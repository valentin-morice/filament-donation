<?php

namespace ValentinMorice\FilamentDonation;

use Filament\Contracts\Plugin;
use Filament\Panel;
use ValentinMorice\FilamentDonation\Actions\GetSortedCountryAction;
use ValentinMorice\FilamentDonation\Resources\DonorResource;
use ValentinMorice\FilamentDonation\Resources\PaymentResource;
use ValentinMorice\FilamentDonation\Resources\SubscriptionResource;

class FilamentDonationPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-donation';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                DonorResource::class,
                PaymentResource::class,
                SubscriptionResource::class,
            ]);

        \Illuminate\Support\Facades\Route::get('/test1', function (GetSortedCountryAction $action) {
            return $action->handle();
        });
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
