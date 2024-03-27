<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buildingresident;
use App\Models\Owner;
use Illuminate\Support\Facades\Validator;



class BuildingResidentController extends Controller
{
    public function registerbuildingresident(Request $request)


    {

        $isValidate = Validator::make($request->all(), [
           

            "residentid" => 'required|exists:users,id',

            "subadminid" => 'required|exists:users,id',


            "country" => "required",
            "state" => "required",
            "city" => "required",
            "buildingname" => "required",
            "floorname" => "required",
            "apartmentid" => "required",

            "residenttype" => "required",
            "committeemember" => "required",
            "ownername" => "nullable",
            "owneraddress" => "nullable",
            "ownermobileno" => "nullable",
            "status" => "required",



        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        

        $resident = new Buildingresident;
        $resident->residentid = $request->residentid;
        $resident->subadminid = $request->subadminid;
        $resident->country = $request->country;
        $resident->state = $request->state;
        $resident->city = $request->city;
        $resident->buildingname = $request->buildingname;
        $resident->floorname = $request->floorname;
        $resident->apartmentid = $request->apartmentid;

        $resident->houseaddress = $request->houseaddress ?? 'NA';

        $resident->vechileno = $request->vechileno ?? 'NA';
        $resident->residenttype = $request->residenttype;
        $resident->propertytype = $request->propertytype;
        $resident->committeemember = $request->committeemember ?? 0;
        $resident->status = $request->status ?? 0;


        $resident->save();
        if ($resident->residenttype == 'Rental') {
            $owner = new Owner;
            $owner->residentid = $resident->residentid;
            $owner->ownername = $request->ownername ?? "NA";
            $owner->owneraddress = $request->owneraddress ?? "NA";

            $owner->ownermobileno = $request->ownermobileno ?? "NA";
            $owner->save();
        }





        return response()->json(
            [

                "success" => true,
                "message" => "Building Resident Register to our system Successfully",
                "data" => $resident,

            ]
        );
    }




    public function loginbuildingresidentdetails ($residentid)

    {
        $data = Buildingresident::where('residentid', $residentid)->get();



    return response()->json(
        [
            "success" => true,
            "data" => $data
        ]
    );

    }
}
