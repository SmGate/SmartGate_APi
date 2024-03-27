<?php

namespace App\Http\Controllers;
use App\Models\Gatekeeper;
use App\Models\Preapproveentry;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
class PreApproveEntryController extends Controller
{

    public function addpreapproventry(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'gatekeeperid' => 'required|exists:gatekeepers,gatekeeperid',
            'userid' => 'required|exists:users,id',
            'visitortype' => 'required',
            'name' => 'required',
            'description' => 'nullable',
            'cnic' => 'nullable',
            'mobileno' => 'required',
            'vechileno' => 'nullable',
            'arrivaldate' => 'required|date',
            'arrivaltime' => 'required',
            'status' => 'required',
            'statusdescription' => 'required',


        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $preapprovedentry= new Preapproveentry();

        $preapprovedentry->gatekeeperid=$request->gatekeeperid;
        $preapprovedentry->userid=$request->userid;
        $preapprovedentry->visitortype=$request->visitortype;
        $preapprovedentry->name=$request->name;
        $preapprovedentry->description=$request->description??"";
        $preapprovedentry->cnic=$request->cnic??"";
        $preapprovedentry->mobileno=$request->mobileno;
        $preapprovedentry->vechileno=$request->vechileno??"";
        $preapprovedentry->arrivaldate=  Carbon::parse($request->arrivaldate)->format('y-m-d');
        $preapprovedentry->arrivaltime=$request->arrivaltime;
        $preapprovedentry->status=$request->status;
        $preapprovedentry->statusdescription=$request->statusdescription;

        $preapprovedentry->save();


        $residents= Gatekeeper::where('gatekeeperid',$request->gatekeeperid)
        ->join('users','users.id','=','gatekeepers.gatekeeperid')->get();


        $user=User::find($request->userid);

        $fcm=[];

        foreach ($residents as $datavals) {

            array_push($fcm, $datavals['fcmtoken']);

        }

           
        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        "data"=>["type"=>'PreApproveEntry'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,
            "android_channel_id"=>"smart-gate-notification"

        ],
        "notification"=>['title'=>'Preapproved Entry','body'=>'You have a Preapproved Entry request from '.$user->address,
        
        ]

    ];
    $finaldata=json_encode($mydata);
        $headers = array (
            'Authorization: key=' .  Config('app.serverkey'),
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






        return response()->json([
            "success" => true,
            "data" => $preapprovedentry,

        ]);




    }


    

