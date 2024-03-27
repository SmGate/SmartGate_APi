<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superadminfinancemanagers extends Model
{
    use HasFactory;

    protected $fillable = [

        "superadminid",
        "financemanagerid",
        "status"

    ];
}
