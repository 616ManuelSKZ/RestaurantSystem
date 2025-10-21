<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = 'detalles_factura';

    protected $fillable = [
        'id_factura',
        'id_menu',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'nombre_menu',
        'precio_menu',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'id_factura');
    }
}
