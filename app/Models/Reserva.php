<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'dueno_id',
        'cuidador_id',
        'estado',
        'fecha_llegada',
        'hora_llegada',
        'fecha_retiro',
        'hora_retiro',
    ];

    public function dueno()
    {
        return $this->belongsTo(User::class, 'dueno_id');
    }

    public function cuidador()
    {
        return $this->belongsTo(User::class, 'cuidador_id');
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'pet_reserva');
    }
}
