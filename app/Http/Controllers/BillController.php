<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Bill;
use Illuminate\Support\Facades\Validator;
use App\Models\Familymember;
use App\Models\Houseresidentaddress;
use App\Models\Apartmentresidentaddress;


class BillController extends Controller
{




    public function generatehousebill(Request $request)
    {



        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'financemanagerid' => 'required|exists:financemanagers,financemanagerid',
            'duedate' => 'required|date|after:billenddate',
            'billstartdate' => 'required|date',
            'billenddate' => 'required|date|after:billstartdate',
            'status' => 'required|in:paid,unpaid,partiallypaid',
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $noOfAppUsers = 1;
        $charges = 0.0;
        $latecharges = 0.0;
        $tax = 0.0;
        $balance = 0.0;
        $payableamount = 0.0;
        $subadminid = 0;
        $residnentid = 0;
        $propertyid = 0;
        $measurementid = 0;
        $duedate = null;
        $billstartdate = null;
        $billenddate = null;
        $getmonth = null;
        $month = null;
        $status = null;
        $previousPayableAmount=0.0;
        $previousBalance=0.0;
        $billType='house';
        $paymentType='NA';
        $totalPaidAmount=0.0;
        $subadminid = $request->subadminid;
        $financemanagerid = $request->financemanagerid;

        $status = $request->status;
        $duedate = $request->duedate;
        $billstartdate = $request->billstartdate;
        $billenddate = $request->billenddate;

        $residents = Resident::where('subadminid', $subadminid)
        ->where('status', 1)
        ->where('propertytype', 'house')
        ->join('users', 'users.id', '=', 'residents.residentid')
        ->get();


        foreach ($residents as $residents) {

            // fetching resident details from db
            
            $residnentsLi = Houseresidentaddress::where('houseresidentaddresses.residentid', $residents->residentid )
            ->join('residents', 'houseresidentaddresses.residentid', '=', 'residents.residentid')
            ->with('property')
            ->with('measurement')
            ->first();


            $measurement =  $residnentsLi ->measurement;
            $property =  $residnentsLi ->property;
            $residnentid= $residnentsLi->residentid;

            
            $noOfusers = Familymember::where('subadminid', $subadminid)->where('residentid', $residnentid)->count();
            $residentItSelf = 1;
            $noOfAppUsers = $noOfusers + $residentItSelf;

         
            $getmonth = Carbon::parse($billstartdate)->format('F Y');
            $month = $getmonth;


            foreach ($measurement as $measurement) 
            {
                $measurementid = $measurement->id;
                $charges = $measurement->charges;
                $appcharge = $measurement->appcharges;
                $tax = $measurement->tax;
                $payableamount = $appcharge + $tax + $charges;
                $latecharges = $measurement->latecharges;
                $balance = $payableamount;
            }

            foreach ($property as $property) 
            {
                $propertyid = $property->id;
            }



        $firstDate = Carbon::parse($billstartdate);
            $existingBill = Bill::where('residentid', $residnentid)
            ->where('subadminid',$subadminid)->where('billtype',$billType)
                ->whereMonth('billstartdate', $firstDate->month)
                ->whereYear('billstartdate', $firstDate->year)
                ->get();


            foreach ($existingBill as $existingBill) {

                if ($existingBill != null) {

                    $firstDate = Carbon::parse($billstartdate);
                    $secondDate = Carbon::parse($existingBill->billstartdate);


                    if ($firstDate->year === $secondDate->year && $firstDate->month === $secondDate->month) {


                        return response()->json(['message' => 'the bill of this month is already generated !.']);

                    }


                }


            }
            
            $previousBill = Bill::where('residentid', $residnentid)->where('subadminid',$subadminid)
                ->whereIn('status', ['paid', 'unpaid'])->whereIn('isbilllate', [0, 1])->GET();
            if(!empty($previousBill[0]->id))
            {
                foreach ($previousBill as $previousBill)
                {
                    $previousPayableAmount=$previousBill->payableamount;
                    $previousBalance=$previousBill->balance;
                }
            }
            else
            {
                $previousPayableAmount=0;
                $previousBalance=0;
            }

            $bill = new Bill();

            $bill->insert(
                [

                    [
                        'charges' => $charges,
                        'latecharges' => $latecharges,
                        'appcharges' => $appcharge,
                        'tax' => $tax,
                        'payableamount' => $payableamount + $previousPayableAmount,
                        'balance' => $balance+ $previousBalance ,
                        'subadminid' => $subadminid,
                        'financemanagerid' =>$financemanagerid,
                        'residentid' => $residnentid,
                        'propertyid' => $propertyid,
                        'measurementid' => $measurementid,
                        'duedate' => $duedate,
                        'billstartdate' => $billstartdate,
                        'billenddate' => $billenddate,
                        'month' => $month,
                        'status' => $status,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'noofappusers' => $noOfAppUsers,
                        'billtype'=>$billType,
                        'paymenttype'=>$paymentType,
                        'totalpaidamount'=>$totalPaidAmount
                    ],

                ]
            );

        }





        


             return response()->json([
            "success" => true,
            "message" => 'Bill generated Successfully'
        ]);





    }


