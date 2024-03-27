<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use APP\Models\Houseresidentaddress;
use APP\Models\Resident;
use APP\Models\User;

class verifyhouseresident extends Controller
{

    public function verifyhouseresident(Request $request)

    {
        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'status' => 'required',
            'pid' => 'required',
            'bid' => 'required',
            'sid' => 'required',
            'propertyid' => 'required',
            'vechileno' => 'nullable',
            'measurementid' => 'required'

        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }





        $residents = Houseresidentaddress::where('residentid', $request->residentid)->first();


        $residents->pid = $request->pid;
        $residents->bid = $request->bid;
        $residents->sid = $request->sid;
        $residents->propertyid = $request->propertyid;
        $residents->measurementid = $request->measurementid;
        $residents->update();

        $res = Resident::where('residentid', $residents->residentid)->first();
        $res->status = $request->status;
        $res->vechileno = $request->vechileno ?? '';
        $res->update();


        $user = User::where('id',  $residents->residentid)->first();
        $user->address =  $res->houseaddress;
        $user->update();



        return response()->json([
            "success" => true,
            "data" => $residents

        ]);
    }
}
