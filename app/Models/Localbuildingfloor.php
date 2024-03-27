<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localbuildingfloor extends Model
{
    protected $fillable = [
        'name',
        'subadminid',
        'buildingid',

        'nooffloors'
    ];
    use HasFactory;
}
