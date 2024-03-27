<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatrooms', function (Blueprint $table) {
            $table->id();
            $table->unique(array('sender', 'receiver'));
            $table->unsignedBigInteger('sender');
            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('receiver');
            $table->foreign('receiver')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['default', 'block', 'pending','accepted','rejected'])->default('default');

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
        Schema::dropIfExists('chatrooms');
    }
}
