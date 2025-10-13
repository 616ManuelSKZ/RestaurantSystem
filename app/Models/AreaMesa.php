<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaMesa extends Model
{
    protected $table = 'area_mesas';

    protected $fillable = [
        'nombre',
    ];

    public function mesas()
    {
        return $this->hasMany(Mesa::class, 'id_area_mesas');
    }
}
