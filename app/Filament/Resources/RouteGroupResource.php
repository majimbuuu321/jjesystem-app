<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RouteGroupResource\Pages;
use App\Filament\Resources\RouteGroupResource\RelationManagers;
use App\Models\RouteGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
class RouteGroupResource extends Resource
{
    protected static ?string $model = RouteGroup::class;

    protected static ?string $navigationIcon = 'heroicon-m-tag';
    protected static ?string $navigationGroup = 'Route & Warehouse Management';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('route_group_name')
                            ->required()
                            ->label('Route Group Name')
                            ->unique(ignoreRecord:true)
                            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                        
                        ToggleButtons::make('is_active')
                            ->boolean()
                            ->required()
                            ->label('Status')
                            ->inline(true) 
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('route_group_name')
                    ->searchable(),
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
                    RouteGroup::where('id', $record->id)->update([
                        'deleted_by' => auth()->user()->id,
                    ]);
                }),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make()
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
            'index' => Pages\ListRouteGroups::route('/'),
            'create' => Pages\CreateRouteGroup::route('/create'),
            'edit' => Pages\EditRouteGroup::route('/{record}/edit'),
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
