<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial', 200);
            $table->string('razon_social', 200);
            $table->string('direccion', 200);
            $table->string('correo', 200);
            $table->string('telefono', 200);
            $table->boolean('obligado_contabilidad')->default('0');
            $table->string('numero_resolucion', 200);
            $table->boolean('estado')->default('0');
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
        Schema::dropIfExists('empresas');
    }
}
