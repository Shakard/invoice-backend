<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 200);
            $table->string('descripcion', 200);
            $table->string('unidad_medida', 200)->nullable();
            $table->string('cantidad', 200);
            $table->string('precio_costo', 200)->nullable();
            $table->string('porcentaje_venta', 200)->nullable();
            $table->string('precio_venta', 200);
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
        Schema::dropIfExists('productos');
    }
}
