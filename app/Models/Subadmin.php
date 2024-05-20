<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subadmin extends Model
{
    protected $fillable = [

        'superadminid',
        'societyid',
        'subadminid',
        'firstname',
        'lastname',
        'cnic',
        'password',
        'roleid',
        'rolename',

    ];
    use HasFactory;
    protected $hidden = [
        // 'password',
        'remember_token',
    ];
    public function society()
    {
        return $this->hasOne(Society::class,"id",'societyid');
    }
    public function user()
    {
        return $this->hasOne(User::class,"id",'subadminid');
    }

    

}
