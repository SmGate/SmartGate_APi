<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;
    protected $fillable = [
        'noticetitle',
        'noticedetail',
        'startdate',
        'enddate',
        // 'starttime',
        // 'endtime',

        'status',
        'subadminid',
    ];


    protected $casts = [
        "subadminid" => 'integer',


    ];
}
