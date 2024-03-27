<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussionroom;

use Illuminate\Support\Facades\Validator;
class DiscussionRoomController extends Controller
{
    public function creatediscussionroom (Request $request)
    {
        

        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
           

        ]);



        if ($isValidate->fails()) {


        
                    return response()->json([
                        "errors" => $isValidate->errors()->all(),
                        "success" => false

                    ], 403);

                }

            

    $check =Discussionroom::where("creator",$request->subadminid)->get()->first();

  if($check==null)
  {

    $chatroom = new Discussionroom();
    $chatroom->creator=$request->subadminid;
    $chatroom->save();
    
  }
  $disscussionroom=Discussionroom::where("creator",$request->subadminid)->get();


     


        return response()->json(["data" =>   $disscussionroom]);


    }



 } 