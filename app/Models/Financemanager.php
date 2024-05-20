<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financemanager extends Model
{
    use HasFactory;

    protected $fillable = [
        "subadminid",
        "societyid",
        "superadminid",
        "financemanagerid",
        "status"
        ];
    public function society()
    {
        return $this->hasOne(Society::class,"id",'societyid');
    }
    public function user()
    {
        return $this->hasOne(User::class,"id",'financemanagerid');
    }
}
