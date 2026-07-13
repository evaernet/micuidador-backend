<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cuidador_id' => ['required', 'exists:users,id'],
            'pets' => ['required', 'array', 'min:1'],
            'pets.*' => ['exists:pets,id'],
            'fecha_llegada' => ['required', 'date'],
            'hora_llegada' => ['required', 'date_format:H:i'],
            'fecha_retiro' => ['required', 'date', 'after:fecha_llegada'],
            'hora_retiro' => ['required', 'date_format:H:i'],
        ];
    }
}
