<?php

namespace ValentinMorice\FilamentDonation\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use ValentinMorice\FilamentDonation\Actions\Utils\GetSortedCurrencyAction;
use ValentinMorice\FilamentDonation\Enums\SubscriptionStatusEnum;
use ValentinMorice\FilamentDonation\Models\Donor;
use ValentinMorice\FilamentDonation\Models\Subscription;
use ValentinMorice\FilamentDonation\Resources\SubscriptionResource\Pages;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\DatePicker::make('created_at')
                            ->required()
                            ->hint('Format: DD/MM/YYYY')
                            ->native(false)
                            ->displayFormat('d / m / Y')
                            ->closeOnDateSelection()
                            ->default(Carbon::now()->toDateTimeString()),
                        Forms\Components\TextInput::make('amount')
                            ->formatStateUsing(function (?int $state) {
                                return number_format($state / 100, 2);
                            })
                            ->required(),
                        Forms\Components\Select::make('currency')
                            ->searchable()
                            ->options(fn (GetSortedCurrencyAction $action) => $action->handle()),
                    ])
                    ->columns(3),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->live()
                            ->options(SubscriptionStatusEnum::class),
                        Forms\Components\Select::make('donor_id')
                            ->label('Donor')
                            ->options(Donor::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->hint(function (string $operation) {
                                if ($operation === 'create') {
                                    return new HtmlString('If the donor does not exist, please create it<a style="text-decoration: underline;" href="' . url('/admin/donors/create') . '"> here</a>.');
                                }

                                return '';
                            })
                            ->required(),
                        Forms\Components\DatePicker::make('cancelled_at')
                            ->required(function (Forms\Get $get) {
                                return $get('status') === 'cancelled';
                            })
                            ->label('Cancelled'),
                    ])
                    ->columns(3),
                Forms\Components\Fieldset::make('Metadata')
                    ->schema([
                        Forms\Components\TextInput::make('metadata.customer_id')
                            ->default(uniqid())
                            ->formatStateUsing(fn ($state) => $state ? $state : uniqid())
                            ->label('Customer ID')
                            ->required()
                            ->hint('This ID is generated automatically.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('id')
                            ->default(uniqid())
                            ->formatStateUsing(fn ($state) => $state ? $state : uniqid())
                            ->required()
                            ->label('Subscription ID')
                            ->hint('This ID is generated automatically.'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
