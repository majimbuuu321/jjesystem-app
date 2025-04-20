<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseTypeResource\Pages;
use App\Filament\Resources\WarehouseTypeResource\RelationManagers;
use App\Models\WarehouseType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
class WarehouseTypeResource extends Resource
{
    protected static ?string $model = WarehouseType::class;

    protected static ?string $navigationIcon = 'heroicon-m-tag';
    protected static ?string $navigationGroup = 'Route & Warehouse Management';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                        ->schema([
                            TextInput::make('warehouse_type_name')
                            ->required()
                            ->label('Warehouse Type Name')
                            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                        ]),
                        ToggleButtons::make('is_active')
                        ->label('Status')
                        ->boolean()
                        ->inline()
                        ->required()

                    ]),
               
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('warehouse_type_name')
                    ->searchable()
                    ->label('Warehouse Type Name'),
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
                    WarehouseType::where('id', $record->id)->update([
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
            'index' => Pages\ListWarehouseTypes::route('/'),
            'create' => Pages\CreateWarehouseType::route('/create'),
            'edit' => Pages\EditWarehouseType::route('/{record}/edit'),
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
