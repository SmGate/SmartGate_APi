<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{

    protected $primarykey='residentid';
    // public $incrementing = false;
    use HasFactory;


    protected $fillable = [
        "residentid",
        "ownername",
         "owneraddress",
           "ownermobileno"



    ];
}
