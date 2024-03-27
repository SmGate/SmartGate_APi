<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    use HasFactory;
    protected $fillable = [
        "residentid",
        "societyid",
        "subadminid",
        "productname",
        "description",
        "productprice",
        

    ];

    
    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'residentid');
    }
    public function residents()
    {
        return $this->hasOne('App\Models\Resident', 'residentid', 'residentid');
    }

    public function images()
    {
        return $this->hasMany('App\Models\Marketplaceimages', 'marketplaceid', 'id');
    }

    public function society()
    {
        return $this->hasMany('App\Models\Marketplaceimages', 'marketplaceid', 'id');
    }




}
