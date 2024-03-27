<?php

namespace App\Http\Controllers;
use App\Models\Measurement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function addmeasurement(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'type' => 'required|string',
            'unit' => 'required|string',
            'charges' => 'required',
            'latecharges' => 'required',
            'appcharges' => 'required',
            'tax' => 'required',
            'area' => 'required',
            'status' => 'nullable',
            'subadminid' => 'required|exists:users,id',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $measurement= new Measurement();
        $measurement->type = $request->type;
        $measurement->unit = $request->unit;
        $measurement->charges = $request->charges;
        $measurement->area = $request->area;
        $measurement->status = $request->status??0;
        $measurement->subadminid = $request->subadminid;
        $measurement->appcharges = $request->appcharges;
        $measurement->latecharges = $request->latecharges;
        $measurement->tax = $request->tax;
        $measurement->save();

        return response()->json([
            'message' => 'success',
            'data' => $measurement
        ], 200);
    }


    public function housesapartmentmeasurements($subadminid , $type)
    {
     
   
    $houses = Measurement::where('subadminid',$subadminid)->where('type',$type) ->get();

    return response()->json([
        'message' => 'success',
        'data' => $houses
    ]);
    }




    


}
