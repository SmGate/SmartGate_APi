<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Societybuilding;

use Illuminate\Support\Facades\Validator;


class SocietyBuildingController extends Controller
{
    //
    public function addsocietybuilding(Request $request)
    {
        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            'superadminid' => 'required|exists:users,id',
            'societybuildingname' => 'required',
            'dynamicid' => 'required',
            'type' => 'required',


        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $societybuildingresident = new Societybuilding;

        $societybuildingresident->subadminid = $request->subadminid;
        $societybuildingresident->societyid = $request->societyid;
        $societybuildingresident->superadminid = $request->superadminid;

        $societybuildingresident->societybuildingname = $request->societybuildingname;
        $societybuildingresident->dynamicid = $request->dynamicid;
        $societybuildingresident->type = $request->type;




        $societybuildingresident->save();




        return response()->json(
            [

                "success" => true,
                "message" => "Society Building Register to our system Successfully",
                "data" => $societybuildingresident,

            ]
        );
    }




    public function societybuildings($dynamicid, $type)
    {


        $societybuildingresident = Societybuilding::where('dynamicid', $dynamicid)->where('type', $type)->get();





        return response()->json([
            "success" => true,
            "data" => $societybuildingresident,

        ]);
    }


    public function allsocietybuildings($subadminid)
    {


        $societybuildingresident = Societybuilding::where('subadminid', $subadminid)->get();





        return response()->json([
            "success" => true,
            "data" => $societybuildingresident,

        ]);
    }
  
  
}
