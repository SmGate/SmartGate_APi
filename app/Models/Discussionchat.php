<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussionchat extends Model
{
    use HasFactory;

    protected $fillable = [
        'residentid',
        'discussionroomid',
        'message'
    ];

    public function resident()
    {
        return $this->hasMany('App\Models\Resident','residentid','residentid');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User','id','residentid');
    }


    protected $casts = [
         "discussionroomid"=> 'integer',
         "residentid"=> 'integer',
         "id"=> 'integer',


];
}
