<?php

namespace App\Http\Controllers;


use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FloorController extends Controller
{
    


    public function addfloors(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'buildingid' => 'required|exists:societies,id',

            'from' => 'required|integer|gt:0',
            'to' => 'required|integer|gt:from',

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $floors = new Floor();
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

        // $phases->subadminid = $request->subadminid;

        return response()->json([
            "success" => true,
            "data" => $status,
        ]);
    }



   

    public function viewfloorsforresidents($buildingid)
    {
        $floors = Floor::where('buildingid', $buildingid)->get();

        return response()->json(["data" => $floors]);
    }

public function floors($subadminid)
    {

       
        $floors =  Floor::where('subadminid', $subadminid)->get();





        return response()->json([
            "success" => true,
            "data" => $floors,
        ]);
    }

}
