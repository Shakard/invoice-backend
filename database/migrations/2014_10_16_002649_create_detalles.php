<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_principal', 25);
            $table->string('descripcion', 100);
            $table->string('cantidad', 100);
            $table->string('precio_unitario', 100);
            $table->bigInteger('comprobante_id')->unsigned();
            $table->timestamps();

            $table->foreign('comprobante_id')->references('id')->on('invoices')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles');
    }
}

