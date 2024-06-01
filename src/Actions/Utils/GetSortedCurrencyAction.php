<?php

namespace ValentinMorice\FilamentDonation\Actions;

use Illuminate\Support\Facades\File;

class GetSortedCurrencyAction
{
    public function handle(): array
    {
        return File::json(__DIR__ . '/../../resources/json/Currencies.json');
    }
}
