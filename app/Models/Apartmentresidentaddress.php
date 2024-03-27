<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartmentresidentaddress extends Model
{
    use HasFactory;
    protected $fillable = [

        "residentid",
        	"societyid",
            "pid",
            	"buildingid",
                	"societybuildingfloorid",	
                    "societybuildingapartmentid",	
                    "measurementid"
    ];

    public function society()
    {
        return $this->hasMany('App\Models\Society',"id",'societyid');
    }

    public function phase()
    {
        return $this->hasMany('App\Models\Phase',"id",'pid');
    }


    public function building()
    {

        return $this->hasMany('App\Models\Societybuilding',"id",'buildingid');
    }

    public function floor()
    {


        return $this->hasMany('App\Models\Societybuildingfloor',"id",'societybuildingfloorid');
    }



    public function apartment()
    {

        return $this->hasMany('App\Models\Societybuildingapartment',"id",'societybuildingapartmentid');

    }

    public function measurement()
    {

        return $this->hasMany('App\Models\Measurement',"id",'measurementid');

    }
    public function societybuildingapartments()
    {
        return $this->hasMany('App\Models\Societybuildingapartment',"id",'societybuildingapartmentid');
    }

}