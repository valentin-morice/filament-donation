<?php

namespace ValentinMorice\FilamentDonation\Resources\DonorResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use ValentinMorice\FilamentDonation\Resources\DonorResource;

class EditDonor extends EditRecord
{
    protected static string $resource = DonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
