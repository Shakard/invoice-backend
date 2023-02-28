<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuenciales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secuenciales', function (Blueprint $table) {
            $table->id();
            $table->string('secuencial_factura', 100);
            $table->string('secuencial_nota_credito', 100);
            $table->string('secuencial_nota_debito', 100);
            $table->string('secuencial_guia_remision', 100);
            $table->string('secuencial_retencion', 100);
            $table->string('punto_emicion_secuencial', 3);
            $table->string('codigo_establecimiento_secuencial', 3);
            $table->string('direccion', 200);
            $table->boolean('estado')->default('0');
            $table->string('ambiente', 2);
            $table->bigInteger('empresa_id')->unsigned();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secuenciales');
    }
}
