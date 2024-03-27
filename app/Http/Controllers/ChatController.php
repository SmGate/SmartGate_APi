<?php

namespace App\Http\Controllers;
use App\Models\Chat;
use App\Models\Gatekeeper;
use App\Models\Resident;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function conversations(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'sender' => 'required|exists:users,id',
            'reciever' => 'required|exists:users,id',
            'chatroomid' => 'required|exists:chatrooms,id',
            'message' => 'nullable',
            'lastmessage' => 'nullable',

        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $chat = new Chat();
        $chat->sender=$request->sender;
        $chat->reciever=$request->reciever;
        $chat->chatroomid=$request->chatroomid;
        $chat->message=$request->message??'';
        $chat->lastmessage=$request->lastmessage??'';
        $chat->save();

        


        // event(new UserChat($chat));



        return response()->json([
            "success"=>true,
            "data" => $chat]);




    }

    public function viewconversationsneighbours($chatroomid)
    {



        $cov=Chat::where('chatroomid',$chatroomid)->orderBy('created_at', 'desc')->get();

        


        return response()->json([
            "success"=>true,
            "data" => $cov]);




    }


    public function chatneighbours($subadminid)
    {

       $chatneighbours=   Resident::where('subadminid', $subadminid)
       ->where('status',1)->join('users', 'residents.residentid', '=', 'users.id')
       ->get();
    // $chatneighbours=   Resident::where('subadminid', $subadminid)
    //    ->where('status',1)->join('users', 'residents.residentid', '=', 'users.id')
    //    ->paginate(6);


        return
        response()->json(["success"=>true,
        "data" => $chatneighbours]);

    }


    public function chatgatekeepers($subadminid)
    {

        $chatgatekeepers= Gatekeeper::where('subadminid', $subadminid)->join('users', 'gatekeepers.gatekeeperid', '=', 'users.id')->get();


        return
        response()->json(["success"=>true,
        "data" => $chatgatekeepers]);
    }



    public function zegocall($residentid)
    {

        $residents= Resident::where('residentid',$residentid)
->join('users','users.id','=','residents.residentid')->get();



$fcm=[];

foreach ($residents as $datavals) {

    
    array_push($fcm, $datavals['fcmtoken']);

}

        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        // "data"=>["type"=>'Event'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,
            "android_channel_id"=>'call',
            "channel_name"=>'call'

        ],
        "notification"=>['title'=>'call','body'=>'calling',
        
     ]

    ];
    $finaldata=json_encode($mydata);
        $headers = array (
            'Authorization: key=' . Config('app.serverkey'),
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $finaldata );
        $result = curl_exec ( $ch );
        // var_dump($result);
        curl_close ( $ch );

        
    }



}
