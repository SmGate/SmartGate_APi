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
        Schema::create('gatekeepers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gatekeeperid');
            $table->foreign('gatekeeperid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('societyid');
            $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            
            $table->string('gateno');
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
        Schema::dropIfExists('gatekeepers');
    }
};