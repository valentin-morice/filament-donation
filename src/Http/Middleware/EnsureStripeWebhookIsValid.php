<?php

namespace ValentinMorice\FilamentDonation\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStripeWebhookIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->header('Stripe-Signature')) {
            return \response('Error verifying webhook signature.', 400);
        }

        try {
            \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                'STRIPE_WEBHOOK_SECRET' // TODO: Query secret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return \response('Error verifying webhook signature.', 400);
        }

        return $next($request);
    }
}
