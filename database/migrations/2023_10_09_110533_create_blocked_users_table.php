<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blocked_users', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('userid');
        $table->unsignedBigInteger('blockeduserid');
        $table->unsignedBigInteger('chatroomid');
        $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('chatroomid')->references('id')->on('chatrooms')->onDelete('cascade');
        $table->foreign('blockeduserid')->references('id')->on('users')->onDelete('cascade');
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_users');
    }
};
