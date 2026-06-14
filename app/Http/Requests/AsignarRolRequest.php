<?php

namespace App\Http\Requests;

// ============================================================
// Valida el cambio de rol de un usuario (CU-05)
// ============================================================

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AsignarRolRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id_rol' => ['required', 'integer', 'exists:tbl_rol,id_rol'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_rol.required' => 'El rol es obligatorio.',
            'id_rol.exists'   => 'El rol seleccionado no existe en el sistema.',
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
