<?php

namespace ValentinMorice\FilamentDonation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ValentinMorice\FilamentDonation\FilamentDonation
 */
class FilamentDonation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ValentinMorice\FilamentDonation\FilamentDonation::class;
    }
}
