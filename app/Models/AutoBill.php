<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoBill extends Model
{
    use HasFactory;
    protected $fillable=[
        'subadmin_id',
        'financemanager_id',
        'start_date',
        'end_date',
        'due_date',
        'start_month',
        'end_month'
    ];
}
