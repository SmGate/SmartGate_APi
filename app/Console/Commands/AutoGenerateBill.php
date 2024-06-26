<?php

namespace App\Console\Commands;

use App\Helpers\CustomHelper;
use App\Models\AutoBill;
use App\Models\Bill;
use App\Models\Familymember;
use App\Models\Houseresidentaddress;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoGenerateBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-generate-bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate bill on end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fcm = [];
        $previousPayableAmount = 0.0;
        $previousBalance = 0.0;
        $billType = 'house';
        $totalPaidAmount = 0.0;
        $today = Carbon::now()->format('Y-m-d');
        $auto_bills = AutoBill::where('end_date', $today)->get();
        foreach ($auto_bills as $auto_bill) {
            $residents = Resident::where('subadminid', $auto_bill->subadmin_id)
                ->where('status', 1)
                ->where('propertytype', 'house')
                ->with('user')
                ->get();
            foreach ($residents as $resident) {
                $resident_address = Houseresidentaddress::where('houseresidentaddresses.residentid', $resident->residentid)
                    ->join('residents', 'houseresidentaddresses.residentid', '=', 'residents.residentid')
                    ->with(['property', 'measurement'])
                    ->first();
                array_push($fcm, $resident->user->fcmtoken);
                $noOfusers = Familymember::where('subadminid', $auto_bill->subadmin_id)->where('residentid', $resident_address->residentid)->count();
                //added 1 because the resident it self also
                $noOfAppUsers = $noOfusers + 1;
                $month = Carbon::parse($auto_bill->start_date)->format('F Y');
                // $month = $getmonth;
                foreach ($resident_address->measurement as $measurement) {
                    $measurementid = $measurement->id;
                    $charges = $measurement->charges;
                    $appcharge = $measurement->appcharges;
                    $tax = $measurement->tax;
                    $payableamount = $appcharge + $tax + $charges;
                    $latecharges = $measurement->latecharges;
                    $balance = $payableamount;
                }
                foreach ($resident_address->property as $property) {
                    $propertyid = $property->id;
                }
                $firstDate = Carbon::parse($auto_bill->start_date);
                $existingBill = Bill::where('residentid', $resident_address->residentid)
                    ->where('subadminid', $auto_bill->subadmin_id)->where('billtype', $billType)
                    ->whereMonth('billstartdate', $firstDate->month)
                    ->whereYear('billstartdate', $firstDate->year)
                    ->get();
                foreach ($existingBill as $existingBill) {

                    if ($existingBill != null) {

                        $firstDate = Carbon::parse($auto_bill->start_date);
                        $secondDate = Carbon::parse($existingBill->billstartdate);
                        if ($firstDate->year === $secondDate->year && $firstDate->month === $secondDate->month) {
                            // return response()->json(['message' => 'the bill of this month is already generated !.']);
                            echo 'bill exits';
                            exit();
                        }
                    }
                }

                $previousBill = Bill::where('residentid', $resident_address->residentid)->where('subadminid', $auto_bill->subadmin_id)
                    ->whereIn('status', ['paid', 'unpaid'])->whereIn('isbilllate', [0, 1])->GET();
                if (!empty($previousBill[0]->id)) {
                    foreach ($previousBill as $previousBill) {
                        $previousPayableAmount = $previousBill->payableamount;
                        $previousBalance = $previousBill->balance;
                    }
                } else {
                    $previousPayableAmount = 0;
                    $previousBalance = 0;
                }
                $bill = new Bill();
                $bill->charges = $charges;
                $bill->latecharges = $latecharges;
                $bill->appcharges = $appcharge;
                $bill->tax = $tax;
                $bill->payableamount = $payableamount + $previousPayableAmount;
                $bill->balance = $balance + $previousBalance;
                $bill->subadminid = $auto_bill->subadmin_id;
                $bill->financemanagerid = $auto_bill->financemanager_id;
                $bill->residentid = $resident_address->residentid;
                $bill->propertyid = $propertyid;
                $bill->measurementid = $measurementid;
                $bill->duedate = $auto_bill->due_date;
                $bill->billstartdate = $auto_bill->start_date;
                $bill->billenddate = $auto_bill->end_date;
                $bill->month = $month;
                $bill->status = 'unpaid';
                $bill->noofappusers = $noOfAppUsers;
                $bill->billtype = $billType;
                $bill->paymenttype = 'NA';
                $bill->totalpaidamount = $totalPaidAmount;
                $bill->specific_type = $auto_bill->is_consolidated ? 'consolidated' :'auto';
                $bill->description = 'auto generated bill';
                $bill->save();
            }
            $total=$payableamount + $previousPayableAmount;
            CustomHelper::sendNotification($fcm, $total);
        }
    }
}
