<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use Illuminate\Support\Facades\Log;


class MonthlyBillUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthlyBillUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update payable amount, balance, isbilllate status = 1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bills = Bill::all();

        

        

        foreach ($bills as $bills) {



            $currentDate =  strtotime(date('Y-m-d'));
            $dueDate=strtotime($bills->duedate);
            $lateCharges=$bills->latecharges;
            $payableAmount=$bills->payableamount;

           
         

            
                if ($currentDate > $dueDate && $bills->isbilllate==0) {


            
              $latePayableAmount=$payableAmount+$lateCharges;
              $bills->isbilllate=1;

              $bills->payableamount =$latePayableAmount;
              $bills->balance=$latePayableAmount;
              
             $bills->update();
          
                }

        }

    }
}
