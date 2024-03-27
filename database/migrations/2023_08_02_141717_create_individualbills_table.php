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
        Schema::create('individualbills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('financemanagerid');
            $table->foreign('financemanagerid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('residentid')->on('residents')->onDelete('cascade');
            // $table->unsignedBigInteger('propertyid');
            // $table->foreign('propertyid')->references('id')->on('properties')->onDelete('cascade');

            $table->date('billstartdate');
            $table->date('billenddate');
            $table->date('duedate');
            $table->string('billtype');
            $table->string('paymenttype');
            $table->enum('status', ['paid', 'unpaid', 'partiallypaid'])->default('unpaid');
            $table->decimal('charges');
            $table->decimal('latecharges');
            $table->decimal('tax');
            $table->decimal('balance');
            $table->decimal('payableamount');
            $table->decimal('totalpaidamount');
            $table->integer('isbilllate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individualbills');
    }
};