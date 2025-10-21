<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'id_mesas',
        'id_users',
        'estado',
        'fecha_orden',
        'subtotal',
        'impuestos',
        'totaliva',
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detalles_orden()
    {
        return $this->hasMany(DetalleOrden::class, 'id_orden');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'id_orden');
    }

    public function getTotalAttribute()
    {
        return $this->detalles_orden->sum('subtotal');
    }
}
