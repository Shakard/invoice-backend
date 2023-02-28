<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoices')->insert([
            'clave_acceso' => rand(49,49),
            'codigo_establecimiento' => rand(3,3),
            'punto_emision' => rand(3,3),
            'secuencial' => '000000131',
            'ruc_emisor' => rand(13,13),
            'nombre_comercial_emisor' => Str::random(10),
            'razon_social_emisor' => Str::random(10),
            'ambiente' => '1',
            'estado' => '02',
            'mensaje' => Str::random(10),
            'razon_social_emisor' => Str::random(10),
            'fecha_emision' => '2023-02-13',
            'fecha_autorizacion' => '2023-02-13',
            'valor_total_factura' =>  rand(4,4),
            'notificado_correo' => '0',
            'visto_emisor' => '0',
        ]);
    }
}
