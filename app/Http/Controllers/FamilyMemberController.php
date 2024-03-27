<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Familymember;

use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    public function addfamilymember(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'nullable|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required|unique:users',
            'residentid' => 'required|exists:residents,residentid',
            'subadminid' => 'required|exists:residents,subadminid',
            'password' => 'required',
            'image' => 'nullable|image',   
             ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $user = new User;
        $image = $request->file('image');

        if ($image != null) {
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/'), $imageName);
            $user->image = $imageName;
        }
        
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->cnic = $request->cnic;
        $user->address= $request->address;
        $user->mobileno= $request->mobileno;
        $user->roleid = 5;
        $user->rolename = 'familymember';
        $user->image=$imageName??'images/user.png';
        $user->password = Hash::make($request->password);
        $user->save();


        $tk =   $user->createToken('token')->plainTextToken;
        $familymember =new Familymember;
        $familymember ->familymemberid=$user->id;
        $familymember ->residentid=$request->residentid;
        $familymember ->subadminid=$request->subadminid;
        $familymember->save();

        return response()->json(
            [
                "token" => $tk,
                "success" => true,
                "message" => "Family Member Add Successfully",
                "data" => $user,
            ]
        );
    }


    public function viewfamilymember($subadminid,$residentid)


    {


        $data = Familymember::where('subadminid', $subadminid)->where('residentid', $residentid)
            ->join('users', 'users.id', '=', 'familymembers.familymemberid')->get();



        return response()->json(
            [
                "success" => true,
                "data" => $data
            ]
        );
    }



}
