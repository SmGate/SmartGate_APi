<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalbuildingfloorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localbuildingfloors', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedBigInteger('buildingid');
            $table->foreign('buildingid')->references('id')->on('societies')->onDelete('cascade');
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('id')->on('users')->onDelete('cascade');


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
        Schema::dropIfExists('localbuildingfloors');
    }
}