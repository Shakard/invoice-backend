<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignacionComprobanteElectronico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_comprobante_electronico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comprobante_id')->unsigned();
            $table->boolean('visto_receptor')->default('0');
            $table->bigInteger('client_id')->unsigned();
            $table->timestamps();

            $table->foreign('comprobante_id')->references('id')->on('invoices')->onDelete("cascade");
            $table->foreign('client_id')->references('id')->on('clients')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignacion_comprobante_electronico');
    }
}
