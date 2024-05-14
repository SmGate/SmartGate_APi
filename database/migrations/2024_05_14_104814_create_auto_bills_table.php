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
        Schema::create('auto_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subadmin_id')->nullable();
            $table->unsignedBigInteger('financemanager_id')->nullable();
            $table->tinyInteger('is_consolidated')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('due_date');
            $table->string('start_month')->nullable()->default(null);
            $table->string('end_month')->nullable()->default(null);
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
        Schema::dropIfExists('auto_bills');
    }
};
