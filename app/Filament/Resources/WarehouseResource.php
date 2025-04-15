<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Filament\Resources\WarehouseResource\RelationManagers;
use App\Models\Warehouse;
use App\Models\Employee;
use App\Models\Routes;
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
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Textarea;
class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-m-building-storefront';
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                                ->loadingMessage('Loading Sales Representative...'),

                                TextInput::make('warehouse_name')
                                    ->required()
                                    ->label('Warehouse Name')
                                    ->unique(ignoreRecord:true)
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('warehouse_type_id')
                                ->label('Warehouse Type')
                                ->preload()
                                ->options(WarehouseType::where('is_active', 1)->pluck('warehouse_type_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Warehouse Type...'),

                                 Select::make('route_id')
                                ->label('Routes')
                                ->preload()
                                ->options(Routes::where('is_active', 1)->pluck('route_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Routes...'),
                            ]),
                        
                        Grid::make(1)
                            ->schema([
                                TextArea::make('warehouse_address')
                                    ->required()
                                    ->label('Warehouse Address')
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
                TextColumn::make('employee.first_name')
                    ->formatStateUsing(function ($state, Warehouse $route) {
                        return $route->employee->first_name . ' ' . $route->employee->last_name;
                    })
                    ->label('Sales Representative')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('warehouse_name')
                    ->label('Warehouse Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('route.route_name')
                    ->label('Route Name')
                    ->sortable()
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
                    Warehouse::where('id', $record->id)->update([
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
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
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
