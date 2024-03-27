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
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->string('country');

            $table->string('state');


            $table->string('city');
            $table->string('area');

            $table->string('type');



            $table->string('name');

            $table->string('address');
            $table->unsignedBigInteger('superadminid');
            $table->foreign('superadminid')->references('id')->on('users')->onDelete('cascade');
            $table->integer('structuretype');
            // $table->unsignedBigInteger('roleid');
            // $table->foreign('roleid')->references('roleid')->on('users');
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
        Schema::dropIfExists('societies');
    }
};
