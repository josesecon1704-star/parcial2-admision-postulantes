<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// GET /api/v1/admision/resumen-cupos
// Devuelve, por carrera, cuántos cupos se usaron / disponibles
// según el cálculo en tiempo real de AdmisionService. Usado por
// "Reportes Analíticos" para mostrar la tarjeta de cupos.
// ============================================================

use App\Http\Controllers\Controller;
use App\Services\AdmisionService;
use Illuminate\Http\JsonResponse;

class AdmisionController extends Controller
{
    public function __construct(
        private readonly AdmisionService $admision
    ) {}

    public function resumenCupos(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->admision->resumenCupos(),
        ]);
    }
}
