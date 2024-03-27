<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalbuildingapartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localbuildingapartments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('localbuildingfloorid');
            $table->foreign('localbuildingfloorid')->references('id')->on('localbuildingfloors')->onDelete('cascade');
            $table->string('name');

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
        Schema::dropIfExists('localbuildingapartments');
    }
}