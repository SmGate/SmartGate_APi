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
        Schema::create('owners', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('residentid')->primarykey();
            $table->foreign('residentid')->references('id')->on('users')->onDelete('cascade');
            $table->string('ownername');
            $table->string('owneraddress');
            $table->string('ownermobileno');
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
        Schema::dropIfExists('owners');
    }
};
