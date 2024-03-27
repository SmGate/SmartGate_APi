<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Gatekeeper extends Model
{
    use HasFactory;
    protected $fillable = [
        "gatekeeperid",
        "subadminid",
        "societyid",
        
         "gateno",
    ];
}