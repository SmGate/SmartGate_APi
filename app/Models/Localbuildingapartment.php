<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localbuildingapartment extends Model
{
    protected $fillable = [

        'localbuildingfloorid',
        'name',
        'from',
        'to'
    ];
    use HasFactory;
}
