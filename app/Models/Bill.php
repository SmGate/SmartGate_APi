<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;


    protected $fillable = [

        'residentid',

        "charges",
        	"chargesafterduedate"	,
            "appcharges",
            	"tax",	"balance",
                	"subadminid",	
                    "residentid",	"propertyid",
                    	"measurementid",
                        	"duedate"	,"billstartdate",

                            "billenddate",	"month"	, "status"	
        
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User',"id",'residentid');
    }


    public function property()
    {
        return $this->hasMany('App\Models\Property',"id",'propertyid');
    }
    
    public function measurement()
    {
        return $this->hasMany('App\Models\Measurement',"id",'measurementid');
    }

    public function resident()
    {
        return $this->hasMany('App\Models\Resident','residentid','residentid');
    }


    public function societybuildingapartments()
    {
        return $this->hasMany('App\Models\Societybuildingapartment',"id",'societybuildingapartmentid');
    }

}
