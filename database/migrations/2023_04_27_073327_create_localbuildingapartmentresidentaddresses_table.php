<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalbuildingapartmentresidentaddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localbuildingapartmentresidentaddresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('localbuildingid');
            $table->foreign('localbuildingid')->references('id')->on('societies')->onDelete('cascade');

            $table->unsignedBigInteger('fid');
            $table->foreign('fid')->references('id')->on('localbuildingfloors')->onDelete('cascade');
            $table->unsignedBigInteger('aid');
            $table->foreign('aid')->references('id')->on('localbuildingapartments')->onDelete('cascade');
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
        Schema::dropIfExists('localbuildingapartmentresidentaddresses');
    }
}