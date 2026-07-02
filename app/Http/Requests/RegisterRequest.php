<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>[
                'string',
                'required',
                'max:80'
            ],
            'last_name' =>[
                'nullable',
                'string',
                'max:80'
            ],
            'email'=>[
                'string',
                'email',
                'required',
                'unique:users,email'
            ],
            'password'=>[
                'confirmed',
                'min:8',
                'string',
                'required'
            ],
            'phone'=>[
                'nullable',
                'min:8',
                'max:30',
                'string'
            ],
            'birth_date'=>[
                'nullable',
                'date',
            ]
        ];
    }
}
