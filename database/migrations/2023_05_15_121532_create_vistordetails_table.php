<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVistordetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vistordetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gatekeeperid');
            $table->foreign('gatekeeperid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('societyid');
            $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('houseaddress');

            $table->string('visitortype');
            $table->string('name');
            
            $table->string('cnic');
            $table->string('mobileno');
            $table->string('vechileno');
            $table->date('arrivaldate');
            $table->time('arrivaltime');
            $table->date('checkoutdate');
            $table->time('checkouttime');
            
            $table->integer('status');
            $table->string('statusdescription');
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
        Schema::dropIfExists('vistordetails');
    }
}