<?php

namespace App\Http\Controllers;

use App\Models\Subadmin;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resident;
use App\Models\Owner;
use App\Models\Property;
use App\Models\Societybuildingapartment;
use App\Models\Localbuildingapartmentresidentaddress;
use App\Models\Houseresidentaddress;
use App\Models\Localbuildingapartment;
use App\Models\Apartmentresidentaddress;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class ResidentController extends Controller
{

    public function checkChatVisibility(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'residentid' => 'required|exists:residents,residentid',
            ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $chatVisibilty= Resident::where('residentid',$request->residentid)->first();

         

       if ($chatVisibilty->visibility=='none')

       {
        return response()->json([
            'success'=>true,
            'data' => $chatVisibilty->visibility,
            
            
        ]);

       }
       return response()->json([
        'success'=>true,
        'data' => $chatVisibilty->visibility,
        
        ]
    
    );

       


    }


      public function updateChatVisibility(Request $request)
      {

        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'visibility' => 'nullable',
            
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }



$residentId=$request->residentid;

        $resident = Resident::where('residentid',$residentId)->get()->first();
       
     
        $resident->visibility=$request->visibility;
        
        $resident->update();

        return response()->json([
            "success" => true,
            "status"=>$resident->visibility,

        
        ]);

      }


      public function updateUserName(Request $request)
      {

        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'username' => 'required|unique:residents',
            
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }



