<?php

namespace App\Http\Requests;

use App\Support\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:Administrador,Solicitante',
            'foto_perfil' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser texto.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'El email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol no es válido.',
            'foto_perfil.image' => 'La foto de perfil debe ser una imagen.',
            'foto_perfil.max' => 'La foto de perfil no puede superar 2 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(Sanitizer::cleanArray($this->all(), ['name']));
    }
}
