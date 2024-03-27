<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Superadminfinancemanagers;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperAdminFinanceManagerController extends Controller
{


    public function superAdminFinanceMangerRegister(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'required|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required',
            'roleid' => 'required',
            'rolename' => 'required',
            'password' => 'required',
            'image' => 'nullable|image',
            "superadminid" => 'required|exists:users,id',


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
        $user->address = $request->address;
        $user->mobileno = $request->mobileno;
        $user->roleid = $request->roleid;
        $user->rolename = $request->rolename;
        $user->image = $imageName ?? 'images/user.png';
        $user->password = Hash::make($request->password);
        // $user->password = $request->password;


        $user->save();
        $tk =   $user->createToken('token')->plainTextToken;
        $financeManager = new Superadminfinancemanagers;
        $financeManager->financemanagerid = $user->id;
        $financeManager->superadminid = $request->superadminid;
        $financeManager->status = $request->status ?? 'active';

        $financeManager->save();

        return response()->json(
            [
                "token" => $tk,
                "success" => true,
                "message" => "Register Successfully",
                "data" => $user,
            ]
        );
    }
    public function view($id)
    {

        $data = Superadminfinancemanagers::where('subadminid', $id)
            ->join('users', 'users.id', '=', 'financemanagers.financemanagerid')->get();
        return response()->json(
            [
                "success" => true,
                "data" => $data
            ]
        );
    }
    public function delete($id)


    {
        $user = User::where('id', $id)->first();



        if ($user == null) {
            return response()->json([
                "errors" => "Id Not Exist",
                "success" => false
            ], 403);
        }



        $destination = public_path('storage\\') . $user->image;





        if (File::exists(public_path('storage\\') . 'images/user.png')) {
        }
        if (File::exists($destination)) {


            unlink($destination);
        }

        $financeManager =   User::where('id', $id)->delete();

        if ($financeManager == 0) {
            return response()->json([
                "errors" => "Id Not Exist",
                "success" => false
            ], 403);
        }




        return response()->json([
            "success" => true,
            "message" => "Deleted successfully"
        ]);
    }
    public function update(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'address' => 'required',
            'mobileno' => 'required',
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




            if (File::exists(public_path('storage\\') . 'images/user.png')) {
            } else  if (File::exists($destination)) {

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
            "message" => "Record Updated Successfully"
        ]);
    }








    public function allresidentsBill($id)


    {


        $data = Bill::where('bills.residentid', $id)
            ->join('users', 'users.id', '=', 'bills.residentid')
            ->join('residents', 'residents.residentid', '=', 'bills.residentid')
            ->select(
                'bills.*',
                'users.*',
                'residents.vechileno',
                'residents.residenttype',
                'residents.propertytype',
                'residents.committeemember'

            )
            ->get();



        return response()->json(
            [
                "success" => true,
                "residentslist" => $data
            ]
        );
    }






    public function searchResidentsBill($residentid, $q)
    {


        // $data = Bill::where(function ($query) use ($q) {
        //     $query->where('firstname', 'LIKE', '%' . $q . '%')
        //         ->orWhere('lastname', 'LIKE', '%' . $q . '%')
        //         ->orWhere('mobileno', 'LIKE', '%' . $q . '%')
        //         ->orWhere('address', 'LIKE', '%' . $q . '%');
        // })

        //     ->join('users', 'users.id', '=', 'bills.residentid')
        //     ->join('residents', 'residents.residentid', '=', 'bills.residentid')
        //     ->select(
        //         'bills.*',
        //         'users.*',
        //         'residents.vechileno',
        //         'residents.residenttype',
        //         'residents.propertytype',
        //         'residents.committeemember'

        //     )
        //     ->get();


        // return response()->json([
        //     "success" => true,
        //     "residentslist" => $data,
        // ]);
        if (!empty($q)) {
            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )
                ->whereHas('user', function ($query) use ($q) {
                    $query->Where('firstname', 'LIKE', '%' . $q . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $q . '%')
                        ->orWhere('mobileno', 'LIKE', '%' . $q . '%')
                        ->orWhere('cnic', 'LIKE', '%' . $q . '%')
                        ->orWhere('address', 'LIKE', '%' . $q . '%');
                })
                ->get();






            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        }
    }



    public function filterBills()
    {

        $status = request()->status ?? null;
        $paymenttype = request()->paymenttype ?? null;
        $startdate = request()->startdate ?? null;
        $enddate = request()->enddate ?? null;
        $residentid = request()->residentid ?? null;



        $isValidate = Validator::make(request()->all(), [

            'startdate' => 'date|nullable',
            'enddate' => 'date|nullable',
            'paymenttype' => 'nullable',
            'status' => 'nullable',
            'residentid' => 'required|exists:users,id',



        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $startDatecurrentYear = date('Y', strtotime($startdate));
        $startDatecurrentMonth = date('m', strtotime($startdate));
        $endDatecurrentYear = date('Y', strtotime($enddate));
        $endDatecurrentMonth = date('m', strtotime($enddate));
        // echo($status);
        // echo($paymenttype);
        // echo($startdate);
        // echo($enddate);

        if (!empty($status) && !empty($paymenttype) && !empty($startdate) && !empty($enddate)) {


            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )

                ->where('bills.status', $status)->where('paymenttype', $paymenttype)
                ->whereMonth('billstartdate', $startDatecurrentMonth)->whereYear(
                    'billstartdate',
                    $startDatecurrentYear
                )->whereMonth('billenddate', $endDatecurrentMonth)->whereYear(
                    'billenddate',
                    $endDatecurrentYear
                )

                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        }

        if (!empty($status) && !empty($paymenttype)) {


            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )->where('paymenttype', $paymenttype)
                ->where('bills.status', $status)

                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        } else if (!empty($status) && !empty($startdate) && !empty($enddate)) {


            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )
                ->whereMonth('billstartdate', $startDatecurrentMonth)->whereYear(
                    'billstartdate',
                    $startDatecurrentYear
                )->whereMonth('billenddate', $endDatecurrentMonth)->whereYear(
                    'billenddate',
                    $endDatecurrentYear
                )

                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        } else if (!empty($paymenttype) && !empty($startdate) && !empty($enddate)) {


            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )->where('paymenttype', $paymenttype)
                ->whereMonth('billstartdate', $startDatecurrentMonth)->whereYear(
                    'billstartdate',
                    $startDatecurrentYear
                )->whereMonth('billenddate', $endDatecurrentMonth)->whereYear(
                    'billenddate',
                    $endDatecurrentYear
                )

                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        } else if (!empty($status)) {

            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )
                ->where('bills.status', $status)


                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        } else if (!empty($paymenttype)) {
            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )
                ->where('paymenttype', $paymenttype)

                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        } else if (!empty($startdate) && !empty($enddate)) {



            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )
                ->whereMonth('billstartdate', $startDatecurrentMonth)->whereYear(
                    'billstartdate',
                    $startDatecurrentYear
                )->whereMonth('billenddate', $endDatecurrentMonth)->whereYear(
                    'billenddate',
                    $endDatecurrentYear
                )

                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        } else {


            $bills = Bill::where('bills.residentid', $residentid)
                ->join('users', 'users.id', '=', 'bills.residentid')
                ->join('residents', 'residents.residentid', '=', 'bills.residentid')
                ->select(
                    'bills.*',
                    'users.*',
                    'residents.vechileno',
                    'residents.residenttype',
                    'residents.propertytype',
                    'residents.committeemember'

                )
                ->get();

            return response()->json([
                "success" => true,
                "residentslist" => $bills,
            ]);
        }
    }

    public function currentMonthBills($subadminid)
    {





        $currentDate = date('Y-m-d');
        $currentYear = date('Y', strtotime($currentDate));

        $currentMonth = date('m', strtotime($currentDate));


        $bills = Bill::where('bills.subadminid', $subadminid)
            ->join('users', 'users.id', '=', 'bills.residentid')
            ->join('residents', 'residents.residentid', '=', 'bills.residentid')
            ->select(
                'bills.*',
                'users.*',
                'residents.vechileno',
                'residents.residenttype',
                'residents.propertytype',
                'residents.committeemember'

            )

            ->whereMonth('billenddate', $currentMonth)
            ->whereYear(
                'billenddate',
                $currentYear
            )
            ->get();






        return response()->json([
            "success" => true,
            "residentslist" => $bills,
        ]);
    }

}