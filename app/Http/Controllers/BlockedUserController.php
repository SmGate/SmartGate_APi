<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlockedUserController extends Controller
{
    


    public function blockuser(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'userid' => 'required|exists:users,id',
            'blockeduserid' => 'required|exists:users,id',
            'chatroomid' => 'required|exists:chatrooms,id',
            



        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $blockedUser= BlockedUser::where('blockeduserid',$request->blockeduserid)->where('chatroomid',$request->chatroomid)->first();

         

        if($blockedUser==null)
        {
            $blockedUser = new BlockedUser;
            $blockedUser->userid=$request->userid;
            $blockedUser->blockeduserid=$request->blockeduserid;
            $blockedUser->chatroomid=$request->chatroomid;
            $blockedUser->save();
            
        return response()->json(["message" => 'user-blocked']);


        }

      else if ($blockedUser->blockeduserid==$request->blockeduserid&&$blockedUser->chatroomid==$request->chatroomid)
       {

        return response()->json(["message" => 'no-interaction']);


       }

       


    }


    public function checkblockuser(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'chatroomid' => 'required',
            ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $blockedUser= BlockedUser::where('chatroomid',$request->chatroomid)->first();

         

       if ($blockedUser!=null)

       {
        return response()->json([
            'success'=>true,
            'data' => $blockedUser,
            'message'=>'blocked',
            
        ]);

       }
       return response()->json([
        'success'=>false,
        'data' => $blockedUser,
        'message' => $blockedUser??'not-block']);

       


    }



    public function unblockuser(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'blockeduserid' => 'required|exists:users,id',
            'chatroomid' => 'required|exists:chatrooms,id',

            



        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $blockedUser= BlockedUser::where('blockeduserid',$request->blockeduserid)->where('chatroomid',$request->chatroomid);

         
        if ($blockedUser) {
            $blockedUser->delete();
            return response()->json(['message' => 'unblock-successfully']);
        }

    
        

    }


}
