<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesas';

    protected $fillable = [
        'id_area_mesas',
        'numero',
        'capacidad',
        'estado',
    ];

    public function area()
    {
        return $this->belongsTo(AreaMesa::class, 'id_area_mesas');
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'id_mesas');
    }
}
