<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceCodeResource\Pages;
use App\Filament\Resources\PriceCodeResource\RelationManagers;
use App\Models\PriceCode;
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

class PriceCodeResource extends Resource
{
    protected static ?string $model = PriceCode::class;

    protected static ?string $navigationIcon = 'heroicon-m-clipboard-document-list';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->schema([
                        Grid::make(3)
                        ->schema([
                            TextInput::make('price_code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Price Code')
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
                TextColumn::make('price_code')
                    ->searchable()
                    ->label('Price Code'),
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
                    PriceCode::where('id', $record->id)->update([
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
            'index' => Pages\ListPriceCodes::route('/'),
            'create' => Pages\CreatePriceCode::route('/create'),
            'edit' => Pages\EditPriceCode::route('/{record}/edit'),
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
