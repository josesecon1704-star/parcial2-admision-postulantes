<?php

namespace App\Http\Requests;

// ============================================================
// DESTINO: app/Http/Requests/UpdateUsuarioRequest.php
// Valida la edición de un usuario existente (CU-04)
// Usa Rule::ignore para el unique del usuario que se edita
// ============================================================

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // id del usuario que se está editando (viene de la URL /usuarios/{id})
        $idUsuario = $this->route('usuario');

        return [
            'txt_username' => [
                'sometimes', 'string', 'min:3', 'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
                // Ignorar el registro actual para no chocar con su propio username
                Rule::unique('tbl_usuario', 'txt_username')->ignore($idUsuario, 'id_usuario'),
            ],
            'txt_email' => [
                'sometimes', 'email', 'max:100',
                Rule::unique('tbl_usuario', 'txt_email')->ignore($idUsuario, 'id_usuario'),
            ],
            // Cambio de contraseña es opcional en el update
            'txt_password' => [
                'sometimes', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/',
            ],
            'id_rol'     => ['sometimes', 'integer', 'exists:tbl_rol,id_rol'],
            'bol_estado' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'txt_username.unique'    => 'Ese nombre de usuario ya está en uso.',
            'txt_email.unique'       => 'Ese correo ya está registrado por otro usuario.',
            'txt_password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'txt_password.confirmed' => 'Las contraseñas no coinciden.',
            'txt_password.regex'     => 'La contraseña debe tener al menos una mayúscula y un número.',
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
