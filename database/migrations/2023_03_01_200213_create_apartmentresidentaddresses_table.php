<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentresidentaddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartmentresidentaddresses', function (Blueprint $table) {
            $table->id();    
             $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('societyid');
            $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            $table->unsignedBigInteger('buildingid');
            $table->foreign('buildingid')->references('id')->on('societybuildings')->onDelete('cascade');
            $table->unsignedBigInteger('societybuildingfloorid');
            $table->foreign('societybuildingfloorid')->references('id')->on('societybuildingfloors')->onDelete('cascade');
            $table->unsignedBigInteger('societybuildingapartmentid');
            $table->foreign('societybuildingapartmentid')->references('id')->on('societybuildingapartments')->onDelete('cascade');
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
        Schema::dropIfExists('apartmentresidentaddresses');
    }
}