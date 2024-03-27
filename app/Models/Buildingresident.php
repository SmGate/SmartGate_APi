<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buildingresident extends Model
{
    use HasFactory;
    protected $fillable = [
        "residentid",
        "subadminid",
        "country",
        "state",
        "city",
        "buildingname",
        "floorname",
        "apartmentid",

        "houseaddress",
        "vechileno",
        "residenttype",
        "propertytype",
        "committeemember",
        "status",
    ];
}
