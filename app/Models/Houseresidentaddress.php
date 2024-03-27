<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Houseresidentaddress extends Model
{
    use HasFactory;

    protected $table = "houseresidentaddresses";
    protected $primarykey='residentid';

    protected $fillable = [
        "residentid",
        "societyid",
        "pid",
        "bid",
        "sid",
        "propertyid",
        "measurementid"

      
    ];

    public function property()
    {
        return $this->hasMany('App\Models\Property',"id",'propertyid');
    }
    
    public function measurement()
    {
        return $this->hasMany('App\Models\Measurement',"id",'measurementid');
    }
    

}
