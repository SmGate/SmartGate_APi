<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        "charges",
        "latecharges",
        "appcharges",
        "tax",   
        "payableamount",
        "balance",
        "subadminid",
        "financemanagerid",
        "residentid",    
        "propertyid",
        "measurementid",
        "duedate", 
        "billstartdate",
        "billenddate",    
        "charges",
        "charges",
        "chargesafterduedate",
        "paymenttype",
        "billtype",
        "noofappusers",
        "month", 
        "status", 
        "description", 
        "specific_type", 
        "totalpaidamount", 
    ];

    public function user()
    {
        return $this->hasOne(User::class, "id", 'residentid');
    }


    public function property()
    {
        return $this->hasOne(Property::class, "id", 'propertyid');
    }

    public function measurement()
    {
        return $this->hasOne(Measurement::class, "id", 'measurementid');
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'residentid', 'residentid');
    }
    public function subAdmin()
    {
        return $this->belongsTo(Subadmin::class, 'subadminid', 'subadminid');
    }
    public function financeManager()
    {
        return $this->belongsTo(Financemanager::class, 'financemanagerid', 'financemanagerid');
    }
    


    public function societybuildingapartments()
    {
        return $this->hasMany('App\Models\Societybuildingapartment', "id", 'societybuildingapartmentid');
    }
}
