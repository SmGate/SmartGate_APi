<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localbuildingapartmentresidentaddress extends Model
{
    use HasFactory;
    protected $fillable = [

        "residentid",
        	"localbuildingid",
            "pid",
            	"fid",	
                    "aid",	
                    "measurementid"
    ];

    public function society()
    {
        return $this->hasMany('App\Models\Society',"id",'localbuildingid');
    }

    


    

    public function floor()
    {


        return $this->hasMany('App\Models\Localbuildingfloor',"id",'fid');
    }



    public function apartment()
    {

        return $this->hasMany('App\Models\Localbuildingapartment',"id",'aid');

    }

    public function measurement()
    {

        return $this->hasMany('App\Models\Measurement',"id",'measurementid');

    }
}