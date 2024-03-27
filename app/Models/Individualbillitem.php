<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individualbillitem extends Model
{
    use HasFactory;
    protected $table = 'individualbillitems';
    protected $fillable = [
        'individualbillid',
        'billname',
        'billprice',
    ];

    public function bill()
    {
        return $this->belongsTo(IndividualBill::class, 'individualbillid', 'id');
    }
}
