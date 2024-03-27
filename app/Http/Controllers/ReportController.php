<?php
namespace App\Http\Controllers;
use App\Models\Report;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

        // 0=> pending
        // 2=> in progress
        // 3=> rejected
        // 4=> completed
class ReportController extends Controller
{
    public function reporttoadmin(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'userid' => 'required|exists:users,id',
            'subadminid' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'status' => 'required',
            'statusdescription' => 'required',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $report = new Report();
        $report->userid = $request->userid;
        $report->subadminid = $request->subadminid;
        $report->title = $request->title;
        $report->description = $request->description;
        // $report->date = Carbon::parse($request->date)->format('y-m-d');
        $report->status = $request->status;
        $report->statusdescription = $request->statusdescription;
        $report->save();

        $residents= User::where('id',$request->subadminid)->get();
        
        
        $fcm=[];
        
        foreach ($residents as $datavals) {
        
            array_push($fcm, $datavals['fcmtoken']);
        
        }
        
                $url = 'https://fcm.googleapis.com/fcm/send';
                $mydata=['registration_ids'=>$fcm,
         
                "data"=>["type"=>'ReportNotification'],
                "android"=> [
                    "priority"=> "high",
                    "ttl"=> 60 * 60 * 1,
                    // "android_channel_id"=>'1',
                    // "channel_name"=>'call'
        
                ],
                "notification"=>['title'=>'Complain','body'=>$report->description,
                
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
            'message' => 'success',
            'data' => $report
        ], 200);
    }
    public function deletereport($id)
    {
        $report =   Report::find($id);
        if ($report != null) {
            $report = Report::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                "data" => $report, "message" => " Report Delete Successfully"
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Report not deleted"
            ], 403);
        }
    }
    public function adminreports($residentid)
    {
        $report = Report::where('userid', $residentid)->where('status','!=',3)->where('status' ,'!=' , 4)->orderByDesc('updated_at')->paginate(6);
        return response()->json([
            "success" => true,
            "data" => $report,
        ]);
    }







    public function updatereportstatus(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'id' => 'required|exists:reports,id',
            'userid' => 'required|exists:users,id',
            'status' => 'required',
            'statusdescription' => 'nullable',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $report = Report::Find($request->id);
        $report->status = $request->status;
        $report->statusdescription = $request->statusdescription??"NA";
        $report->update();

       

         $notificationtext="";
          $fcm=[];
          $isRejected=false;
         if($report->status ==2)

         { $residents= User::where('id',$request->userid)->get();
            $notificationtext="Your Complaint Request"." ".$report->title." "."has been"." "."Accepted ✅"." "."by Admin.";
            foreach ($residents as $datavals) {
        
            array_push($fcm, $datavals['fcmtoken']);
        
        }
        
        }
        

           else   if($report->status ==4)

         { $residents= User::where('id', $report->subadminid)->get();
            $res = Resident::where('residentid', $request->userid)->first();


            $notificationtext="The complaint from the resident at"." ".$res->houseaddress." "."regarding the"." ".$report->title." "."has been resolved by the maintenance team.✅";
            foreach ($residents as $datavals) {
        
            array_push($fcm, $datavals['fcmtoken']);
        
        }
        
        
        }
        

        else   if($report->status ==3)

        {
            $isRejected=true;

             $residents= User::where('id', $report->userid)->get();
           $res = Resident::where('residentid', $request->userid)->first();


           $notificationtext="The complaint rejected 🔴 Reason : $report->statusdescription ";
           foreach ($residents as $datavals) {
       
           array_push($fcm, $datavals['fcmtoken']);
       
       }
       
       
       }
       
        
       
        
       
        
                $url = 'https://fcm.googleapis.com/fcm/send';
                $mydata=['registration_ids'=>$fcm,
         
                "data"=>["type"=>$isRejected?'ReportHistory' : 'Report',],
                "android"=> [
                    "priority"=> "high",
                    "ttl"=> 60 * 60 * 1,
                    // "android_channel_id"=>'1',
                    // "channel_name"=>'call'
        
                ],
                "notification"=>['title'=> 'Complain','body'=> $notificationtext,
                
                
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
            "data" => $report,
            "message" => "Status Updated Successfully"
        ]);
    }
    public function reportedresidents($subadminid)
    {
        $report =  User::where('subadminid', $subadminid)->
        join('reports', 'reports.userid', '=', 'users.id')->where('status',2)->distinct()->get();
        $res = $report->unique('userid');
        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }
    public function reports($subadminid, $userid)
    {
        $reports =  Report::where('subadminid', $subadminid)->where('userid', $userid)->where('status',2)->GET();
        return response()->json([
            "success" => true,
            "data" => $reports
        ]);
    }
    public function pendingreports($subadminid)
    {
        $reports =  Report::where('subadminid', $subadminid)->where('status',0)
            ->join('users', 'reports.userid', '=', 'users.id')->select(
                'reports.id',
                "users.firstname",
                "users.lastname",
                "users.cnic",
                "users.address",
                "users.mobileno",
                "users.roleid",
                "users.rolename",
                "users.image",
                "reports.userid",
                "reports.subadminid",
                "reports.title",
                "reports.description",
                "reports.status",
                "reports.statusdescription",
                 "reports.created_at",
                 "reports.updated_at",
            )->orderBy('created_at','desc')-> GET();
        return response()->json([
            "success" => true,
            "data" => $reports
        ]);
    }
    public function historyreportedresidents($subadminid)
    {
        $report =  User::where('subadminid', $subadminid)->join('reports', 'reports.userid', '=', 'users.id')->where('status',3)->orwhere('status',4)->distinct()->get();
        $res = $report->unique('userid');
        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }
    public function historyreports($subadminid, $userid)
    {   
        
        $reports =  Report::where('subadminid', $subadminid)->where('userid', $userid)->whereIn('status', [3, 4])->orderByDesc('updated_at')->paginate(6);
        
        return response()->json([
            "success" => true,
            "data" => $reports
        ]);
    }


    public function pendingreportscount($subadminid)
{

   


$reportsCount = Report::where('subadminid',$subadminid)->where('status',0)->count();
    



    return response()->json([
        "success" => true,
        "data" => $reportsCount,

    ]);


}


public function inprogressreportscount($subadminid)
{

   


$reportsCount = Report::where('subadminid',$subadminid)->where('status','2')->count();
    



    return response()->json([
        "success" => true,
        "data" => $reportsCount,

    ]);


}
}
