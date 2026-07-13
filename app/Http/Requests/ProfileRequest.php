<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:150'],
            'birth_date' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
