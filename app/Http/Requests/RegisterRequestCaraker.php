<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequestCaraker extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ---- cuenta ----
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'min:8', 'max:30'],
            'foto_perfil' => ['nullable', 'image', 'max:5120'], // 5MB

            // ---- hospedaje ----
            'vivienda' => ['required', 'in:casa_patio,casa,quinta,depto'],
            'foto_hospedaje' => ['nullable', 'image', 'max:5120'],
            'descripcion' => ['nullable', 'string'],
            'mascotas_propias' => ['nullable', 'string'],

            'acepta_perro' => ['nullable', 'boolean'],
            'acepta_gato' => ['nullable', 'boolean'],
            'tamanos_aceptados' => ['nullable', 'array'],
            'tamanos_aceptados.*' => ['string', 'in:Miniatura,Pequeño,Mediano,Grande'],

            // ---- experiencia ----
            'experiencia' => ['nullable', 'string'],
            'declaracion' => ['required', 'accepted'], // tiene que venir true/1
        ];
    }
}