<?php

namespace ValentinMorice\FilamentDonation\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use ValentinMorice\FilamentDonation\Actions\GetSortedCountryAction;
use ValentinMorice\FilamentDonation\Actions\GetSortedPhoneCodeAction;
use ValentinMorice\FilamentDonation\Models\Donor;
use ValentinMorice\FilamentDonation\Resources\DonorResource\Pages;

class DonorResource extends Resource
{
    protected static ?string $model = Donor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->placeholder('John')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->label('First Name')
                            ->placeholder('Doe')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birth_date')
                            ->required()
                            ->hint('Format: DD/MM/YYYY')
                            ->native(false)
                            ->placeholder('01/01/2024')
                            ->displayFormat('d / m / Y')
                            ->closeOnDateSelection(),
                    ])->columns(3),
                Forms\Components\Fieldset::make('Contact Details')
                    ->schema([
                        Forms\Components\Select::make('phone_code')
                            ->options(function (GetSortedPhoneCodeAction $action) {
                                return $action->handle();
                            })
                            ->searchable()
                            ->label('Phone Code'),
                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->placeholder('john@example.com')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),
                Forms\Components\Fieldset::make('Address')->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('country')
                            ->required()
                            ->searchable()
                            ->options(function (GetSortedCountryAction $action) {
                                return $action->handle();
                            }),
                        Forms\Components\TextInput::make('city')
                            ->placeholder('Los Angeles')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->placeholder('California')
                            ->maxLength(255),
                    ])->columns(3),
                    Forms\Components\TextInput::make('address')
                        ->placeholder('8 Sesame Street')
                        ->required()
                        ->maxLength(255)
                        ->label('Street'),
                    Forms\Components\TextInput::make('postal_code')
                        ->placeholder('90001')
                        ->required()
                        ->maxLength(255),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->prefix(function (Donor $record) {
                        return $record->phone_code;
                    }),
                Tables\Columns\TextColumn::make('country'),
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
            'index' => Pages\ListDonors::route('/'),
            'create' => Pages\CreateDonor::route('/create'),
            'edit' => Pages\EditDonor::route('/{record}/edit'),
        ];
    }
}
