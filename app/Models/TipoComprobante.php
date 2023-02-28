<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $fillable = [
        'codigo',
        'nombre'
    ];

    public function invoice()
    {
        return $this->hasOne(invoice::class);
    }
}