<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Gatekeeper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class GateKeeperController extends Controller
{
    public function registergatekeeper(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'required|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required|unique:users',
            'gateno' => 'nullable',
            'roleid' => 'required',
            'rolename' => 'required',
            'password' => 'required',
            'image' => 'required|image',
            "subadminid" => 'required|exists:users,id',
            "societyid" => 'required|exists:societies,id',
            
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->cnic = $request->cnic;
        $user->address = $request->address;
        $user->mobileno = $request->mobileno;
        $user->roleid = $request->roleid;
        $user->rolename = $request->rolename;
        $user->password = Hash::make($request->password);
        // $user->password = $request->password;
        $image = $request->file('image');
        $imageName = time() . "." . $image->extension();
        $image->move(public_path('/storage/'), $imageName);
        $user->image = $imageName;
        
        $user->save();
        $tk =   $user->createToken('token')->plainTextToken;
        $gatekeeper = new Gatekeeper;
        $gatekeeper->gatekeeperid = $user->id;
        $gatekeeper->subadminid = $request->subadminid;
        $gatekeeper->societyid = $request->societyid;
        
        $gatekeeper->gateno = $request->gateno??'';
        $gatekeeper->save();
        return response()->json(
            [
                "token" => $tk,
                "success" => true,
                "message" => "GateKeeper Register to our system Successfully",
                "data" => $user,
            ]
        );
    }
    public function viewgatekeepers($id)
    {
        //$data = Gatekeeper::where('subadminid', $id)->get();
        $data = Gatekeeper::where('subadminid', $id)
            ->join('users', 'users.id', '=', 'gatekeepers.gatekeeperid')->get();
        return response()->json(
            [
                "success" => true,
                "gatekeeperlist" => $data
            ]
        );
    }
    public function deletegatekeeper($id)
    {
        $gatekeeper =   User::where('id', $id)->delete();
        if ($gatekeeper == 0) {
            return response()->json([
                "errors" => 'Gate Keeper Id Not Exist',
                "success" => false
            ], 403);
        }
        return response()->json([
            "success" => true,
            "data" => $gatekeeper,
            "message" => "GateKeeper Deleted successfully"
        ]);
    }
    public function updategatekeeper(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'address' => 'required',
            'mobileno' => 'required',
            'gateno' => 'required',
            'image' => 'nullable|image',
            "id" => 'required|exists:users,id',
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
        $user->password = Hash::make($request->password);
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
        $gatekeeper = Gatekeeper::where('gatekeeperid', $request->id)->first();
        $gatekeeper->update([
            'gateno' => $request->gateno,
        ]);
        return response()->json([
            "success" => true,
            "data" => [$user, $gatekeeper],
            "message" => "Gate Keeper  Details Updated Successfully"
        ]);
    }
}