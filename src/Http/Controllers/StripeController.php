<?php

namespace ValentinMorice\FilamentDonation\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use ValentinMorice\FilamentDonation\Models\Payment;

class StripeController
{
    /**
     * @throws ApiErrorException
     */
    public function index(Request $request, StripeClient $client)
    {
        $data = $request->get('data')['object']['object'];

        switch ($data['object']['object']) {
            case 'invoice':
                $invoice = $client->invoices->retrieve($data['object']['id']);

                Payment::updateOrCreate(
                    ['id' => $invoice->id],
                    [
                        'amount' => $invoice->total,
                        'currency' => $invoice->currency,
                        'status' => $invoice->status,
                        'donor_id' => $invoice->metadata['donor_id'],
                        'metadata' => [
                            'customer_id' => $invoice->customer->id,
                        ],
                    ]
                );
                return response('Invoice processed successfully', 200);
            default:
                return response('Default', 200);

        }
    }
}
