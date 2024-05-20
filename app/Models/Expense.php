<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable=[
        'subadmin_id',
        'financemanager_id',
        'amount',
        'description',
        'expense_type',
        'payment_method',
        'date',
        'document'
    ];
}
