<?php

namespace ValentinMorice\FilamentDonation\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class GetSortedPhoneCodeAction
{
    public function handle(): array
    {
        $json = File::json(__DIR__ . '/../../resources/json/PhoneCode.json');
        $collect = new Collection($json);
        $arr = [];

        $collect->each(function (array $country) use (&$arr) {
            $arr[] = $country['dial_code'];
        });

        sort($arr, SORT_NUMERIC);

        $prepared = [];

        foreach (array_unique($arr) as $value) {
            $prepared[$value] = $value;
        }

        return $prepared;
    }
}
