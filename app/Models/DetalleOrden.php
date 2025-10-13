<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    protected $table = 'detalles_orden';

    protected $fillable = [
        'id_orden',
        'id_menu',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'nombre_menu',
        'precio_menu',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}
