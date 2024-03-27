<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseresidentaddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houseresidentaddresses', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('societyid');
            $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            $table->unsignedBigInteger('pid');
            // $table->foreign('pid')->references('id')->on('phases')->onDelete('cascade');
            $table->unsignedBigInteger('bid');
            // $table->foreign('bid')->references('id')->on('blocks')->onDelete('cascade');
            $table->unsignedBigInteger('sid');
            // $table->foreign('sid')->references('id')->on('streets')->onDelete('cascade');
            $table->unsignedBigInteger('propertyid');
            $table->foreign('propertyid')->references('id')->on('properties')->onDelete('cascade');
            $table->unsignedBigInteger('measurementid');
            $table->foreign('measurementid')->references('id')->on('measurements')->onDelete('cascade');
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
        Schema::dropIfExists('houseresidentaddresses');
    }
}