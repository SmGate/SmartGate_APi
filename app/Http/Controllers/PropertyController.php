<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PropertyController extends Controller
{
    public function addproperties(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            'superadminid' => 'required|exists:users,id',
            'address' => 'required',
            'from' => 'required|integer|gt:0',
            'to' => 'required|integer|gt:from',
            'dynamicid' => 'required',
            'type' => 'required',
            
            

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $properties = new Property();
        $from = (int) $request->from;
        $to = (int) $request->to;

        for ($i = $from; $i < $to + 1; $i++) {


            $status = $properties->insert([

                [
                    "address" =>  $request->address.$i,
                    'subadminid' => $request->subadminid,
                    'superadminid' => $request->superadminid,
                    'societyid' => $request->societyid,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'iteration' => $i,
                    'dynamicid' => $request->dynamicid,
                    'type' => $request->type,
                    
                    

                ],

            ]);
        }


        return response()->json([
            "success" => true,
            "data" => $status,
        ]);
    }




    public function properties($dynamicid,$type)
    {
        $properties =  Property::where('dynamicid', $dynamicid)
        ->where('type',$type)->get();


        return response()->json([
            "success" => true,
            "data" => $properties,
        ]);
    }
    public function viewpropertiesforresidents($dynamicid,$type)
    {
        $properties = Property::where('dynamicid', $dynamicid)->where('type',$type)->get();
        return response()->json(["data" => $properties]);
    }
}