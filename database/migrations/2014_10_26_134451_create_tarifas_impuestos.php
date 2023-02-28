<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasImpuestos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifas_impuestos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 200);
            $table->string('descripcion', 200);
            $table->string('porcentaje', 200);
            $table->bigInteger('tipo_impuesto_id')->unsigned();
            $table->timestamps();

            $table->foreign('tipo_impuesto_id')->references('id')->on('tipos_impuestos')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarifas_impuestos');
    }
}
