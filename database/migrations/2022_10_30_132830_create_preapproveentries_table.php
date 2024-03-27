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
        Schema::create('preapproveentries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gatekeeperid');
            $table->foreign('gatekeeperid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->string('visitortype');
            $table->string('name');
            $table->string('description');
            $table->string('cnic');
            $table->string('mobileno');
            $table->string('vechileno');
            $table->date('arrivaldate');
            $table->time('arrivaltime');
            $table->integer('status');
            $table->string('statusdescription');
            $table->time('checkintime')->nullable();
            $table->time('checkouttime')->nullable();
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
        Schema::dropIfExists('preapproveentries');
    }
};
