<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'clave_acceso',             
        'codigo_establecimiento',
        'punto_emision',
        'secuencial',
        'numero_autorizacion',  
        'ruc_emisor',  
        'nombre_comercial_emisor',  
        'razon_social_emisor',  
        'ambiente',  
        'estado',  
        'mensaje',
        'fecha_emision',
        'fecha_autorizacion',
        'valor_total_factura',
        'archivo_respuesta_sri',
        'comprobante_firmado',
        'notificado_correo',
        'visto_emisor',
        'tipo_pago',
        'numero_documento_transferencia'
    ];

    public function tipoComprobante()
    {
        return $this->belongsTo(tipoComprobante::class);
    }
}
