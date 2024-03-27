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
        Schema::create('individualbillitems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('individualbillid');
            $table->foreign('individualbillid')->references('id')->on('individualbills')->onDelete('cascade');
            $table->string('billname');
            $table->decimal('billprice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individualbillitems');
    }
};