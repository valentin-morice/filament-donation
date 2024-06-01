<?php

namespace ValentinMorice\FilamentDonation\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use ValentinMorice\FilamentDonation\Actions\Utils\GetSortedCurrencyAction;
use ValentinMorice\FilamentDonation\Enums\PaymentStatusEnum;
use ValentinMorice\FilamentDonation\Models\Donor;
use ValentinMorice\FilamentDonation\Models\Payment;
use ValentinMorice\FilamentDonation\Resources\PaymentResource\Pages;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

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
                            ->required()
                            ->formatStateUsing(function (?int $state) {
                                return number_format($state / 100, 2);
                            }),
                        Forms\Components\Select::make('currency')
                            ->required()
                            ->searchable()
                            ->options(fn (GetSortedCurrencyAction $action) => $action->handle()),
                    ])->columns(3),
                Forms\Components\Select::make('status')
                    ->options(PaymentStatusEnum::class)
                    ->required(),
                Forms\Components\Select::make('donor_id')
                    ->options(Donor::all()->pluck('name', 'id'))
                    ->label('Donor')
                    ->required()
                    ->searchable()
                    ->hint(function (string $operation) {
                        if ($operation === 'create') {
                            return new HtmlString('If the donor does not exist, please create it<a style="text-decoration: underline;" href="' . url('/admin/donors/create') . '"> here</a>.');
                        }

                        return '';
                    })
                    ->required(),
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
                            ->label('Payment ID')
                            ->hint('This ID is generated automatically.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->color('gray')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Created')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->label('Updated')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn (int $state) => number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('donor.name')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Payment $record) => '/admin/donors/' . $record->donor->id . '/edit'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
