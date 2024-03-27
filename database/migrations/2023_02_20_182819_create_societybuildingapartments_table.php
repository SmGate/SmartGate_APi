<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocietybuildingapartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('societybuildingapartments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('societybuildingfloorid');
            $table->foreign('societybuildingfloorid')->references('id')->on('societybuildingfloors')->onDelete('cascade');
            $table->string('name'); 
            $table->unsignedBigInteger('typeid'); 
            $table->string('type'); 
            
            $table->integer('occupied')->default(0); // 0,1 
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
        Schema::dropIfExists('societybuildingapartments');
    }
}