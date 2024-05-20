<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Resident;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey="id";


    public function getResidentData()

    { return $this->hasOne('App\Models\Resident'); }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'firstname',
        'lastname',
        'cnic',
        'address',
        'mobileno',
        'password',
        'roleid',
        'rolename',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // 'password',
        'remember_token',
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

     public function role()
     {
        return $this->hasOne(Role::class,'id','roleid');
     }
     public function familymember()
     {
         return $this->hasMany('App\Models\Familymember',"id",'familymemberid');
     }
     public function gateKeeper()
     {
        return $this->hasOne(Gatekeeper::class,"gatekeeperid",'id');
     }


     public function resident()
     {
         return $this->hasOne(Resident::class,"residentid",'id');
     }


    
     public function subadmin()
     {
         return $this->hasOne('App\Models\User',"id",'subadminid');
     }
     public function financeManager()
     {
         return $this->hasOne(Financemanager::class,"financemanagerid",'id');
     }
     public function superadmin()
     {
         return $this->hasOne('App\Models\User',"id",'superadminid');
     }
     public function society()
     {
         return $this->hasOne('App\Models\Society',"id",'societyid');
     }




 
}
