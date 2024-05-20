<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Expense;
use App\Traits\TransformerTrait;
use Illuminate\Http\Request;

class RevenueAndSalesController extends Controller
{
    use TransformerTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sales = Bill::where('status','paid')
        ->when($request->start_date, function($query) use($request){
            $query->where('paid_on','>=',$request->start_date);
        })
        ->when($request->end_date, function($query) use($request){
            $query->where('paid_on','<=',$request->end_date);
        })
        ->when($request->month, function($query) use($request){
            $query->whereMonth('paid_on', $request->month);
        })
        ->when($request->year, function($query) use($request){
            $query->whereYear('paid_on',$request->year);
        })
        ->whereHas('resident')
        ->with('resident.user','resident.residentHouseAddress.society')->latest()->get();
        return TransformerTrait::successResponse(TransformerTrait::transformSales($sales));
    }

    public function ProfityLossReport(Request $request)
    {
        $data['revenue']=Bill::where('status','!=','unpaid')
        ->when($request->start_date, function($query) use($request){
            $query->where('paid_on','>=',$request->start_date);
        })
        ->when($request->end_date, function($query) use($request){
            $query->where('paid_on','<=',$request->end_date);
        })
        ->when($request->month, function($query) use($request){
            $query->whereMonth('paid_on', $request->month);
        })
        ->when($request->year, function($query) use($request){
            $query->whereYear('paid_on',$request->year);
        })
        ->groupBy('paymenttype')
        ->selectRaw('paymenttype, SUM(totalpaidamount) as revenue')
        ->unionAll(Bill::selectRaw('"total" as paymenttype, SUM(totalpaidamount) as revenue'))
        ->get();
        $data['expense']=Expense::
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
            $query->whereYear('date',$request->year);
        })
        ->groupBy('expense_type')
        ->selectRaw('expense_type, SUM(amount) as expense')
        ->unionAll(Expense::selectRaw('"total" as expense_type, SUM(amount) as expense'))
        ->get();
        $revenue_data = [];
        $revenue_data = ['bank_transfer' => 0, 'by_app'=>0,'cash'=>0];
        foreach ($data['revenue'] as $revenue_item) {
            $revenue_data[$revenue_item->paymenttype] = doubleval($revenue_item->revenue);
        }
        $expense_data = [];
        foreach ($data['expense'] as $expense_item) {
            $expense_data[$expense_item->expense_type] = $expense_item->expense;
        }

        $data['revenue'] = $revenue_data;
        $data['expense'] = $expense_data;
        return TransformerTrait::successResponse($data);
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
