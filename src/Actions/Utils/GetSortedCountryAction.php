<?php

namespace ValentinMorice\FilamentDonation\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class GetSortedCountryAction
{
    public function handle(): array
    {
        $json = File::json(__DIR__ . '/../../resources/json/Countries.json');
        $collect = new Collection($json);
        $arr = [];

        $collect->each(function ($item, $key) use (&$arr) {
            $arr[$item['abbreviation']] = $item['country'];
        });

        return $arr;
    }
}
