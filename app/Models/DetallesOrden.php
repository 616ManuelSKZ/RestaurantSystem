<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallesOrden extends Model
{
    protected $table = 'detalles_orden';

    protected $fillable = [
        'id_orden',
        'id_menu',
        'cantidad',
        'precio_unitario',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden');
    }
}
