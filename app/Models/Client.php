<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
       'ruc',
       'razon_social',
       'telefono',
       'direccion',
       'correo'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}