    public function generatedhousebill($subadminid)
    {



        $currentDate = date('Y-m-d');
        $currentYear = date('Y', strtotime($currentDate));
        $billType='house';
        $currentMonth = date('m', strtotime($currentDate));
        $bills = Bill::where('subadminid', $subadminid)->where('billtype', $billType)
         ->whereMonth('billenddate', $currentMonth)->whereYear(
            'billenddate',
            $currentYear
        )
     
            ->with('user')
            ->with('resident')
            ->with('measurement')
            ->with('property')

            ->get();





        return response()->json([
            "success" => true,
            "data" => $bills,
        ]);
    }


    public function generatesocietyapartmentbill(Request $request)
    {



        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'financemanagerid' => 'required|exists:financemanagers,financemanagerid',
            'duedate' => 'required|date|after:billenddate',
            'billstartdate' => 'required|date',
            'billenddate' => 'required|date|after:billstartdate',
            'status' => 'required|in:paid,unpaid,partiallypaid',
        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $noOfAppUsers = 1;
        $charges = 0.0;
        $latecharges = 0.0;
        $tax = 0.0;
        $balance = 0.0;
        $payableamount = 0.0;
        $subadminid = 0;
        $residnentid = 0;
        $propertyid = 0;
        $measurementid = 0;
        $duedate = null;
        $billstartdate = null;
        $billenddate = null;
        $getmonth = null;
        $month = null;
        $status = null;
        $previousPayableAmount=0.0;
        $previousBalance=0.0;
        $totalPaidAmount=0.0;

        $subadminid = $request->subadminid;
        $financemanagerid = $request->financemanagerid;
        $status = $request->status;
        $duedate = $request->duedate;
        $billstartdate = $request->billstartdate;
        $billenddate = $request->billenddate;
        $billType='societybuildingapartment';
        $paymentType='NA';

        $residents = Resident::where('subadminid', $subadminid)
        ->where('status', 1)
        ->where('propertytype', 'apartment')
        ->join('users', 'users.id', '=', 'residents.residentid')
        ->get();


        foreach ($residents as $residents) {

            // fetching resident details from db
            
            $residnentsLi = Apartmentresidentaddress::where('apartmentresidentaddresses.residentid', $residents->residentid )
            ->join('residents', 'apartmentresidentaddresses.residentid', '=', 'residents.residentid')
            ->with('societybuildingapartments')
            ->with('measurement')
            ->first();


            $measurement =  $residnentsLi ->measurement;
            $property =  $residnentsLi ->societybuildingapartments;
            $residnentid= $residnentsLi->residentid;

            
            $noOfusers = Familymember::where('subadminid', $subadminid)->where('residentid', $residnentid)->count();
            $residentItSelf = 1;
            $noOfAppUsers = $noOfusers + $residentItSelf;

         
            $getmonth = Carbon::parse($billstartdate)->format('F Y');
            $month = $getmonth;


            foreach ($measurement as $measurement) 
            {
                $measurementid = $measurement->id;
                $charges = $measurement->charges;
                $appcharge = $measurement->appcharges;
                $tax = $measurement->tax;
                $payableamount = $appcharge + $tax + $charges;
                $latecharges = $measurement->latecharges;
                $balance = $payableamount;
            }

            foreach ($property as $property) 
            {
                $propertyid = $property->id;
            }



        $firstDate = Carbon::parse($billstartdate);
            $existingBill = Bill::where('residentid', $residnentid)
            ->where('subadminid',$subadminid)->where('billtype',$billType)
                ->whereMonth('billstartdate', $firstDate->month)
                ->whereYear('billstartdate', $firstDate->year)
                ->get();


            foreach ($existingBill as $existingBill) {

                if ($existingBill != null) {

                    $firstDate = Carbon::parse($billstartdate);
                    $secondDate = Carbon::parse($existingBill->billstartdate);


                    if ($firstDate->year === $secondDate->year && $firstDate->month === $secondDate->month) {


                        return response()->json(['message' => 'the bill of this month is already generated !.']);

                    }


                }


            }
            
            $previousBill = Bill::where('residentid', $residnentid)->where('subadminid',$subadminid)
                ->whereIn('status', ['paid','unpaid'])->whereIn('isbilllate', [0, 1])->GET();
            if(!empty($previousBill[0]->id))
            {
                foreach ($previousBill as $previousBill)
                {
                    $previousPayableAmount=$previousBill->payableamount;
                    $previousBalance=$previousBill->balance;
                }
            }
            else
            {
                $previousPayableAmount=0;
                $previousBalance=0;
            }

            $bill = new Bill();

            $bill->insert(
                [

                    [
                        'charges' => $charges,
                        'latecharges' => $latecharges,
                        'appcharges' => $appcharge,
                        'tax' => $tax,
                        'payableamount' => $payableamount + $previousPayableAmount,
                        'balance' => $balance+ $previousBalance ,
                        'subadminid' => $subadminid,
                        'financemanagerid' =>$financemanagerid,
                        'residentid' => $residnentid,
                        'propertyid' => $propertyid,
                        'measurementid' => $measurementid,
                        'duedate' => $duedate,
                        'billstartdate' => $billstartdate,
                        'billenddate' => $billenddate,
                        'month' => $month,
                        'status' => $status,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'noofappusers' => $noOfAppUsers,
                        'billtype'=>$billType,
                        'paymenttype'=>$paymentType,
                        'totalpaidamount'=>$totalPaidAmount

                    ],

                ]
            );

        }





        


             return response()->json([
            "success" => true,
            "message" => 'Bill generated Successfully'
        ]);





    }

