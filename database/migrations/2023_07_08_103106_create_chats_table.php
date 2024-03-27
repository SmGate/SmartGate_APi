<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender');
            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('reciever');
            $table->foreign('reciever')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('chatroomid');
            $table->foreign('chatroomid')->references('id')->on('chatrooms')->onDelete('cascade');
            $table->longText('message');
            $table->string('lastmessage');
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
        Schema::dropIfExists('chats');
    }
}
