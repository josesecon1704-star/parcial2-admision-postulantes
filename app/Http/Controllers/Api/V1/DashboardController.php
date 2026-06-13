<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AdmisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdmisionService $admision
    ) {}

    /**
     * Obtener las métricas globales para el Dashboard de Admisión de la FICCT.
     * GET /api/v1/dashboard/metrics
     *
     * 'aprobados'  = postulantes con las 12 notas (3 exámenes x 4 materias)
     *                completas y TODAS >= 60 (AdmisionService).
     * 'reprobados' = inscritos - aprobados (incluye reprobados reales y
     *                quienes aún no tienen evaluaciones registradas).
     * 'aceptados'  = aprobados que además consiguieron cupo en su
     *                carrera_1 o carrera_2 (txt_resultado ADMITIDO_*).
     */
    public function getMetrics(): JsonResponse
    {
        try {
            // 1. Total Inscritos / Postulantes
            $inscritos = DB::table('tbl_postulante')->count();

            // 2. Total Grupos Habilitados
            $grupos = DB::table('tbl_grupo')->count();

            // 3. Resultados reales de admisión (AdmisionService)
            $resultados = $this->admision->calcularResultados();

            $aprobados = $resultados->filter(fn($r) => $r['aprobado'] === true)->count();
            $aceptados = $resultados->filter(fn($r) => in_array(
                $r['txt_resultado'],
                ['ADMITIDO_CARRERA_1', 'ADMITIDO_CARRERA_2'],
                true
            ))->count();
            $reprobados = $inscritos - $aprobados;

            return response()->json([
                'success' => true,
                'message' => 'Métricas procesadas correctamente desde PostgreSQL.',
                'data' => [
                    'inscritos'  => $inscritos,
                    'aprobados'  => $aprobados,
                    'aceptados'  => $aceptados,
                    'reprobados' => $reprobados,
                    'grupos'     => $grupos,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno en el servidor de la facultad: ' . $e->getMessage()
            ], 500);
        }
    }
}