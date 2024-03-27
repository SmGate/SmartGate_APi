<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class Block extends Model
{    protected $primeryKey='id';

    use HasFactory;
    

    protected $fillable = [

        'pid',
        'noofphases',
        'from',
        'to'
    ];
}
