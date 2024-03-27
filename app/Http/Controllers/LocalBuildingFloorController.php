<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localbuildingfloor;


use Illuminate\Support\Facades\Validator;

class LocalBuildingFloorController extends Controller
{
    //
    public function addlocalbuildingfloors(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'buildingid' => 'required|exists:societies,id',

            'from' => 'required|integer',
            'to' => 'required|integer',

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $floors = new Localbuildingfloor();
        $from = (int) $request->from;
        $to = (int) $request->to;


        for ($i = $from; $i < $to + 1; $i++) {


            $status = $floors->insert(
                [

                    [
                        "name" => 'Floor ' . $i,
                        'subadminid' => $request->subadminid,
                        'buildingid' => $request->buildingid,

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],

                ]
            );
        }


        return response()->json([
            "success" => true,
            "data" => $status,
        ]);
    }



    


    public function viewlocalbuildingfloors($buildingid)
    {
        $floors = Localbuildingfloor::where('buildingid', $buildingid)->get();

        return response()->json(["data" => $floors]);
    }

    public function floors($subadminid)
    {

        
        $floors =  Localbuildingfloor::where('subadminid', $subadminid)->get();





        return response()->json([
            "success" => true,
            "data" => $floors,
        ]);
    }
}