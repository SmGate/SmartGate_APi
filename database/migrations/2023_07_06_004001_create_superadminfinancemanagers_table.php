<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperadminfinancemanagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('superadminfinancemanagers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financemanagerid');
            $table->foreign('financemanagerid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('superadminid');
            $table->foreign('superadminid')->references('id')->on('users')->onDelete('cascade');
            $table->string('status');
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
        Schema::dropIfExists('superadminfinancemanagers');
    }
}
