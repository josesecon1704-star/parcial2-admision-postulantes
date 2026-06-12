<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/PagoController.php
// (archivo NUEVO)
//
// Endpoints para iniciar el pago de matrícula (Stripe Checkout)
// y consultar su estado al volver de Stripe.
//
// POST /api/v1/public/pagos/{idInscripcion}/crear-sesion
//      → público, usado justo después del registro
//
// POST /api/v1/postulante/pagos/crear-sesion
//      → postulante.jwt, desde "Mi Perfil" si el pago sigue PENDIENTE
//
// GET  /api/v1/public/pagos/estado/{sessionId}
//      → público, usado por la página de retorno de Stripe
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Postulante;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function __construct(
        private readonly StripeService $stripe
    ) {}

    /**
     * POST /api/v1/public/pagos/{idInscripcion}/crear-sesion
     * Usado en registroPostulante.blade.php justo tras el registro,
     * cuando el postulante elige "Pagar matrícula ahora".
     */
    public function crearSesionPublica(Request $request, int $idInscripcion): JsonResponse
    {
        $inscripcion = Inscripcion::find($idInscripcion);

        if (! $inscripcion) {
            return response()->json([
                'success' => false,
                'message' => 'Inscripción no encontrada.',
            ], 404);
        }

        return $this->generarSesion($inscripcion, $request);
    }

    /**
     * POST /api/v1/postulante/pagos/crear-sesion
     * El postulante autenticado (vía postulante.jwt) paga su propia
     * matrícula desde el portal si su pago sigue PENDIENTE.
     */
    public function crearSesionPostulante(Request $request): JsonResponse
    {
        $postulante = $request->attributes->get('auth_sujeto');

        if (! $postulante instanceof Postulante) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $inscripcion = $postulante->inscripciones()
            ->latest('fch_inscripcion')
            ->first();

        if (! $inscripcion) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes una inscripción registrada.',
            ], 404);
        }

        return $this->generarSesion($inscripcion, $request);
    }

    /**
     * Lógica común: valida que exista un Pago PENDIENTE para la
     * inscripción y crea la Checkout Session de Stripe.
     */
    private function generarSesion(Inscripcion $inscripcion, Request $request): JsonResponse
    {
        $pago = Pago::where('id_inscripcion', $inscripcion->id_inscripcion)->first();

        if (! $pago) {
            return response()->json([
                'success' => false,
                'message' => 'No existe un registro de pago para esta inscripción.',
            ], 404);
        }

        if ($pago->txt_estado === 'APROBADO') {
            return response()->json([
                'success' => false,
                'message' => 'Esta matrícula ya fue pagada.',
            ], 409);
        }

        $origin = $request->header('Origin') ?: config('app.url');

        $successUrl = rtrim($origin, '/') . '/pago-resultado';
        $cancelUrl  = rtrim($origin, '/') . '/pago-resultado?status=cancelado';

        try {
            $url = $this->stripe->crearSesionCheckout($pago, $successUrl, $cancelUrl);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo iniciar el pago: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'checkout_url' => $url,
            ],
        ]);
    }

    /**
     * GET /api/v1/public/pagos/estado/{sessionId}
     * Usado por pago-resultado.blade.php al volver de Stripe.
     * Consulta directamente a Stripe (no depende del webhook para
     * mostrar el resultado al usuario), pero el webhook sigue siendo
     * la fuente de verdad que actualiza tbl_pago.
     */
    public function estado(string $sessionId): JsonResponse
    {
        try {
            $session = $this->stripe->obtenerEstadoSesion($sessionId);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo verificar el pago: ' . $e->getMessage(),
            ], 500);
        }

        $idInscripcion = $session->metadata->id_inscripcion ?? $session->client_reference_id ?? null;
        $pago = $idInscripcion ? Pago::where('id_inscripcion', $idInscripcion)->first() : null;

        return response()->json([
            'success' => true,
            'data'    => [
                'payment_status' => $session->payment_status, // 'paid' | 'unpaid' | 'no_payment_required'
                'estado_pago'    => $pago?->txt_estado ?? 'PENDIENTE',
                'monto'          => $pago?->num_monto,
            ],
        ]);
    }
}
