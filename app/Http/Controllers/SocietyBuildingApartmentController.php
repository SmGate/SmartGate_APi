<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Societybuildingapartment;



class SocietyBuildingApartmentController extends Controller
{
    public function addsocietybuildingapartments(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
            'societybuildingfloorid' => 'required|exists:societybuildingfloors,id',
            'from' => 'required|integer',
            'to' => 'required|integer',
            'typeid' => 'integer',
            'type' => 'string',
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $societybuildingapartments = new Societybuildingapartment();

        $from = (int) $request->from;
        $to = (int) $request->to;



        for ($i = $from; $i < $to + 1; $i++) {


            $status = $societybuildingapartments->insert(
                [

                    [
                        "name" => 'Apartment ' . $i,
                        'societybuildingfloorid' => $request->societybuildingfloorid,
                        'typeid' => '1',
                        'type' => 'apartment',
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





    public function viewsocietybuildingapartments($societybuildingfloorid)
    {
        $apartment = Societybuildingapartment::where('societybuildingfloorid', $societybuildingfloorid)->get();

        return response()->json(["data" => $apartment]);
    }
    public function apartments($fid)

    {

        $apartment =  Societybuildingapartment::where('fid', $fid)->get();

        return response()->json([
            "success" => true,
            "data" => $apartment,
        ]);
    }
}