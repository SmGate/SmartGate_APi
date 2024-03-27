<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familymembers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('residentid')->on('residents')->onDelete('cascade');
            $table->unsignedBigInteger('familymemberid');
            $table->foreign('familymemberid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('subadminid')->on('residents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familymembers');
    }
};
