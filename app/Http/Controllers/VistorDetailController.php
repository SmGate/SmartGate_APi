<?php

namespace App\Http\Controllers;

use App\Models\Vistordetail;
use App\Models\Resident;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class VistorDetailController extends Controller
{

    public function addvistordetail(Request $request)
    {


        $isValidate = Validator::make($request->all(), [
            'gatekeeperid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            'subadminid' => 'required|exists:users,id',

            'houseaddress' => 'nullable',
            'visitortype' => 'required',
            'name' => 'required',

            'cnic' => 'nullable',
            'mobileno' => 'required',
            'vechileno' => 'nullable',
            'arrivaldate' => 'required|date',
            'arrivaltime' => 'required',
            'checkoutdate' => 'required|date',
            'checkouttime' => 'required',

            'status' => 'required',
            'statusdescription' => 'required',


        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $vistordetail = new Vistordetail();

        $vistordetail->gatekeeperid = $request->gatekeeperid;
        $vistordetail->societyid = $request->societyid;
        $vistordetail->subadminid = $request->subadminid;

        $vistordetail->houseaddress = $request->houseaddress ?? 'Society Vistor';
        $vistordetail->visitortype = $request->visitortype;
        $vistordetail->name = $request->name;

        $vistordetail->cnic = $request->cnic ?? "";
        $vistordetail->mobileno = $request->mobileno;
        $vistordetail->vechileno = $request->vechileno;
        $vistordetail->arrivaldate =  Carbon::parse($request->arrivaldate)->format('y-m-d');
        $vistordetail->arrivaltime = $request->arrivaltime;
        $vistordetail->checkoutdate =  Carbon::parse($request->checkoutdate)->format('y-m-d');
        $vistordetail->checkouttime = $request->checkouttime;

        $vistordetail->status = $request->status;
        $vistordetail->statusdescription = $request->statusdescription;

        $vistordetail->save();


        return response()->json([
            "success" => true,
            "data" => $vistordetail,

        ]);
    }



    public function viewvistordetail($societyid)
    {
        $vistors = Vistordetail::where('societyid', "=", $societyid)->where('status', "=", 0)->orderBy('id', 'DESC')->get();

        return response()->json([
            "success" => true,
            "data" => $vistors,

        ]);
    }

    public function searchResident($subadminid)
    {
        $resident = Resident::where('subadminid', "=", $subadminid)->get();

        return response()->json([
            "success" => true,
            "data" => $resident,

        ]);
    }
    public function updateVistorStatus(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'id' => 'required|exists:vistordetails,id',
            'checkoutdate' => 'required|date',
            'checkouttime' => 'required',
            'status' => 'required',
            'statusdescription' => 'required',

        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $vistor = Vistordetail::Find($request->id);
        $vistor->status = $request->status;

        $vistor->checkoutdate = Carbon::parse($request->checkoutdate)->format('y-m-d');
        $vistor->checkouttime = $request->checkouttime;

        $vistor->statusdescription = $request->statusdescription;
        $vistor->update();
        return response()->json([
            "success" => true,
            "data" => $vistor,
            "message" => "Status Updated Successfully"
        ]);
    }
}