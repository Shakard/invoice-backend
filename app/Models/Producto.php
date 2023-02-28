<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
       'codigo',
       'descripcion',
       'cantidad',
       'precio_venta',
       'comprobante_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}