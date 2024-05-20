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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subadmin_id')->nullable();
            $table->unsignedBigInteger('financemanager_id')->nullable();
            $table->double('amount');
            $table->longText('description')->nullable()->default(null);
            $table->string('expense_type');
            $table->string('payment_method');
            $table->date('date')->nullable()->default(date('Y-m-d'));
            $table->string('document')->nullable()->default(null);
            $table->timestamps();
            $table->foreign('subadmin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('financemanager_id')->references('id')->on('users')->onDelete('set null');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
