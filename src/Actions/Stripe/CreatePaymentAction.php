<?php

namespace ValentinMorice\FilamentDonation\Actions\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class CreatePaymentAction
{
    public function __construct(
        protected StripeClient $client,
    ) {
    }

    /**
     * @throws ApiErrorException
     */
    public function handle(int $amount, string $currency)
    {
        return $this->client->paymentIntents->create(
            [
                'amount' => $amount,
                'customer' => $this->client->customers->create()->id,
                'currency' => $currency,
            ],
        );
    }
}
