<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Models\Products;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Warehouse;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationGroup;
class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $navigationIcon = 'heroicon-m-clipboard-document-check';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('product_code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Product Code')
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            ]),
                        Grid::make(1)
                            ->schema([
                                TextArea::make('product_description')
                                    ->required()
                                    ->label('Product Description')
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            ]),
                        Grid::make(4)
                            ->schema([
                                
                                DatePicker::make('price_date')
                                ->label('Price Date')
                                ->required()
                                ->minDate(now()->subYears(150)),

                                TextInput::make('unit_ctn')
                                    ->required()
                                    ->label('Unit/CTN')
                                    ->integer()
                                    ->minValue(1),
                                
                                TextInput::make('unit_cost')
                                    ->label('Unit Cost')
                                    ->required()
                                    ->numeric()
                                    ->inputMode('decimal'),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->required()
                                    ->numeric()
                                    ->inputMode('decimal'),
                            ]),

                            Grid::make(2)
                            ->schema([
                                Select::make('product_category_id')
                                    ->label('Product Category')
                                    ->options(ProductCategory::all()->pluck('product_category_name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->loadingMessage('Loading Product Category...')
                                    ->reactive() // Enables dynamic behavior
                                    ->afterStateUpdated(function ($state, callable $set, callable $get)
                                    {
                                        
                                        if($state == null || $state == '')
                                        {
                                            $state = null;
                                            $set('sub_category_id', null);
                                        }
                                        else{
                                            $set('sub_category_id', null);
                                        }
                                    }),

                                Select::make('sub_category_id')
                                    ->label('Sub Category')
                                    ->options(function (callable $get) {
                                        $prodCategoryId = $get('product_category_id');
                                        
                                        if (!$prodCategoryId) {
                                            return [];
                                        }
                                        
                                        return SubCategory::where('product_category_id', $prodCategoryId)->pluck('sub_category_name', 'id');
                                    })
                                    ->reactive() // Enables dynamic behavior
                                    ->searchable()
                                    ->required()
                                    ->loadingMessage('Loading Sub Category...'),  
                            ]),

                            Grid::make(2)
                            ->schema([

                                Select::make('supplier_id')
                                ->label('Supplier')
                                ->preload()
                                ->options(Supplier::where('is_active', 1)->pluck('company_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Supplier...'),

                                Select::make('warehouse_id')
                                ->label('Warehouse Location')
                                ->preload()
                                ->options(Warehouse::where('is_active', 1)->pluck('warehouse_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Warehouse...'),
                                 
                            ]),

                            Grid::make(2)
                            ->schema([

                                TextInput::make('reorder_level')
                                    ->label('Reorder Level')
                                    ->required()
                                    ->integer()
                                    ->minValue(1),

                                TextInput::make('weight')
                                    ->label('Weight (kgs)')
                                    ->numeric()
                                    ->inputMode('decimal'),
                                 
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
                TextColumn::make('product_code')
                    ->label('Product Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('product_description')
                    ->label('Product Description')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
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
            RelationGroup::make('Price per Code', [
                RelationManagers\PricePerCodeRelationManager::class,
            ]),
            RelationGroup::make('Cost History', [
                RelationManagers\CostHistoryRelationManager::class,
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
            'view' => Pages\ViewProducts::route('/{record}'),
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
