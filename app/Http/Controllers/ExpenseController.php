<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Traits\TransformerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    use TransformerTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = Expense::
        when($request->start_date, function($query) use($request){
            $query->where('date','>=',$request->start_date);
        })
        ->when($request->end_date, function($query) use($request){
            $query->where('date','<=',$request->end_date);
        })
        ->when($request->month, function($query) use($request){
            $query->whereMonth('date', $request->month);
        })
        ->when($request->year, function($query) use($request){
            $query->whereYear('date', $request->year);
        })
        ->latest()->get();
        $data['expenses']=$expenses;
        $data['total expenses']=$expenses->sum('amount');
        return TransformerTrait::successResponse($data);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'subadmin_id' => 'required|exists:users,id',
            'financemanager_id' => 'required|exists:financemanagers,financemanagerid',
            'amount' => 'required',
            'description' => 'required',
            'expense_type' => 'required',
            'payment_method' => 'required',
            'date' => 'required|date'
        ]);
        if ($isValidate->fails()) {
            return TransformerTrait::errorResponse($isValidate->errors()->all(), null , 403);
        }
        $image_name='';
        if ($request->file('reciept')) {
            $document = $request->file('reciept');
            $image_name = time() . "." . $document->extension();
            $document->move(public_path('/storage/expense/'), $image_name);
        }
        $request->merge(['document' => 'expenses/'.$image_name]);
        $expense=Expense::create($request->all());
        if ($expense) {
            return TransformerTrait::successResponse($expense,'Expense Added Successfuly.');
        }
        return TransformerTrait::errorResponse();
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
