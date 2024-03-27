<?php

namespace App\Http\Controllers;

use App\Models\Subadmin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubAdminController extends Controller
{

    public function   registersubadmin(Request $request)

    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'required|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required|unique:users',
            'roleid' => 'required',
            'rolename' => 'required',
            'password' => 'required',
            'image' => 'nullable|image',
            'superadminid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id'

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }  $user = new User;
        $image = $request->file('image');

          if($image!=null)
        {
        $imageName= time().".".$image->extension();
        $image->move(public_path('/storage/'), $imageName);
        $user->image=$imageName;}




        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->cnic = $request->cnic;
        $user->address = $request->address;
        $user->mobileno = $request->mobileno;
        $user->roleid = $request->roleid;
        $user->rolename = $request->rolename;
        $user->password = Hash::make($request->password);
        $user->image = $imageName??'images/user.png';
        $user->save();
        $tk =   $user->createToken('token')->plainTextToken;
        $subadmin = new Subadmin;
        $subadmin->superadminid = $request->superadminid;
        $subadmin->societyid = $request->societyid;
        $subadmin->subadminid = $user->id;

        $subadmin->save();

        return response()->json(
            [
                "token" => $tk,
                "success" => true,
                "message" => "Sub Admin Assign to Society Successfully",
                "data" => $user,
            ]
        );
    }

    public function deletesubadmin($id)

    {

        $userid = User::where('id', $id)->delete();

        return response()->json([

            "success" => true,
            "data" => $userid,
            "message" => "Sub Admin Deleted successfully"
        ]);
    }


    public  function updatesubadmin(Request $request)


    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'mobileno' => 'nullable',
            'address' => 'nullable',
            // 'password' => 'nullable',
            'image' => 'nullable|image',
            'id' => 'required|exists:users,id'

        ]);

        if ($isValidate->fails()) {
            return response()->json([

                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        $user = User::find($request->id);

        $user->firstname = $request->firstname ?? "";
        $user->lastname = $request->lastname ?? "";
        $user->mobileno = $request->mobileno ?? "";
        $user->address = $request->address ?? "";
        // $user->password = Hash::make($request->password);
        // $user->password = $request->password;

        if ($request->hasFile('image')) {
            $destination = public_path('storage\\') . $user->image;
        // dd($destination);



            if (File::exists(public_path('storage\\') . 'images/user.png')) {
                print("delete");

            }
          else  if (File::exists($destination)) {
                print("delete");
                unlink($destination);
            }

            $image = $request->file('image');
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/'), $imageName);

            $user->image = $imageName;
        }





        $user->update();


        return response()->json([
            "success" => true,
            "data" => $user,
            "message" => "Sub Admin Details Updated Successfully"
        ]);
    }

    public  function viewsubadmin($id)


    {

       

        $data = Subadmin::where('societyid', $id)->join('users', 'users.id', '=', 'subadmins.subadminid')->get();


    

        return response()->json(
            [
                "success" => true,
                "data" => $data


            ]

        );
    }




    
}
