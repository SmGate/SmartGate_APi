<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Traits\TransformerTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AutoBill;

class AutoBillController extends Controller
{
    use TransformerTrait;
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
            'due_date' => 'required|date|after:start_date|before:end_date',
            'end_date' => 'required|date|after:due_date',
        ]);
        if ($isValidate->fails()) {
        return TransformerTrait::errorResponse($isValidate->errors()->all(),null,403);
        }
        $previous_bill= AutoBill::where('start_date',$request->start_date)
        ->where('end_date',$request->end_date)->first();
        if(!empty($previous_bill)){
        return TransformerTrait::errorResponse(['A bill with these start and end date already exists.'],'A bill with these start and end date already exists.',403);
        }
        $auto_bill=AutoBill::create($request->all());  
        $residents = Resident::where('subadminid', $request->subadmin_id)
                ->where('status', 1)
                ->where('propertytype', 'house')
                ->with('user')
                ->get();
        CustomHelper::generateBill($residents,$auto_bill->subadmin_id,$auto_bill->financemanager_id,$request->start_date,$request->end_date,$request->due_date);      
        if($auto_bill){
          return  TransformerTrait::successResponse($auto_bill,'Auto Bill Generated Successfully');
        }
        return TransformerTrait::errorResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
