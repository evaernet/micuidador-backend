<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class CarakerProfile extends Model
{
    protected $fillable = [   //permite llenar cuando creo un cuidador o al atulizar - esto evita que alguien puedo rellenar campos que manejamos internamente
        'user_id',
        'vivienda',
        'foto_hospedaje',
        'descripcion',
        'mascotas_propias',
        'acepta_perro',
        'acepta_gato',
        'tamanos_aceptados',
        'experiencia',
        'declaracion',
    ];


        // Convierte automáticamente algunos campos al tipo de dato correspondiente.
    protected function casts():array
    {
        return [
            'tamanos_aceptados' => 'array', //Laravel convierte json a array o array a json SOLO
            'acepta_perros' => 'boolean',
            'acepta_gatos' => 'boolean',
            'declaracion' => 'boolean'
        ];

    }
    public function user(){
        return $this->BelongsTo(User::class); // Un perfil pertenece a un usuario.
    }
}
//belongsTo() → "Yo pertenezco a otro." (la tabla actual tiene la FK, por ejemplo user_id).
// hasOne() → "Yo tengo uno."
// hasMany() → "Yo tengo muchos."
// belongsToMany() → "Muchos de un lado con muchos del otro."