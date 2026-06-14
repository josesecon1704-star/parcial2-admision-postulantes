<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// POST /api/v1/stripe/webhook
// SIN autenticación, SIN CSRF (debe excluirse en bootstrap/app.php
// o estar en routes/api.php que ya está fuera de CSRF por defecto).
//
// Verifica la firma con STRIPE_WEBHOOK_SECRET y, ante el evento
// 'checkout.session.completed', marca el Pago correspondiente
// como APROBADO. Esta es la ÚNICA fuente de verdad real del pago
// (el frontend solo muestra un estado provisional vía PagoController::estado).
// ============================================================

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly StripeService $stripe
    ) {}

    public function handle(Request $request): JsonResponse
    {
        $payload   = $request->getContent();
        $signature = $request->header('Stripe-Signature', '');

        try {
            $event = $this->stripe->construirEventoWebhook($payload, $signature);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook: payload inválido', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payload inválido'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook: firma inválida', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Firma inválida'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                /** @var \Stripe\Checkout\Session $session */
                $session = $event->data->object;

                if ($session->payment_status === 'paid') {
                    $this->stripe->confirmarPagoPorSesion($session);
                }
                break;

            default:
                // Otros eventos se ignoran silenciosamente.
                break;
        }

        return response()->json(['received' => true]);
    }
}
