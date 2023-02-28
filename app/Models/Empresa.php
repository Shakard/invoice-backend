<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $fillable = [
       'codigo_principal',
       'descripcion',
       'cantidad',
       'precio_unitario',
       'comprobante_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}