<?php

return [

    /**
     * Reference your Stripe credentials here.
     */
    'stripe' => [
        'webhook_secret' => env('FD_STRIPE_WEBHOOK_SECRET'),
        'publishable_key' => env('FD_STRIPE_PUBLISHABLE_KEY'),
        'secret_key' => env('FD_STRIPE_SECRET_KEY'),
    ],

];
