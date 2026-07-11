<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            'tipo' => ['required', 'in:dog,cat'],
            'foto' => ['nullable', 'image', 'max:5120'], // 5MB
            'nota' => ['nullable', 'string'],
        ];
    }
}

?>