    public function    searchpreapproventry(Request $request)
    {
       

        $userid= $request->input('userid');
        $q= rawurldecode( $request->input('search'));

        
        $isValidate = Validator::make($request->all(), [
            'userid' => 'required',
            'searchquery' => 'nullable',
          

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

       

        
        $preapproveentry = Preapproveentry::where('userid',$userid)->
        where('name', 'LIKE', '%' . $q . '%')
        // ->orWhere('mobileno', 'LIKE', '%' . $q . '%')
        // ->orWhere('vechileno', 'LIKE', '%' . $q . '%')
        // ->orWhere('visitortype', 'LIKE', '%'. $q. '%')
        ->get();
        

      

        return response()->json(["data" => $preapproveentry]);
    }



public function viewpreapproveentryreports($userid)
{
    $preapproveentryreports= Preapproveentry::where('userid',"=",$userid)->orderByDesc('updated_at')->paginate(6);

    return response()->json([
        "success" => true,
        "data" => $preapproveentryreports,

    ]);

}
    public function updatepreapproveentrystatus(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'id' => 'required|exists:preapproveentries,id',
            'cnic' => 'nullable',
            'vechileno' => 'nullable',
            'status' => 'required',
            'statusdescription' => 'required',

        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        if($request->status==2)

        {

            $currentTime = Carbon::now(); // Get the current time as a Carbon instance
            $checkInTime = $currentTime->format('H:i'); // Format the current time as "h:i A"
            
            $preapproveentryreports = Preapproveentry::Find($request->id);
            $preapproveentryreports->status = $request->status;
            $preapproveentryreports->cnic = $request->cnic??'---';
            $preapproveentryreports->checkintime = $checkInTime;
            $preapproveentryreports->vechileno = $request->vechileno??'---';
            $preapproveentryreports->statusdescription = $request->statusdescription;
            $preapproveentryreports->update();
    
          
    
            $fcm=[];
          
            $user=User::find( $preapproveentryreports->userid);
            array_push($fcm, $user->fcmtoken);
            $bodyData='';
           if ($preapproveentryreports->status==1)
    
           {
    
    $bodyData="The Gatekeeper Approved your Preapprove Entry Request of ".  $preapproveentryreports->visitortype;
           }
    
         else  if ($preapproveentryreports->status==2)
    
           {
    
    $bodyData="Your Preapproved Entry Just Checkin";
           }
    
           else  if ($preapproveentryreports->status==3)
    
           {
    
    $bodyData="Your Preapproved Entry Just Checkout";
           }
    
           
    
               
            $url = 'https://fcm.googleapis.com/fcm/send';
            $mydata=['registration_ids'=>$fcm,
     
            "data"=>["type"=>'PreApproveEntry'],
            "android"=> [
                "priority"=> "high",
                "ttl"=> 60 * 60 * 1,
                "android_channel_id"=>"smart-gate-notification"
    
            ],
            "notification"=>['title'=>'Preapproved Entry','body'=>$bodyData,
            
            ]
    
        ];
        $finaldata=json_encode($mydata);
            $headers = array (
                'Authorization: key=' .  Config('app.serverkey'),
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
    
            return response()->json([
                "success" => true,
                "data" => $preapproveentryreports,
                "message" => "Status Updated Successfully"
            ]);
    
    
        }

        $preapproveentryreports = Preapproveentry::Find($request->id);
        $preapproveentryreports->status = $request->status;
        $preapproveentryreports->cnic = $request->cnic??'---';
        $preapproveentryreports->vechileno = $request->vechileno??'---';
        $preapproveentryreports->statusdescription = $request->statusdescription;
        $preapproveentryreports->update();

      

        $fcm=[];
      
        $user=User::find( $preapproveentryreports->userid);
        array_push($fcm, $user->fcmtoken);
        $bodyData='';
       if ($preapproveentryreports->status==1)

       {

$bodyData="The Gatekeeper Approved your Preapprove Entry Request of ".  $preapproveentryreports->visitortype;
       }

     else  if ($preapproveentryreports->status==2)

       {

$bodyData="Your Preapproved Entry Just Checkin";
       }

       else  if ($preapproveentryreports->status==3)

       {

$bodyData="Your Preapproved Entry Just Checkout";
       }

       

           
        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        "data"=>["type"=>'PreApproveEntry'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,
            "android_channel_id"=>"smart-gate-notification"

        ],
        "notification"=>['title'=>'Preapproved Entry','body'=>$bodyData,
        
        ]

    ];
    $finaldata=json_encode($mydata);
        $headers = array (
            'Authorization: key=' .  Config('app.serverkey'),
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




     



        return response()->json([
            "success" => true,
            "data" => $preapproveentryreports,
            "message" => "Status Updated Successfully"
        ]);

        


    }







    public function updatepreapproveentrycheckoutstatus(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'id' => 'required|exists:preapproveentries,id',
            'status' => 'required',
            'statusdescription' => 'required',

        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $currentTime = Carbon::now(); // Get the current time as a Carbon instance
        $checkoutTime = $currentTime->format('H:i'); // Format the current time as "h:i A"
        

        $preapproveentryreports = Preapproveentry::Find($request->id);
        $preapproveentryreports->status = $request->status;
        $preapproveentryreports->checkouttime = $checkoutTime;
        $preapproveentryreports->statusdescription = $request->statusdescription;
        $preapproveentryreports->update();

        $fcm=[];
      
        $user=User::find( $preapproveentryreports->userid);
        array_push($fcm, $user->fcmtoken);
        $bodyData='';

        
 
          if ($preapproveentryreports->status==3)
 
        {
 
 $bodyData="Your Preapproved Entry Just Checkout";
        }
 
        
 
            
         $url = 'https://fcm.googleapis.com/fcm/send';
         $mydata=['registration_ids'=>$fcm,
  
         "data"=>["type"=>'PreApproveEntry'],
         "android"=> [
             "priority"=> "high",
             "ttl"=> 60 * 60 * 1,
             "android_channel_id"=>"smart-gate-notification"
 
         ],
         "notification"=>['title'=>'Preapproved Entry','body'=>$bodyData,
         
         ]
 
     ];
     $finaldata=json_encode($mydata);
         $headers = array (
             'Authorization: key=' .  Config('app.serverkey'),
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
 
 
 
 
 


        return response()->json([
            "success" => true,
            "data" => $preapproveentryreports,
            "message" => "Status Updated Successfully"
        ]);


    }


public function getgatekeepers($subadminid)
{
    $gatekeeper=GateKeeper::
    where('subadminid',  $subadminid )->
    join('users','users.id','=','gatekeepers.gatekeeperid')->get();



    return response()->json([
        "success" => true,
        "data" => $gatekeeper,

    ]);


}



public function unapprovedpreapproveentrycount($gatekeeperid)
{

   


$preapproveentryreports = Preapproveentry::where('gatekeeperid',$gatekeeperid)->where('status',0)->count();
    



    return response()->json([
        "success" => true,
        "data" => $preapproveentryreports,

    ]);


}



public function preapproveentryresidents($userid)
{

    $user= Preapproveentry::where('gatekeeperid','=',$userid)->join('users','users.id','=','preapproveentries.userid')->where('status',1)->orwhere('status',2)->distinct()->get();
    $res = $user->unique('userid');

    return response()->json([
        "success" => true,
        "data" => $res->values()->all(),
    ]);
}



public function preapproveentries($userid)
{

    $preapproveentries= Preapproveentry::where('userid','=',$userid)->where('status','!=',0)->where('status','!=',3) ->get();


    return response()->json([
        "success" => true,
        "data" => $preapproveentries,
    ]);
}

public function preapproveentryhistories($userid)
{

    $preapproveentries= Preapproveentry::where('userid','=',$userid)->where('status','=',3)->orderByDesc('updated_at')
    ->paginate(6);


    return response()->json([
        "success" => true,
        "data" => $preapproveentries,
    ]);
}




public function preapproventrynotifications($userid)

{
    $unapproveentries= Preapproveentry::where('gatekeeperid','=',$userid)->
    join('users','users.id','=','preapproveentries.userid')->where('status',0)->select(
        "users.firstname",
        "users.lastname",
        "users.cnic",
        "users.address",
        "users.mobileno",
        "users.roleid",
        "users.rolename",
        "users.image",
        'preapproveentries.*',
        


    )->GET();



    return response()->json([
        "success" => true,
        "data" => $unapproveentries,
    ]);

}








}
