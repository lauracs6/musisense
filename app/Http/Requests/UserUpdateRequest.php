<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se realiza en el controlador
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Usuario que se está actualizando:
        // - Si la ruta tiene {user}, se usa ese
        // - Si no, se trata del usuario autenticado
        $user = $this->route('user') ?? $this->user();

        return [
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'status' => ['sometimes', 'in:y,n'],

            // Campos sensibles que no deben modificarse desde este endpoint genérico
            'role_id' => ['prohibited'],
            'password' => ['prohibited'],
        ];
    }
}
