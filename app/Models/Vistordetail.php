<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vistordetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "gatekeeperid",
        "societyid",
        "subadminid",
        
        "houseaddress",
        "visitortype",
        "name",
        
        "cnic",
        "mobileno",
        "vechileno",
        "arrivaldate",
        "arrivaltime",
        "checkoutdate",
        "checkouttime",
        
        "status",
        "statusdescription"
    ];
    protected $casts = [
        "gatekeeperid" => 'integer',
        'status' => 'integer',

    ];
}