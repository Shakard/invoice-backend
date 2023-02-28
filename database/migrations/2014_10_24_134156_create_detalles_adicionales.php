<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesAdicionales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_adicionales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('valor', 200);
            $table->bigInteger('producto_id')->unsigned();
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_adicionales');
    }
}
