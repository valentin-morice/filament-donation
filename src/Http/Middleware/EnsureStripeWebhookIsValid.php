<?php

namespace ValentinMorice\FilamentDonation\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
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
            return \response('Error verifying webhook signature', 400);
        }

        try {
            Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('filament-donation.stripe.webhook_secret'),
            );
        } catch (SignatureVerificationException $e) {
            return \response('Error verifying webhook signature', 400);
        }

        return $next($request);
    }
}
