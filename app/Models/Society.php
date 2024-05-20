<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Society extends Model
{
    use HasFactory;

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
    public function subadmin()
    {
        return $this->belongsTo(Subadmin::class,'id','societyid');
    }
    public function financemanager()
    {
        return $this->belongsTo(Financemanager::class,'id','societyid');
    }
    

    
}
