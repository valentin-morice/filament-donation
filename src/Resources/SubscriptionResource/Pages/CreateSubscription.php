<?php

namespace ValentinMorice\FilamentDonation\Resources\SubscriptionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use ValentinMorice\FilamentDonation\Resources\SubscriptionResource;

class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['amount'] *= 100;

        return $data;
    }
}
