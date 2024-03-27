<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [

        'fid',
        'noofapartments',
        'from',
        'to'
    ];
    use HasFactory;
}
