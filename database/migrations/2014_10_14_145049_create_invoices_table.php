<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('clave_acceso', 49);            
            $table->string('codigo_establecimiento', 3);
            $table->string('punto_emision', 3);
            $table->string('secuencial', 9);
            $table->string('numero_autorizacion', 37)->nullable();
            $table->string('ruc_emisor', 13);
            $table->string('nombre_comercial_emisor', 200);
            $table->string('razon_social_emisor', 200);
            $table->string('ambiente', 2);
            $table->string('estado', 2);
            $table->string('mensaje', 1000)->nullable();
            $table->date('fecha_emision');
            $table->dateTime('fecha_autorizacion')->nullable();
            $table->string('valor_total_factura', 100);
            $table->bigInteger('tipo_comprobante_id')->unsigned()->nullable();
            // $table->string('archivo_respuesta_sri');// deberia ser mediumblob
            // $table->string('comprobante_firmado');// deberia ser mediumblob
            $table->boolean('notificado_correo')->default('0');
            $table->boolean('visto_emisor')->default('0');
            $table->string('tipo_pago', 2)->nullable();
            $table->string('numero_documento_transferencia', 100)->nullable();
            $table->timestamps();

            $table->foreign('tipo_comprobante_id')->references('id')->on('tipos_comprobante_electronico')->onDelete("cascade");          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
