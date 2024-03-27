<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preapproveentry extends Model
{
    use HasFactory;
    protected $fillable = [
        "gatekeeperid",
        "userid",
        "visitortype",
        "name",
        "description",
        "cnic",
        "mobileno",
        "vechileno",
        "arrivaldate",
        "arrivaltime",
        "status",
        "statusdescription"
    ];
    protected $casts = [ "gatekeeperid"=> 'integer',
     'userid' => 'integer',
     'status' => 'integer',

    ];
}
