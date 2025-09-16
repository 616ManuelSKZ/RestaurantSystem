<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'ordenes';

    protected $fillable = [
        'id_mesa',
        'id_users',
        'estado',
        'fecha_orden',
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'id_mesa');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function detalles()
    {
        return $this->hasMany(DetallesOrden::class, 'id_orden');
    }
}
