<?php

namespace ValentinMorice\FilamentDonation\Resources\PaymentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use ValentinMorice\FilamentDonation\Resources\PaymentResource;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['amount'] *= 100;

        return $data;
    }
}
