<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionchatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussionchats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('discussionroomid');
            $table->foreign('discussionroomid')->references('id')->on('discussionrooms')->onDelete('cascade');
            $table->longText('message');
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('discussionchats');
    }
}
