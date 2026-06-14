<?php

namespace App\Http\Requests;

// ============================================================
// Valida la creación de un nuevo usuario (CU-04)
// ============================================================

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // txt_username: obligatorio, único en tbl_usuario
            'txt_username' => [
                'required', 'string', 'min:3', 'max:50',
                'unique:tbl_usuario,txt_username',
                'regex:/^[a-zA-Z0-9._-]+$/',   // sin espacios ni caracteres raros
            ],
            // txt_email: obligatorio, único en tbl_usuario
            'txt_email'    => [
                'required', 'email', 'max:100',
                'unique:tbl_usuario,txt_email',
            ],
            // txt_password: mínimo 8 caracteres, al menos 1 mayúscula y 1 número
            'txt_password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/',
            ],
            // id_rol: debe existir en tbl_rol
            'id_rol'       => ['required', 'integer', 'exists:tbl_rol,id_rol'],
            // bol_estado: opcional, por defecto true
            'bol_estado'   => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'txt_username.required'  => 'El nombre de usuario es obligatorio.',
            'txt_username.unique'    => 'Ese nombre de usuario ya está en uso.',
            'txt_username.min'       => 'El usuario debe tener al menos 3 caracteres.',
            'txt_username.regex'     => 'El usuario solo puede contener letras, números, puntos, guiones y guiones bajos.',
            'txt_email.required'     => 'El correo electrónico es obligatorio.',
            'txt_email.email'        => 'El formato del correo no es válido.',
            'txt_email.unique'       => 'Ese correo ya está registrado.',
            'txt_password.required'  => 'La contraseña es obligatoria.',
            'txt_password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'txt_password.confirmed' => 'Las contraseñas no coinciden.',
            'txt_password.regex'     => 'La contraseña debe tener al menos una mayúscula y un número.',
            'id_rol.required'        => 'Debes asignar un rol al usuario.',
            'id_rol.exists'          => 'El rol seleccionado no existe.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
