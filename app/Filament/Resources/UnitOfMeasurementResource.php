<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitOfMeasurementResource\Pages;
use App\Filament\Resources\UnitOfMeasurementResource\RelationManagers;
use App\Models\UnitOfMeasurement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
class UnitOfMeasurementResource extends Resource
{
    protected static ?string $model = UnitOfMeasurement::class;

    protected static ?string $navigationIcon = 'heroicon-m-scale';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 6;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('unit_code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Unit Code')
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                                TextInput::make('unit_name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Unit Name')
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            ]),
                        ToggleButtons::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->required()
                            ->inline(true)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('unit_code')
                    ->label('Unit Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_name')
                    ->label('Unit Name')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function ($record) {
                    // Perform any additional actions before deletion
                    BusinessChannel::where('id', $record->id)->update([
                        'deleted_by' => auth()->user()->id,
                    ]);
                }),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnitOfMeasurements::route('/'),
            'create' => Pages\CreateUnitOfMeasurement::route('/create'),
            'edit' => Pages\EditUnitOfMeasurement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
