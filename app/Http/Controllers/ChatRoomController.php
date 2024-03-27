<?php

namespace App\Http\Controllers;
use App\Models\Chatroom;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class ChatRoomController extends Controller
{
    

    public function createchatroom (Request $request)
    {
        

        $isValidate = Validator::make($request->all(), [

            'sender' => 'required|exists:users,id',
            'receiver' => 'required|exists:users,id'

        ]);



        if ($isValidate->fails()) {


            

            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);

        }


        $chatroom=Chatroom::where('sender',$request->sender)->where(
            "receiver",$request->receiver)
        ->get();

        if($chatroom->isEmpty() )

        {
            
            $chatroom2=  Chatroom::where('sender',$request->receiver)->where(
                "receiver",$request->sender)
            ->get();

            
            if($chatroom2->isEmpty() )
            {

            $chatroom = new Chatroom();
            $chatroom->sender=$request->sender;
            $chatroom->receiver=$request->receiver;
            $chatroom->save();

            return response()->json(["data" => [$chatroom]]);
            }

            
    
            return response()->json(["data" => $chatroom2]);



        }
        
       
        
         
    

        return response()->json(["data" => $chatroom]);


   


 
}


public function chatRequestStatus(Request $request)
{


    $isValidate = Validator::make($request->all(), [

        'id' => 'required|exists:chatrooms,id',
        'status' => 'required|in:default,block,pending,accepted,rejected',
        'sender' => 'required|exists:users,id',
        'receiver' => 'required|exists:users,id'


    ]);


    if ($isValidate->fails()) {


        return response()->json([
            "errors" => $isValidate->errors()->all(),
            "success" => false
        ], 403);

    }

    $chatRoom =   Chatroom::find($request->id);
    
    if ($request->status=='accepted')

    {
        $chatRoom->receiver=$chatRoom->sender;
     $chatRoom->sender=$request->receiver;
     
     
     $chatRoom->status=$request->status;
     $chatRoom->update();

     return response()->json([
        "data" => [$chatRoom],
    ]);

    }


   else if ($request->status=='default')

    {
        $chatRoom->sender=$request->sender;
     $chatRoom->receiver=$request->receiver;
     
     
     $chatRoom->status=$request->status;
     $chatRoom->update();


     return response()->json([
        "data" => [$chatRoom],
    ]);

    }
    

    else  if ($request->status=='rejected')

    {
  
        $chatRoom->sender=$request->sender;
        $chatRoom->receiver=$request->receiver;
        $chatRoom->status=$request->status;
        $chatRoom->update();
   
     
     
     return response()->json([
        "data" => [$chatRoom],
    ]);

    }

    




   




}


public function sendChatRequestStatus(Request $request)
{


    $isValidate = Validator::make($request->all(), [

        'id' => 'required|exists:chatrooms,id',
        'status' => 'required|in:default,block,pending,accepted,rejected',
        'sender' => 'required|exists:users,id',
        'receiver' => 'required|exists:users,id'


    ]);


    if ($isValidate->fails()) {


        return response()->json([
            "errors" => $isValidate->errors()->all(),
            "success" => false
        ], 403);

    }

    $chatRoom =   Chatroom::find($request->id);
    $chatRoom->status=$request->status;
    // $chatRoom->sender=$request->sender;
    // $chatRoom->receiver=$request->receiver;
    

    
    $chatRoom->update();




    return response()->json([
        "data" => [$chatRoom],
    ]);




}


}