$residentId=$request->residentid;

        $resident = Resident::where('residentid',$residentId)->get()->first();
       
     
        $resident->username=$request->username;
        
        $resident->update();

        return response()->json([
            "success" => true,
            "message"=>"Username updated Successfully"

        
        ]);

      }

      public function searchresident($subadminid, $q)
      {
  
  
          $resident = Resident::where(function ($query) use ($q) {
              $query->where('firstname', 'LIKE', '%' . $q . '%')
                  ->orWhere('lastname', 'LIKE', '%' . $q . '%')
                  ->orWhere('mobileno', 'LIKE', '%' . $q . '%')
                  ->orWhere('address', 'LIKE', '%' . $q . '%');
          })
              ->where('status', 1)->where('subadminid', $subadminid)->join('users', 'users.id', '=', 'residents.residentid',)->get();
  
  
          // $data = Resident::join('users', 'users.id', '=', 'residents.residentid',)
          //     ->Where('residents.residentid', $residentid)
          //     ->Where('users.firstname', 'LIKE', '%' . $q . '%')
          //     ->orWhere('users.lastname', 'LIKE', '%' . $q . '%')
          //     ->orWhere('users.mobileno', 'LIKE', '%' . $q . '%')
          //     //->orWhere('users.cnic', 'LIKE', '%' . $q . '%')
  
  
          //     ->orWhere('users.address', 'LIKE', '%' . $q . '%')->with('bills')
          //     // ->orWhere('residents.vechileno', 'LIKE', '%' . $q . '%')
          //     ->get();
  
  
          return response()->json(
              [
                  "success" => true,
                  "residentslist" => $resident
              ]
          );
      }
  
      public function filterResident($subadminid, $type)
      {
  
  
          $resident = Resident::where('propertytype', $type)->where('subadminid', $subadminid)
              ->join('users', 'users.id', '=', 'residents.residentid',)->get();
  
  
  
  
          return response()->json(
              [
                  "success" => true,
                  "residentslist" => $resident
              ]
          );
      }
  
    public function registerresident(Request $request)


    {

        $isValidate = Validator::make($request->all(), 
        [


            "residentid" => 'required|exists:users,id',
            "subadminid" => 'required|exists:users,id',

            "country" => "nullable",
            "state" => "nullable",
            "city" => "nullable",
            "houseaddress" => "required",
            "residenttype" => "required",
            "propertytype" => "required",
            "committeemember" => "required",
            "status" => "required",
            "vechileno" => "nullable",

            /* owner details */

            "ownername" => "nullable",
            "owneraddress" => "nullable",
            "ownermobileno" => "nullable",

            /* apartment/houses details */

            "societyid" => "nullable",
            "pid" => "nullable",
            "bid" => "nullable",
            "sid" => "nullable",
            "propertyid" => "nullable",
            "buildingid" => "nullable",
            "societybuildingfloorid" => "nullable",
            "societybuildingapartmentid" => "nullable",
            "measurementid" => "nullable"



        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }



        if ($request->propertytype == 'house')  {




            $property = Property::find($request->propertyid);
            

            if($property->occupied==1)

            {
                return response()->json(
                    [
        
                        "success" => true,
                        "message" => "Property Already Ocuupied by an other User.",
                        
        
                    ],409
                );


            }
    


            $resident = new Resident;
            $resident->residentid = $request->residentid;
            $resident->subadminid = $request->subadminid;
            $resident->country = $request->country??"";
            $resident->state = $request->state??"";
            $resident->city = $request->city??"";
            $resident->houseaddress = $request->houseaddress ?? 'NA';
            $resident->vechileno = $request->vechileno ?? '';
            $resident->residenttype = $request->residenttype;
            $resident->propertytype = $request->propertytype;
            $resident->committeemember = $request->committeemember ?? 0;
            $resident->status = $request->status ?? 0;
            $resident->save();

            if ($resident->residenttype == 'Rental') {
                $owner = new Owner;
                $owner->residentid = $resident->residentid;
                $owner->ownername = $request->ownername ?? "NA";
                $owner->owneraddress = $request->owneraddress ?? "NA";
                $owner->ownermobileno = $request->ownermobileno ?? "NA";
                $owner->save();
            }
    
    


            $address  = new Houseresidentaddress;
            $address->residentid = $request->residentid;
            $address->societyid = $request->societyid;
            $address->pid = $request->pid??0;
            $address->bid = $request->bid??0;
            $address->sid = $request->sid??0;
            $address->propertyid = $request->propertyid;
            $address->measurementid = $request->measurementid;
            $address->save();

            $subadmins = Subadmin::where('subadminid', $request->subadminid)
            ->join('users', 'users.id', '=', 'subadmins.subadminid')->get();

        $fcm = [];

      

        foreach ($subadmins as $datavals) {
            array_push($fcm, $datavals->fcmtoken);
        }

       

        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata = [
            'registration_ids' => $fcm,

            "data" => ["type" => 'Verification'],
            "android" => [
                "priority" => "high",
                "ttl" => 60 * 60 * 1,
                "android_channel_id" => "pushnotificationapp"

            ],
            "notification" => [
                'title' => 'Verification ✅', 
                'body' => 'You have Verification request from '. $resident->houseaddress.".",
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


            


            


            return response()->json(
                [
    
                    "success" => true,
                    "message" => "User Register  Successfully.",
                    
    
                ]
            );
        } 
        else if($request->propertytype == 'apartment')
        {

            
            $societyBuildingaAartment = Societybuildingapartment::find($request->societybuildingapartmentid);
            

            if($societyBuildingaAartment ->occupied==1)

            {
                return response()->json(
                    [
        
                        "success" => true,
                        "message" => "Apartment Already Ocuupied by an other User.",
                        
        
                    ],409
                );


            }
    

            $resident = new Resident;
            $resident->residentid = $request->residentid;
            $resident->subadminid = $request->subadminid;
            $resident->country = $request->country??"";
            $resident->state = $request->state??"";
            $resident->city = $request->city??"";
            $resident->houseaddress = $request->houseaddress ?? 'NA';
            $resident->vechileno = $request->vechileno ?? '';
            $resident->residenttype = $request->residenttype;
            $resident->propertytype = $request->propertytype;
            $resident->committeemember = $request->committeemember ?? 0;
            $resident->status = $request->status ?? 0;
            $resident->save();

            if ($resident->residenttype == 'Rental') {
                $owner = new Owner;
                $owner->residentid = $resident->residentid;
                $owner->ownername = $request->ownername ?? "NA";
                $owner->owneraddress = $request->owneraddress ?? "NA";
                $owner->ownermobileno = $request->ownermobileno ?? "NA";
                $owner->save();
            }
    
    


            $address  = new Apartmentresidentaddress;
            $address->residentid = $request->residentid;
            $address->societyid = $request->societyid;
            $address->buildingid = $request->buildingid;
            $address->societybuildingfloorid = $request->societybuildingfloorid;
            $address->societybuildingapartmentid = $request->societybuildingapartmentid;
            $address->measurementid = $request->measurementid;
            $address->save();


            $subadmins = Subadmin::where('subadminid', $request->subadminid)
            ->join('users', 'users.id', '=', 'subadmins.subadminid')->get();

        $fcm = [];

      

        foreach ($subadmins as $datavals) {
            array_push($fcm, $datavals->fcmtoken);
        }

       

        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata = [
            'registration_ids' => $fcm,

            "data" => ["type" => 'Verification'],
            "android" => [
                "priority" => "high",
                "ttl" => 60 * 60 * 1,
                "android_channel_id" => "pushnotificationapp"

            ],
            "notification" => [
                'title' => 'Verification ✅', 
                'body' => 'You have Verification request from '. $resident->houseaddress.".",
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




            return response()->json(
                [
    
                    "success" => true,
                    "message" => "User Register  Successfully",
                    
    
                ]
            );
        }

        else  {
 

            $localBuildingApartment = Localbuildingapartment::find($request->aid);
            

            if($localBuildingApartment ->occupied==1)

            {
              
                return response()->json(
                    [
        
                        "success" => true,
                        "message" => "Apartment Already Ocuupied by an other User.",
                        
        
                    ],409
                );
               


            }


            $resident = new Resident;
            $resident->residentid = $request->residentid;
            $resident->subadminid = $request->subadminid;
            $resident->country = $request->country??"";
            $resident->state = $request->state??"";
            $resident->city = $request->city??"";
            $resident->houseaddress = $request->houseaddress ?? 'NA';
            $resident->vechileno = $request->vechileno ?? '';
            $resident->residenttype = $request->residenttype;
            $resident->propertytype = $request->propertytype;
            $resident->committeemember = $request->committeemember ?? 0;
            $resident->status = $request->status ?? 0;
            $resident->save();

            
                 if ($resident->residenttype == 'Rental') {
                $owner = new Owner;
                $owner->residentid = $resident->residentid;
                $owner->ownername = $request->ownername ?? "NA";
                $owner->owneraddress = $request->owneraddress ?? "NA";
                $owner->ownermobileno = $request->ownermobileno ?? "NA";
                $owner->save();
            }


            $address  = new Localbuildingapartmentresidentaddress;
            $address->residentid = $request->residentid;
            $address->localbuildingid = $request->localbuildingid;
            $address->fid = $request->fid;
            $address->aid = $request->aid;
            $address->measurementid = $request->measurementid;
            $address->save();


            $subadmins = Subadmin::where('subadminid', $request->subadminid)
            ->join('users', 'users.id', '=', 'subadmins.subadminid')->get();

        $fcm = [];

      

        foreach ($subadmins as $datavals) {
            array_push($fcm, $datavals->fcmtoken);
        }

       

        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata = [
            'registration_ids' => $fcm,

            "data" => ["type" => 'Verification'],
            "android" => [
                "priority" => "high",
                "ttl" => 60 * 60 * 1,
                "android_channel_id" => "pushnotificationapp"

            ],
            "notification" => [
                'title' => 'Verification ✅', 
                'body' => 'You have Verification request from '. $resident->houseaddress.".",
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




            return response()->json(
                [
    
                    "success" => true,
                    "message" => "User Register  Successfully",
                    
    
                ]
            );
    
        }



       



      
    }

    public function viewresidents($id)


    {


        $data = Resident::where('subadminid', $id)->where('status', 1)
            ->join('users', 'users.id', '=', 'residents.residentid',)
            ->get();



        return response()->json(
            [
                "success" => true,
                "residentslist" => $data
            ]
        );
    }

    public function deleteresident($id)

    {

        $resident = Resident::where('residentid', $id)->delete();

        return response()->json([

            "success" => true,
            "data" => $resident,
            "message" => "Resident Deleted successfully"
        ]);
    }


    public function updateresident(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            // 'cnic' => 'required|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required',
            // 'roleid' => 'required',
            // 'rolename' => 'required',
            // 'password' => 'required',
            'image' => 'nullable|image',
            "id" => 'required|exists:users,id',
            "vechileno" => "nullable",
            "residenttype" => "required",
            "propertytype" => "required",
            "committeemember" => "required",
            "ownername" => "nullable",
            "owneraddress" => "nullable",
            "ownermobileno" => "nullable",

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        $user = User::Find($request->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = $request->address;
        $user->mobileno = $request->mobileno;
        // $user->cnic = $request->cnic;
        if ($request->hasFile('image')) {
            $destination = public_path('storage\\') . $user->image;

            if (File::exists($destination)) {

                unlink($destination);
            }
            $image = $request->file('image');
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/'), $imageName);

            $user->image = $imageName;
        }
        $user->update();

        $resident = Resident::where('residentid', $request->id)->first();

        $resident->update([
            'vechileno' => $request->vechileno,
            'residenttype' => $request->residenttype,
            'propertytype' => $request->propertytype,
            'committeemember' => $request->committeemember,
        ]);


        if ($request->residenttype == "Rental") {
            $owner =  Owner::where('residentid', $request->id)->first();
            $owner->ownername = $request->ownername;
            $owner->owneraddress = $request->owneraddress;
            $owner->ownermobileno = $request->ownermobileno;
            $owner->update();
        }


        return response()->json([
            "success" => true,
            "data" => $resident,
            "message" => "Resident  Details Updated Successfully"
        ]);
    }


    public function loginresidentdetails($residentid)

    {
        $data = Resident::where('residentid', $residentid)->with('societydata')->first();



        return response()->json(
            [
                "success" => true,
                "data" => $data
            ]
        );
    }
    public function  loginresidentupdateaddress(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'residentid' => 'required',
            'address' => 'required',
        ]);




        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        $user = User::Find($request->residentid);


        $user->address = $request->address;
        $user->update();


        return response()->json([
            "success" => true,



        ]);
    }



    public function residentlogin(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'cnic' => 'required',
            'password' => 'required',
        ]);



        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        // $user = Auth:: user();

        $user = User::where('cnic', $request->cnic)
            ->join('residents', 'residents.residentid', '=', 'users.id')->first();

        // dd($user);
        // if(Hash::check($request->password, $user->password)) {


        //     return response()->json(['status'=>'true','message'=>'Email is correct']);
        // } else {
        //     return response()->json(['status'=>'false', 'message'=>'password is wrong']);
        // }



        $tk =   $request->user()->createToken('token')->plainTextToken;


        return response()->json([
            "success" => true,
            "data" => $user,
            "Bearer" => $tk


        ]);


        // return response()->json([
        //     "success" => false,
        //     "data" => "Unauthorized"

        // ], 401);

    }


    public function unverifiedhouseresident($subadminid, $status)

    {
        
        $residents = Resident::where('subadminid', $subadminid)->where('status', $status)->where('propertytype', 'house')
        ->join('houseresidentaddresses', 'residents.residentid', '=', 'houseresidentaddresses.residentid')->join('users', 'users.id', '=', 'houseresidentaddresses.residentid')
        ->with('society')
        ->with('phase')
        ->with('block')
        ->with('street')
        ->with('property')
        ->with('measurement')->with('owner')->get();


    


        return response()->json([
            "success" => true,
            "data" => $residents



        ]);
    }

    public function unverifiedapartmentresident($subadminid, $status)

    {
       


        $residents = Resident::where('subadminid', $subadminid)->where('status', $status)->where('propertytype', 'apartment')
            ->join('apartmentresidentaddresses', 'residents.residentid', '=', 'apartmentresidentaddresses.residentid')->join('users', 'users.id', '=', 'apartmentresidentaddresses.residentid')
            ->with('society')
            ->with('building')
            ->with('floor')
            ->with('apartment')
            ->with('measurement')->with('owner')->get();



        return response()->json([
            "success" => true,
            "data" => $residents



        ]);
    }


    public function verifyhouseresident(Request $request)

    {
        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'status' => 'required',
            'pid' => 'nullable',
            'bid' => 'nullable',
            'sid' => 'required',
            'propertyid' => 'required',
            'vechileno' => 'nullable',
            'houseaddress' => 'nullable',
            'measurementid' => 'required'

        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
      
        $property = Property::find($request->propertyid);
            

        if($property->occupied==1)

        {
            return response()->json(
                [
    
                    "success" => true,
                    "message" => "Property Already Ocuupied by an other User.",
                    
    
                ],409
            );


        }
        

        $property = Property::find($request->propertyid);

        $property->occupied=1;
        $property->update();




        $residents = Houseresidentaddress::where('residentid', $request->residentid)->first();

        

        $residents->pid = $request->pid??0;
        $residents->bid = $request->bid??0;
        $residents->sid = $request->sid;
        $residents->propertyid = $request->propertyid;
        $residents->measurementid = $request->measurementid;
        $residents->update();

        $res = Resident::where('residentid', $residents->residentid)->first();
        $res->status = $request->status;
        $res->vechileno = $request->vechileno ?? '';
        $res->houseaddress = $request->houseaddress;
        $res->update();


        $user = User::where('id',  $residents->residentid)->first();
        $user->address =  $request->houseaddress;
        $user->update();





        $fcm=[];

        $residents= Resident::where('residentid',$request->residentid)
        ->join('users','users.id','=','residents.residentid')->get();

        
      

        foreach ($residents as $datavals) {

            array_push($fcm, $datavals['fcmtoken']);

        }

           
        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        "data"=>["type"=>'Verification'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,

        ],
        "notification"=>['title'=>'Verification','body'=>'you are successfully registered ✅',
        
        
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
            "data" => $residents

        ]);
    }


    public function verifyapartmentresident(Request $request)

    {
        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'status' => 'required',
            'buildingid' => 'required',
            'societybuildingfloorid' => 'required',
            'societybuildingapartmentid' => 'required',
            'vechileno' => 'nullable',
            'houseaddress' => 'nullable',
            'measurementid' => 'required'
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $societyBuildingApartment = Societybuildingapartment::find($request->societybuildingapartmentid);
            

        if($societyBuildingApartment ->occupied==1)

        {
            return response()->json(
                [
    
                    "success" => true,
                    "message" => "Property Already Ocuupied by an other User.",
                    
    
                ],409
            );


        }
        

        $societyBuildingApartment = Societybuildingapartment::find($request->societybuildingapartmentid);
        $societyBuildingApartment->occupied=1;
        $societyBuildingApartment->update();



        $residents = Apartmentresidentaddress::where('residentid', $request->residentid)->first();


        $residents->buildingid = $request->buildingid;
        $residents->societybuildingfloorid = $request->societybuildingfloorid;
        $residents->societybuildingapartmentid = $request->societybuildingapartmentid;
        $residents->measurementid = $request->measurementid;
        $residents->update();

        $res = Resident::where('residentid', $residents->residentid)->first();
        $res->status = $request->status;
        $res->houseaddress = $request->houseaddress;
        $res->vechileno = $request->vechileno ?? '';
        $res->update();


        $user = User::where('id',  $residents->residentid)->first();
        $user->address =  $request->houseaddress;
        $user->update();



        $fcm=[];

        $residents= Resident::where('residentid',$request->residentid)
        ->join('users','users.id','=','residents.residentid')->get();

        
      

        foreach ($residents as $datavals) {

            array_push($fcm, $datavals['fcmtoken']);

        }

           
        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        "data"=>["type"=>'Verification'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,

        ],
        "notification"=>['title'=>'Verification','body'=>'you are successfully registered ✅',
        
        
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
            "data" => $residents

        ]);
    }


    public function verifylocalbuildingapartmentresident(Request $request)

    {
        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'status' => 'required',
            'localbuildingid' => 'required',
            'fid' => 'required',
            'aid' => 'required',
            'vechileno' => 'nullable',
            'houseaddress' => 'nullable',
            'measurementid' => 'required'
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        $localBuildingApartment = Localbuildingapartment::find($request->aid);
            

        if($localBuildingApartment ->occupied==1)

        {
            return response()->json(
                [
    
                    "success" => true,
                    "message" => "Apartment Already Ocuupied by an other User.",
                    
    
                ],409
            );


        }
        $localBuildingApartment = Localbuildingapartment::find($request->aid);
        $localBuildingApartment->occupied=1;
        $localBuildingApartment->update();



        $residents = LocalBuildingApartmentresidentaddress::where('residentid', $request->residentid)->first();


        $residents->localbuildingid = $request->localbuildingid;
        $residents->fid = $request->fid;
        $residents->aid = $request->aid;
        $residents->measurementid = $request->measurementid;
        $residents->update();

        $res = Resident::where('residentid', $residents->residentid)->first();
        $res->status = $request->status;
        $res->houseaddress = $request->houseaddress;
        $res->vechileno = $request->vechileno ?? '';
        $res->update();


        $user = User::where('id',  $residents->residentid)->first();
        $user->address =  $request->houseaddress;
        $user->update();


        $fcm=[];

        $residents= Resident::where('residentid',$request->residentid)
        ->join('users','users.id','=','residents.residentid')->get();

        
      

        foreach ($residents as $datavals) {

            array_push($fcm, $datavals['fcmtoken']);

        }

           
        $url = 'https://fcm.googleapis.com/fcm/send';
        $mydata=['registration_ids'=>$fcm,
 
        "data"=>["type"=>'Verification'],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,

        ],
        "notification"=>['title'=>'Verification','body'=>'you are successfully registered ✅',
        
        
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
            "data" => $residents

        ]);
    }

    public function unverifiedlocalbuildingapartmentresident($subadminid, $status)

    {
      


        $residents = Resident::where('subadminid', $subadminid)->where('status', $status)->where('propertytype', 'localbuildingapartment')
            ->join('localbuildingapartmentresidentaddresses', 'residents.residentid', '=', 'localbuildingapartmentresidentaddresses.residentid')->join('users', 'users.id', '=', 'localbuildingapartmentresidentaddresses.residentid')
            ->with('localbuilding')
            ->with('localbuildingfloor')
            ->with('localbuildingapartment')
            ->with('measurement')->with('owner')->get();



        return response()->json([
            "success" => true,
            "data" => $residents



        ]);
    }


    public function unverifiedresidentcount($subadminid)
    {
    
       
    
    
    $count = Resident::where('subadminid',$subadminid)->where('status','0')->count();
        
    
    
    
        return response()->json([
            "success" => true,
            "data" => $count,
    
        ]);
    
    
    }
    

}
