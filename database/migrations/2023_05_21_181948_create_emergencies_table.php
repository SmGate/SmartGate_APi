<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('societyid');
            $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('id')->on('users')->onDelete('cascade');
            $table->string('problem');
            $table->string('description');
            $table->integer('status');
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
        Schema::dropIfExists('emergencies');
    }
}
