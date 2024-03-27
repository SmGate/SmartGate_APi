<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussionchat;
use App\Event\DiscussionChatEvent;

use Illuminate\Support\Facades\Validator;
class DiscussionChatController extends Controller
{
    




    public function discussionchats(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

           
            'residentid' => 'required|exists:users,id',
            'discussionroomid' => 'required|exists:discussionrooms,id',
            'message' => 'nullable',
            

        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $chat = new Discussionchat();
        $chat->discussionroomid=$request->discussionroomid;
        $chat->residentid=$request->residentid;
        $chat->message=$request->message??'';
        $chat->save();


        $cov=Discussionchat::where('id',$chat->id)->with('user')
        ->get();
       
        // event(new UserChat($chat));
       
        
        event(new DiscussionChatEvent( response()->json([
            "success"=>true,
            "data" => $cov])));


       




        return response()->json([
            "success"=>true,
            "data" => $cov]);




    }



    public function alldiscussionchats($discussionroomid)
    {



        $cov=Discussionchat::where('discussionroomid',$discussionroomid)->
        
        orderBy('created_at', 'desc') 
        ->with('user')
        ->get();

        


        return response()->json([
            "success"=>true,
            "data" => $cov]);




    }
}
