<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/ProfesionController.php
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Profesion;
use Illuminate\Http\JsonResponse;

class ProfesionController extends Controller
{
    /**
     * GET /api/v1/profesiones
     * Lista todas las profesiones ordenadas alfabéticamente.
     * Usado por el select del formulario de registro de docente.
     */
    public function index(): JsonResponse
    {
        $profesiones = Profesion::orderBy('txt_descripcion')->get([
            'id_profesion',
            'txt_descripcion',
        ]);

        return response()->json([
            'success' => true,
            'data'    => $profesiones,
        ]);
    }
}
