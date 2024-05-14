<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AutoBill;

class AutoBillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd(342);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'subadmin_id' => 'required|exists:users,id',
            'financemanager_id' => 'required|exists:financemanagers,financemanagerid',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date | before:end_date',
            'end_date' => 'required|date|after:billstartdate',
            // 'start_month' => 'nullable',
            // 'end_month' => 'nullable',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $auto_bill=AutoBill::create($request->all());        
        if($auto_bill){
            return response()->json([
                "message" => 'Auto Bill Generated Successfully',
                "success" => true
            ], 200); 
        }
        return response()->json([
            "message" => 'something went wrong',
            "success" => false
        ], 500); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
