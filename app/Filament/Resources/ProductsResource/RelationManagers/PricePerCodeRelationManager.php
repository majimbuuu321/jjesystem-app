<?php

namespace App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\PriceCode;
use App\Models\UnitOfMeasurement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
class PricePerCodeRelationManager extends RelationManager
{
    protected static string $relationship = 'PricePerCode';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            DatePicker::make('price_date')
                                ->label('Price Date')
                                ->required()
                                ->maxDate(now()),
                        ]),
                    Grid::make(3)
                        ->schema([
                            TextInput::make('unit_price')
                                ->label('Unit Price')
                                ->required(),

                            Select::make('unit_of_measurement_id')
                                ->label('Unit of Measurement')
                                ->preload()
                                ->options(UnitOfMeasurement::where('is_active', 1)->where('deleted_at', null)->pluck('unit_code', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Unit of Measurement...'),

                            Select::make('price_code_id')
                                ->label('Price Code')
                                ->preload()
                                ->options(PriceCode::where('is_active', 1)->where('deleted_at', null)->pluck('price_code', 'id'))
                                ->searchable()
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->loadingMessage('Loading Price Code...'),
                           
                        ]),
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                TextColumn::make('price_date')
                    ->label('Price Date')
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('UOM.unit_code')
                    ->label('Unit of Measurement')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('priceCode.price_code')
                    ->label('Price Code')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->after(function ($livewire, $record) {
                    $record->created_by = auth()->id();
                    $record->save();
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->after(function ($livewire, $record) {
                    $record->updated_by = auth()->id();
                    $record->save();
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
