<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoutesResource\Pages;
use App\Filament\Resources\RoutesResource\RelationManagers;
use App\Models\Routes;
use App\Models\Employee;
use App\Models\RouteGroup;
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
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
class RoutesResource extends Resource
{
    protected static ?string $model = Routes::class;

    protected static ?string $navigationIcon = 'heroicon-m-globe-alt';
    protected static ?string $navigationGroup = 'Route & Warehouse Management';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                        ->schema([
                            Select::make('employee_id')
                                ->label('Sales Representative')
                                ->preload()
                                ->required()
                                ->searchable()
                                ->options(Employee::select(
                                    DB::raw("CONCAT(first_name, ' ' ,last_name) AS name"), 'id')
                                    ->where('is_active', 1)
                                    ->pluck('name', 'id'))
                                ->loadingMessage('Loading Sales Representative...')
                        ]),
                        Grid::make(2)
                        ->schema([
                            TextInput::make('route_name')
                                ->required()
                                ->label('Route Name')
                                ->unique(ignoreRecord:true)
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            Select::make('route_group_id')
                                ->label('Route Group')
                                ->preload()
                                ->options(RouteGroup::where('is_active', 1)->pluck('route_group_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Route Group...'),
                        ]),

                        ToggleButtons::make('is_active')
                        ->label('Is Active?')
                        ->inline()
                        ->label('Status')
                        ->boolean()
                        ->required()
                    ]),
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.first_name')
                    ->formatStateUsing(function ($state, Routes $route) {
                        return $route->employee->first_name . ' ' . $route->employee->last_name;
                    })
                    ->label('Sales Representative')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('route_name')
                    ->searchable()
                    ->label('Route Name')
                    ->sortable(),

                TextColumn::make('route_group.route_group_name')
                    ->searchable()
                    ->label('Route Group')
                    ->sortable(),
                
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->sortable(),

                
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
                    Routes::where('id', $record->id)->update([
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
            'index' => Pages\ListRoutes::route('/'),
            'create' => Pages\CreateRoutes::route('/create'),
            'edit' => Pages\EditRoutes::route('/{record}/edit'),
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
