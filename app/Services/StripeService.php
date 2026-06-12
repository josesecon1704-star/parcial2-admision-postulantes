<?php

namespace App\Services;

// ============================================================
// DESTINO: app/Services/StripeService.php
// (archivo NUEVO)
//
// Encapsula la integración con Stripe Checkout (pago único,
// modo 'payment') para la matrícula de admisión (Bs 350 fijo,
// cobrado en USD ya que Stripe no soporta BOB / cuentas en Bolivia).
//
// Requiere en .env:
//   STRIPE_SECRET=sk_test_...
//   STRIPE_WEBHOOK_SECRET=whsec_...
//   STRIPE_CURRENCY=usd
//   MATRICULA_USD=10.00   (equivalente aprox. a Bs 350)
//
// Requiere el paquete: composer require stripe/stripe-php
// ============================================================

use App\Models\Pago;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeService
{
    private StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Crea una Checkout Session de Stripe para pagar la matrícula
     * asociada a un Pago (tbl_pago) ya existente con estado PENDIENTE.
     *
     * Devuelve la URL a la que se debe redirigir al postulante.
     */
    public function crearSesionCheckout(Pago $pago, string $successUrl, string $cancelUrl): string
    {
        $montoUsd = (float) config('services.stripe.matricula_usd', 10.00);
        $moneda   = config('services.stripe.currency', 'usd');

        $session = $this->client->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => $moneda,
                    'unit_amount'  => (int) round($montoUsd * 100), // centavos
                    'product_data' => [
                        'name'        => 'Matrícula de Admisión - FICCT',
                        'description' => 'Pago de matrícula (Bs ' . number_format((float) $pago->num_monto, 2) . ')',
                    ],
                ],
                'quantity' => 1,
            ]],
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => $cancelUrl,
            // Referencia para identificar el pago desde el webhook
            'client_reference_id' => (string) $pago->id_inscripcion,
            'metadata' => [
                'id_inscripcion' => (string) $pago->id_inscripcion,
                'id_pago'        => (string) $pago->id_pago,
            ],
        ]);

        // Guardamos el session_id temporalmente en txt_referencia
        // para poder consultar el estado desde la página de retorno
        // antes de que llegue el webhook.
        $pago->update([
            'txt_referencia' => $session->id,
        ]);

        return $session->url;
    }

    /**
     * Verifica la firma del webhook y devuelve el evento de Stripe.
     *
     * @throws \UnexpectedValueException|\Stripe\Exception\SignatureVerificationException
     */
    public function construirEventoWebhook(string $payload, string $signatureHeader): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $signatureHeader,
            config('services.stripe.webhook_secret')
        );
    }

    /**
     * Marca como APROBADO el Pago correspondiente a una Checkout
     * Session completada. Idempotente: si ya estaba APROBADO, no
     * hace nada (Stripe puede reenviar el mismo evento).
     */
    public function confirmarPagoPorSesion(\Stripe\Checkout\Session $session): void
    {
        $idInscripcion = $session->metadata->id_inscripcion ?? $session->client_reference_id ?? null;

        if (! $idInscripcion) {
            return;
        }

        $pago = Pago::where('id_inscripcion', $idInscripcion)->first();

        if (! $pago || $pago->txt_estado === 'APROBADO') {
            return; // no encontrado o ya procesado (idempotencia)
        }

        $pago->update([
            'txt_estado'     => 'APROBADO',
            'txt_metodo'     => 'TARJETA DE DEBITO',
            'txt_referencia' => $session->payment_intent ?? $session->id,
            'fch_pago'       => now(),
        ]);
    }

    /**
     * Consulta el estado actual de una Checkout Session directamente
     * en Stripe (usado por la página de retorno para mostrar el
     * resultado sin esperar al webhook).
     */
    public function obtenerEstadoSesion(string $sessionId): \Stripe\Checkout\Session
    {
        return $this->client->checkout->sessions->retrieve($sessionId);
    }
}
