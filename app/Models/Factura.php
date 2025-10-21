<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'id_orden',
        'id_users',
        'tipo_factura',
        'fecha_emision',
        'nombre_cliente',
        'nit_cliente',
        'direccion_cliente',
        'telefono_cliente',
        'subtotal',
        'impuestos',
        'totaliva',
        'total',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detalles_factura()
    {
        return $this->hasMany(DetalleFactura::class, 'id_factura');
    }
}
