<?php

namespace App\Http\Controllers;


use App\Models\Apartment;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
class ApartmentController extends Controller
{
   

    public function addapartments(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
            'fid' => 'required|exists:floors,id',
            'from' => 'required|integer|gt:0',
            'to' => 'required|integer|gt:from',
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $blocks = new Apartment();

        $from = (int) $request->from;
        $to = (int) $request->to;



        for ($i = $from; $i < $to + 1; $i++) {


            $status = $blocks->insert(
                [

                    [
                        "name" => 'Apartment ' . $i,
                        'fid' => $request->fid,
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


   

    public function viewapartmentsforresidents($floorid)
    {
        $apartment = Apartment::where('fid', $floorid)->get();

        return response()->json(["data" => $apartment]);
    }
     public function apartments($fid)

    {

        $apartment =  Apartment::where('fid', $fid)->get();

        return response()->json([
            "success" => true,
            "data" => $apartment,
        ]);
    }
}
