<?php

namespace App\Http\Requests;

// ============================================================
// DESTINO: app/Http/Requests/UpdatePostulanteRequest.php
//
// CU-07: Modificar datos del postulante
// Usa Rule::ignore para no chocar con sus propios únicos (CI/correo)
// ============================================================

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePostulanteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // id del postulante que llega en la URL /postulantes/{postulante}
        $id = $this->route('postulante');

        return [
            'txt_ci' => [
                'sometimes', 'string', 'max:20',
                // Ignorar el CI del propio postulante para que no falle en su propio registro
                Rule::unique('tbl_postulante', 'txt_ci')->ignore($id, 'id_postulante'),
            ],
            'txt_nombre'     => ['sometimes', 'string', 'min:3', 'max:150'],
            'txt_telefono'   => ['nullable', 'string', 'max:20'],
            'txt_correo'     => [
                'sometimes', 'email', 'max:100',
                Rule::unique('tbl_postulante', 'txt_correo')->ignore($id, 'id_postulante'),
            ],
            'fch_nacimiento' => ['sometimes', 'date', 'before:today'],
            'chr_sexo'       => ['sometimes', 'in:M,F,X'],
            'txt_direccion'  => ['nullable', 'string', 'max:255'],
            'txt_colegio'    => ['nullable', 'string', 'max:150'],
            'txt_ciudad'     => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'txt_ci.unique'         => 'Ya existe otro postulante con ese CI.',
            'txt_correo.email'      => 'El formato del correo no es válido.',
            'txt_correo.unique'     => 'Ya existe otro postulante con ese correo.',
            'fch_nacimiento.before' => 'La fecha de nacimiento no puede ser en el futuro.',
            'chr_sexo.in'           => 'El sexo debe ser M, F o X.',
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