    public function generatedsocietyapartmentbill($subadminid)
    {

        // $bills =  Bill::where('subadminid', $subadminid) ->join('users', 'users.id', '=', 'bills.residentid')
        // ->select(

        //     'users.rolename',
        //     'bills.*',
        //     'users.firstname', 
        //     'users.lastname',
        //      'users.image',
        //     'users.cnic',
        //     'users.roleid',



        //     )->get();

        $billType='societybuildingapartment';

        $currentDate = date('Y-m-d');
        $currentYear = date('Y', strtotime($currentDate));
        $currentMonth = date('m', strtotime($currentDate));
        $bills = Bill::where('subadminid', $subadminid) ->where('billtype', $billType)->whereMonth('billenddate', $currentMonth)->whereYear(
            'billenddate',
            $currentYear
        )
       
            ->with('user')
            ->with('resident')
            ->with('measurement')
            ->with('societybuildingapartments')

            ->get();





        return response()->json([
            "success" => true,
            "data" => $bills,
        ]);
    }


    public function monthlybills($residnentid )
    {



        $currentDate = date('Y-m-d');
        $currentYear = date('Y', strtotime($currentDate));
        $currentMonth = date('m', strtotime($currentDate));

        $bills = Bill::where('residentid', $residnentid)
            ->whereMonth('billenddate', $currentMonth)->whereYear(
                'billenddate',
                $currentYear
            )
            ->where('status', 'unpaid')->get()->first();





        return response()->json([
            "success" => true,
            "data" => $bills,
        ]);
    }





    


    public function paybill(Request $request)
    {


        $isValidate = Validator::make($request->all(), [

            'id' => 'required|exists:bills,id',
            'paymenttype' => 'required',
            'totalpaidamount' => 'required',
        ]);
    
    
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
    
        $id=$request->id;
        $paymentType=$request->paymenttype;
        $totalPaidAmount=$request->totalpaidamount;
        $billPaidStatus='paid';
        $balance=0.0;
        $amount=0.0;
        


       
        

        $bill=Bill::find($id);



        if($bill->payableamount==$totalPaidAmount&&$bill->balance==0.0)


        {

            return response()->json([
                "success" => true,
                "message"=>"Bill Already Paid"
            ]);
        }
        
       
        $payableAmount=$bill->payableamount;
        $balance=$bill->balance;

        $paidAmount=$totalPaidAmount;


        $amount=$balance-$totalPaidAmount;

        if($amount<0)
        {$amount=0.0;}
    
        // $bill->payableamount=$payableAmount;
        $bill->balance=$amount;
        $bill->totalpaidamount=$bill->totalpaidamount+$paidAmount;
        $bill->status=$billPaidStatus;
        $bill->paymenttype=$paymentType;
        $bill->update();




        

        return response()->json([
            "success" => true,
            "message"=>"Bill Paid Successfully"
        ]);


    }








}