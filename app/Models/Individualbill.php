<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individualbill extends Model
{
    use HasFactory;
    protected $table = 'individualbills';
    protected $fillable = [
        'subadminid',
        'financemanagerid',
        'residentid',
        //'propertyid',
        'billstartdate',
        'billenddate',
        'duedate',
        'billtype',
        'paymenttype',
        'status',
        'charges',
        'latecharges',
        'tax',
        'balance',
        'payableamount',
        'totalpaidamount',
        'isbilllate',
    ];

    public function billItems()
    {
        return $this->hasMany(IndividualBillItem::class, 'individualbillid', 'id');
    }
}
