<?php

namespace ValentinMorice\FilamentDonation\Actions\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;

class CreateSubscriptionAction
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
        $product = $this->create_product();
        $price = $this->create_price($amount, $currency, $product->id);

        return $this->client->subscriptions->create(
            [
                'customer' => $this->client->customers->create()->id,
                'items' => [['price' => $price->id]],
                'currency' => $price->currency,
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
            ],
        );
    }

    /**
     * @throws ApiErrorException
     */
    private function create_price(int $amount, string $currency, string $product_id): Price
    {
        return $this->client->prices->create(
            [
                'unit_amount' => $amount,
                'currency' => $currency,
                'recurring' => ['interval' => 'month'],
                'product' => $product_id,
            ],
        );
    }

    /**
     * @throws ApiErrorException
     */
    private function create_product(): Product
    {
        return $this->client->products->create([
            'name' => config('app.name') . '|' . uniqid(),
        ]);
    }
}
