<?php

namespace ValentinMorice\FilamentDonation\Resources\DonorResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use ValentinMorice\FilamentDonation\Resources\DonorResource;

class ListDonors extends ListRecords
{
    protected static string $resource = DonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
