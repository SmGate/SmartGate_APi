<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Notice;
use App\Models\Resident;
use App\Models\Gatekeeper;

use Carbon\Carbon;
class NoticeBoardController extends Controller
{
    public function addnoticeboarddetail(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'noticetitle' => 'required|string',
            'noticedetail' => 'required|string',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate',
            'starttime' => 'date_format:H:i|required',
            'endtime' => 'date_format:H:i|after:starttime|required',

            'status' => 'required',
            'subadminid' => 'required|exists:users,id',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $notice = new Notice();
        $notice->noticetitle = $request->noticetitle;
        $notice->noticedetail = $request->noticedetail;
        $notice->startdate =  Carbon::parse($request->startdate);
        $notice->enddate =  Carbon::parse($request->enddate);
        $notice->starttime = $request->starttime;
        $notice->endtime = $request->endtime;

        $notice->status = $request->status;
        $notice->subadminid = $request->subadminid;
        $notice->save();
        $fcm=[];

        $residents= Resident::where('subadminid',$request->subadminid)
        ->join('users','users.id','=','residents.residentid')->get();

        

        $gatekeepers= Gatekeeper::where('subadminid',$request->subadminid)
        ->join('users','users.id','=','gatekeepers.gatekeeperid')->get();

        foreach ($gatekeepers as $datavals) {

            array_push($fcm, $datavals['fcmtoken']);

        }

        foreach ($residents as $datavals) {

            array_push($fcm, $datavals['fcmtoken']);

        }

           
        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        "data"=>["type"=>'Noticeboard'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,
            "android_channel_id"=>"one"

        ],
        "notification"=>['title'=>'Noticeboard','body'=>$notice->noticedetail,
        
        
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
            'data' => $notice
        ], 200);
    }
    public function viewallnotices($subadminid)
    {
        $notice = Notice::where('subadminid', $subadminid)->orderBy('created_at','desc')->get();
        return response()->json([
            "success"=>true,
            "data" => $notice,
        

    ]);
    }
    public function deletenotice($id)
    {
        $notice =   Notice::find($id);
        if ($notice != null) {
            $notice = Notice::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                "data" => $notice, "message" => "delete Notice successfully"
            ], 200);
        } else {
            return response()->json([
                "data" => false,
                "message" => "Notice Not deleted"
            ]);
        }
    }
    public  function updatenotice(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'noticetitle' => 'required|string',
            'noticedetail' => 'required|string',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate',
            // 'starttime' => 'date_format:H:i',
            // 'endtime' => 'date_format:H:i|after:starttime',

            'status' => 'required',
            'id' => 'required|exists:notices,id',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $notice = Notice::find($request->id);
        $notice->noticetitle = $request->noticetitle;
        $notice->noticedetail = $request->noticedetail;
        $notice->startdate =  Carbon::parse($request->startdate)->format('y-m-d');
        $notice->enddate =  Carbon::parse($request->enddate)->format('y-m-d');
        // $notice->starttime = $request->starttime;
        // $notice->endtime = $request->endtime;

        $notice->status = $request->status;
        $notice->save();
        return response()->json([
            "success" => true,
            "data" => $notice,
            "message" => "notice update successfully"
        ]);
    }
}
