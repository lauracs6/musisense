<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Usuario objetivo de la eliminación (desactivación):
        // - En DELETE /users/{user} viene desde la ruta
        // - En DELETE /user se usa el usuario autenticado
        $target = $this->route('user') ?? $this->user();
        $me = $this->user();

        // Autorización: debe haber usuario autenticado y ser admin o el mismo usuario
        return $me && ($me->isAdmin() || $me->id === $target->id);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return []; // No se necesitan reglas adicionales
    }
}
