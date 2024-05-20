<?php

use App\Http\Controllers\SocietyController;
use Illuminate\Support\Facades\Route;

Route::prefix('super-finance')->group(function () {
 
    Route::resource('society',SocietyController::class);
});

