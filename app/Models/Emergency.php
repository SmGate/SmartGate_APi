<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;
    protected $fillable = [
        "residentid",
        "societyid",
        "subadminid",
        "problem",
        "description",
        "status"

    ];


    public function resident()
    {
        return $this->hasMany('App\Models\User', 'id', 'residentid');
    }
}
