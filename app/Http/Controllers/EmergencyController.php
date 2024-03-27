<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subadmin;
use App\Models\Gatekeeper;


class EmergencyController extends Controller
{
    public function addEmergency(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'residentid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            'subadminid' => 'required|exists:users,id',
            'problem' => 'required',
            'status' => 'required'
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $emergency = new Emergency();
        $emergency->residentid = $request->residentid;
        $emergency->societyid = $request->societyid;
        $emergency->subadminid = $request->subadminid;
        $emergency->problem = $request->problem;
        $emergency->description = $request->description ?? 'NA';
        $emergency->status = $request->status;
        $emergency->save();

        $subadmins = Subadmin::where('subadminid', $request->subadminid)
            ->join('users', 'users.id', '=', 'subadmins.subadminid')->get();

        $gatekeepers = Gatekeeper::where('subadminid', $request->subadminid)
            ->join('users', 'users.id', '=', 'gatekeepers.gatekeeperid')->get();
        $fcm = [];

        foreach ($gatekeepers as $datavals) {
            array_push($fcm, $datavals->fcmtoken);
        }

        foreach ($subadmins as $datavals) {
            array_push($fcm, $datavals->fcmtoken);
        }

        // foreach ($gatekeepers as $datavals) {

        //     array_push($fcm, $datavals['fcmtoken']);
        // }


        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata = [
            'registration_ids' => $fcm,

            "data" => ["type" => 'Emergency','id'=>$emergency->id],
            "android" => [
                "priority" => "high",
                "ttl" => 60 * 60 * 1,
                "android_channel_id" => "pushnotificationapp"

            ],
            "notification" => [
                'title' => '🚨 Emergency 🚨', 
                'body' => $emergency->problem,
            ]

        ];
        $finaldata = json_encode($mydata);
        $headers = array(
            'Authorization: key=' . Config('app.serverkey'),
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $finaldata);
        $result = curl_exec($ch);
        // var_dump($result);
        curl_close($ch);




        return response()->json([
            "data" => $emergency,
            "subadmin" => $subadmins,
            "gatekeepers" => $gatekeepers,

        ]);
    }

    public function viewEmergency($subadminid)
    {


        $emergency = Emergency::where('subadminid', $subadminid)->orderBy('created_at', 'desc')->with('resident')->get();

        return response()->json(["data" => $emergency]);
    }


    public function emergencycount($subadminid)
{

   


$emergencyCount = Emergency::where('subadminid',$subadminid)->where('status','0')->count();
    



    return response()->json([
        "success" => true,
        "data" => $emergencyCount,

    ]);


}
}
