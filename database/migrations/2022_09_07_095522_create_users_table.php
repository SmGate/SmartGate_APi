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
        Schema::create('users', function (Blueprint $table) {


            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('cnic')->unique()->nullable();
            $table->string('address');
            $table->string('mobileno');
            $table->string('password');
            $table->unsignedBigInteger('roleid');
            $table->string('rolename');
            $table->string('image');
            $table->string('fcmtoken')->nullable();
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
        Schema::dropIfExists('users');
    }
};
