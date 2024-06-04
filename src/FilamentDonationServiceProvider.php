<?php

namespace ValentinMorice\FilamentDonation;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Stripe\StripeClient;
use ValentinMorice\FilamentDonation\Actions\Stripe\CreatePaymentAction;
use ValentinMorice\FilamentDonation\Actions\Stripe\CreateSubscriptionAction;
use ValentinMorice\FilamentDonation\Commands\FilamentDonationCommand;
use ValentinMorice\FilamentDonation\Http\Controllers\StripeController;
use ValentinMorice\FilamentDonation\Http\Middleware\EnsureStripeWebhookIsValid;
use ValentinMorice\FilamentDonation\Testing\TestsFilamentDonation;

class FilamentDonationServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-donation';

    public static string $viewNamespace = 'filament-donation';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('valentin-morice/filament-donation');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        Route::prefix('filament-donation')->group(function () {
            Route::post('/stripe/webhooks', [StripeController::class, 'index'])
                ->middleware(EnsureStripeWebhookIsValid::class);
        });
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-donation/{$file->getFilename()}"),
                ], 'filament-donation-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentDonation());

        // Instantiate Stripe Client
        $this->app->singleton(StripeClient::class, function () {
            return new StripeClient(config('filament-donation.stripe.secret_key'));
        });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'valentin-morice/filament-donation';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-donation', __DIR__ . '/../resources/dist/components/filament-donation.js'),
            Css::make('filament-donation-styles', __DIR__ . '/../resources/dist/filament-donation.css'),
            Js::make('filament-donation-scripts', __DIR__ . '/../resources/dist/filament-donation.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentDonationCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_donors_table',
            'create_payments_table',
            'create_subscriptions_table',
        ];
    }
}
