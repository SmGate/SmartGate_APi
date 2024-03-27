<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Individualbill;
use App\Models\Individualbillitem;
use Illuminate\Support\Facades\Validator;


class IndividualBillController extends Controller
{
    public function createIndividualBill(Request $request)
    {
        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'financemanagerid' => 'required|exists:users,id',
            'residentid' => 'required|exists:residents,residentid',
            //'propertyid' => 'required|exists:properties,id',
            'billstartdate' => 'required|date',
            'billenddate' => 'required|date',
            'duedate' => 'required|date',
            'billtype' => 'required|string',
            'paymenttype' => 'required|string',
            'status' => 'required|in:paid,unpaid,partiallypaid',
            //'charges' => 'required|numeric',

            'latecharges' => 'required|numeric',
            'tax' => 'required|numeric',
            //'balance' => 'required|numeric',
            //'payableamount' => 'required|numeric',
            //'totalpaidamount' => 'required|numeric',
            'isbilllate' => 'required|numeric',

            // Validation rules for individualbillitems table
            'bill_items' => 'required|array',
            'bill_items.*.billname' => 'required|string',
            'bill_items.*.billprice' => 'required|numeric',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        // Create individualbills record






        $charges = 0;
        foreach ($request['bill_items'] as $item) {
            $charges += $item['billprice'];
        }

        $individualBill = new Individualbill;
        $individualBill->subadminid = $request->subadminid;
        $individualBill->financemanagerid = $request->financemanagerid;
        $individualBill->residentid = $request->residentid;
        //$individualBill->propertyid = $request->propertyid;
        $individualBill->billstartdate = $request->billstartdate;
        $individualBill->billenddate = $request->billenddate;
        $individualBill->duedate = $request->duedate;

        $individualBill->billtype = $request->billtype;
        $individualBill->paymenttype = $request->paymenttype ?? 'NA';
        $individualBill->status = $request->status;

        $individualBill->charges = $charges;
        $individualBill->latecharges = $request->latecharges;

        $individualBill->tax = $request->tax;

        $individualBill->payableamount =  $request->tax + $charges;

        $individualBill->balance = $individualBill->payableamount;

        $individualBill->totalpaidamount = $individualBill->payableamount;
        $individualBill->isbilllate = $request->isbilllate;

        $individualBill->save();


        // Create individualbillitems records

        foreach ($request['bill_items'] as $item) {
            Individualbillitem::create([
                'individualbillid' => $individualBill->id,
                'billname' => $item['billname'],
                'billprice' => $item['billprice'],
            ]);
        }

        return response()->json([
            'message' => 'Bill created successfully',
            'data' => $individualBill
        ]);
    }


    public function getIndividualBillsForFinance($subadminid)
    {
        $individualBills = Individualbill::where('subadminid', $subadminid)->with('billItems')->get();

        return response()->json([
            'message' => 'Individual bills fetched successfully',
            'individualBills' => $individualBills
        ]);
    }
    public function getIndividualBillsByResident($residentid)
    {
        $individualBills = Individualbill::where('residentid', $residentid)->with('billItems')->get();

        return response()->json([
            'message' => 'Individual bills fetched successfully',
            'individualBills' => $individualBills
        ]);
    }

    public function filterIndividualBills()
    {

        $status = request()->status ?? null;
        $paymenttype = request()->paymenttype ?? null;
        $startdate = request()->startdate ?? null;
        $enddate = request()->enddate ?? null;
        $subadminid = request()->subadminid ?? null;



        $isValidate = Validator::make(request()->all(), [

            'startdate' => 'date|nullable',
            'enddate' => 'date|nullable',
            'paymenttype' => 'nullable',
            'status' => 'nullable',
            'subadminid' => 'required|exists:subadmins,subadminid',



        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }





        //FUZAIL WORK YEAR MONTH DATE BETWEEN 
        if (!empty($status) && !empty($paymenttype) && !empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills = Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')
                ->where('individualbills.status', $status)
                ->where('paymenttype', $paymenttype)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }



        if (!empty($status) && !empty($paymenttype)) {


            $bills = Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')
                ->where('paymenttype', $paymenttype)
                ->where('individualbills.status', $status)

                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }



        //FUZAIL YEAR MONTH DATE WORK 
        else if (!empty($status) && !empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills = Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')
                ->where('individualbills.status', $status)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }



        //FUZAIL ......... BETWEEN.....
        else if (!empty($paymenttype) && !empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills = Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')

                ->where('paymenttype', $paymenttype)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        } else if (!empty($status)) {




            $bills = Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')

                ->where('individualbills.status', $status)


                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        } else if (!empty($paymenttype)) {
            $bills = Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')


                ->where('individualbills.paymenttype', $paymenttype)

                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }

        //FUZAIL WORK YEAR MONTH AND DATE IN BETWEEN QUERY WORK

        elseif (!empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills =  Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')

                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        } else {


            $bills =  Individualbill::where('individualbills.subadminid', $subadminid)
                ->with('billItems')



                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }
    }


    public function filterIndividualBillsByResident()
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





        //FUZAIL WORK YEAR MONTH DATE BETWEEN 
        if (!empty($status) && !empty($paymenttype) && !empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills = Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')
                ->where('individualbills.status', $status)
                ->where('paymenttype', $paymenttype)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }



        if (!empty($status) && !empty($paymenttype)) {


            $bills = Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')
                ->where('paymenttype', $paymenttype)
                ->where('individualbills.status', $status)

                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }



        //FUZAIL YEAR MONTH DATE WORK 
        else if (!empty($status) && !empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills = Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')
                ->where('individualbills.status', $status)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }



        //FUZAIL ......... BETWEEN.....
        else if (!empty($paymenttype) && !empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills = Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')

                ->where('paymenttype', $paymenttype)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        } else if (!empty($status)) {




            $bills = Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')

                ->where('individualbills.status', $status)


                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        } else if (!empty($paymenttype)) {
            $bills = Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')


                ->where('individualbills.paymenttype', $paymenttype)

                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }

        //FUZAIL WORK YEAR MONTH AND DATE IN BETWEEN QUERY WORK

        elseif (!empty($startdate) && !empty($enddate)) {
            $startDate = date('Y-m-d', strtotime($startdate));
            $endDate = date('Y-m-d', strtotime($enddate));

            $bills =  Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')

                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('billstartdate', [$startDate, $endDate])
                        ->orWhereBetween('billenddate', [$startDate, $endDate]);
                })
                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        } else {


            $bills =  Individualbill::where('individualbills.residentid', $residentid)
                ->with('billItems')



                ->get();

            return response()->json([
                'message' => 'Individual bills fetched successfully',
                'individualBills' => $bills
            ]);
        }
    }
}