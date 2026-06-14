<?php

namespace App\Http\Requests;

// ============================================================
// Reglas del examen:
//   - No permitir CI duplicado     → uq_postulante_ci
//   - Validar correo electrónico   → uq_postulante_correo
//   - Validar campos vacíos
//   - chr_sexo IN ('M','F','X')    → chk_postulante_sexo
// ============================================================

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostulanteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // CU-06: CI obligatorio y único (regla explícita del examen)
            'txt_ci'         => [
                'required', 'string', 'max:20',
                'unique:tbl_postulante,txt_ci',
            ],
            // Nombres y Apellidos completos
            'txt_nombre'     => ['required', 'string', 'min:3', 'max:150'],

            // Teléfono opcional
            'txt_telefono'   => ['nullable', 'string', 'max:20'],

            // Correo obligatorio y único (regla explícita del examen)
            'txt_correo'     => [
                'required', 'email', 'max:100',
                'unique:tbl_postulante,txt_correo',
            ],
            // Fecha de nacimiento — no puede ser en el futuro
            'fch_nacimiento' => ['required', 'date', 'before:today'],

            // Sexo: solo M, F o X (constraint de PostgreSQL)
            'chr_sexo'       => ['required', 'in:M,F,X'],

            // Opcionales
            'txt_direccion'  => ['nullable', 'string', 'max:255'],
            'txt_colegio'    => ['nullable', 'string', 'max:150'],
            'txt_ciudad'     => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'txt_ci.required'         => 'El CI del postulante es obligatorio.',
            'txt_ci.unique'           => 'Ya existe un postulante registrado con ese CI.',
            'txt_ci.max'              => 'El CI no puede superar los 20 caracteres.',
            'txt_nombre.required'     => 'El nombre completo es obligatorio.',
            'txt_nombre.min'          => 'El nombre debe tener al menos 3 caracteres.',
            'txt_correo.required'     => 'El correo electrónico es obligatorio.',
            'txt_correo.email'        => 'El formato del correo no es válido.',
            'txt_correo.unique'       => 'Ya existe un postulante registrado con ese correo.',
            'fch_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fch_nacimiento.date'     => 'La fecha de nacimiento no tiene un formato válido.',
            'fch_nacimiento.before'   => 'La fecha de nacimiento no puede ser en el futuro.',
            'chr_sexo.required'       => 'El sexo es obligatorio.',
            'chr_sexo.in'             => 'El sexo debe ser M (Masculino), F (Femenino) o X (Otro).',
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
