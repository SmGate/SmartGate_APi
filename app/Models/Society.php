<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Society extends Model
{

    protected $primeryKey='id';

    protected $fillable = [

        'country',

        'state',


        'city',
        'area',
        'type',



        'name',

        'address',
        'superadminid'


    ];
    

    use HasFactory;
}
