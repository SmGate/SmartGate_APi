<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplaces', function (Blueprint $table) {
            // $table->id();
            // $table->unsignedBigInteger('residentid');
            // $table->foreign('residentid')->references('residentid')->on('residents')->onDelete('cascade');
            // $table->unsignedBigInteger('societyid');
            // $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            // $table->unsignedBigInteger('subadminid');
            // $table->foreign('subadminid')->references('subadminid')->on('subadmins')->onDelete('cascade');

            // $table->string('productname');
            // $table->string('description');
            // $table->string('productprice');
            // $table->string('images');

            // //$table->json('images')->nullable();
            // $table->timestamps();

            $table->id();
            $table->unsignedBigInteger('residentid');
            $table->foreign('residentid')->references('residentid')->on('residents')->onDelete('cascade');
            $table->unsignedBigInteger('societyid');
            $table->foreign('societyid')->references('id')->on('societies')->onDelete('cascade');
            $table->unsignedBigInteger('subadminid');
            $table->foreign('subadminid')->references('subadminid')->on('subadmins')->onDelete('cascade');
            $table->string('productname');
            $table->string('description');
            $table->string('productprice');
            $table->string('contact');
            $table->string('category');
            $table->string('condition');
            // $table->string('images');
            $table->enum('status', ['sold', 'forsale','unavailable'])->default('forsale');

            //$table->json('images')->nullable();
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
        Schema::dropIfExists('marketplaces');
    }
}
