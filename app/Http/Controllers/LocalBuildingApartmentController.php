<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localbuildingapartment;


use Illuminate\Support\Facades\Validator;

class LocalBuildingApartmentController extends Controller
{
    public function addlocalbuildingapartments(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
            'localbuildingfloorid' => 'required|exists:localbuildingfloors,id',
            'from' => 'required|integer',
            'to' => 'required|integer',

        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $localbuildingapartments = new Localbuildingapartment();

        $from = (int) $request->from;
        $to = (int) $request->to;



        for ($i = $from; $i < $to + 1; $i++) {


            $status = $localbuildingapartments->insert(
                [

                    [
                        "name" => 'Apartment ' . $i,
                        'localbuildingfloorid' => $request->localbuildingfloorid,

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


   




    public function viewlocalbuildingapartments($localbuildingfloorid)
    {
        $apartment = Localbuildingapartment::where('localbuildingfloorid', $localbuildingfloorid)->get();

        return response()->json(["data" => $apartment]);
    }
    public function apartments($fid)

    {

        $apartment =  Localbuildingapartment::where('fid', $fid)->get();

        return response()->json([
            "success" => true,
            "data" => $apartment,
        ]);
    }
}

