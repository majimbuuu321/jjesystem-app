<?php

namespace App\Filament\Resources\PurchaseOrderResource\RelationManagers;

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
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Resources\RelationManagers\RelationGroup;
class PurchaseOrderDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'PurchaseOrderDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Forms\Components\Hidden::make('focus_blocker')->dehydrated(false),
                    Select::make('products_id')
                        ->relationship('product', 'product_description')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->label('Product')
                        ->loadingMessage('Loading Products...'),
                ]),
                Grid::make(2)
                ->schema([

                    Select::make('uom_id')
                        ->relationship('unitOfMeasurement', 'unit_code')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->label('Unit of Measurement')
                        ->loadingMessage('Loading Unit of Measurement...'),

                    Select::make('price_code_id')
                        ->relationship('priceCode', 'price_code')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->label('Price Code')
                        ->loadingMessage('Loading Price Code...'),
                ]),
                Grid::make(4)
                ->schema([

                    TextInput::make('tag_weight')
                        ->numeric()
                        ->inputMode('decimal')
                        ->label('Tag Weight'),

                    TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->live(onBlur: true)
                        ->minValue(0)
                        ->label('Quantity')
                        ->afterStateUpdated(function ($state, callable $set, callable $get)
                            {
                                if($get('unit_cost') == null || $get('unit_cost') == 0){
                                    $set('unit_cost', null);
                                }
                                else{
                                    $set('total_cost', $state * $get('unit_cost'));
                                    $set('net_amount', $get('total_cost') - $get('discount_amount'));
                                }
                            }),
                    TextInput::make('unit_cost')
                        ->required()
                        ->numeric()
                        ->live(onBlur: true)
                        ->minValue(0)
                        ->label('Unit Cost')
                        ->afterStateUpdated(function ($state, callable $set, callable $get)
                            {
                                if($get('quantity') == null || $get('quantity') == 0){
                                    $set('quantity', null);
                                }
                                else{
                                    $set('total_cost', $state * $get('quantity'));
                                    $set('net_amount', $get('total_cost') - $get('discount_amount'));
                                }
                            }),

                    TextInput::make('total_cost')
                        ->numeric()
                        ->inputMode('decimal')
                        ->label('Total Cost')
                        ->readonly(),
                    
                ]),

                Grid::make(3)
                ->schema([
                    TextInput::make('discount_rate')
                        ->numeric()
                        ->inputMode('decimal')
                        ->live(onBlur: true)
                        ->label('Discount Rate (%)')
                        ->afterStateUpdated(function ($state, callable $set, callable $get)
                            {
                                if($get('total_cost') == null || $get('total_cost') == 0){
                                    $set('discount_amount', null);
                                    $set('net_amount', null);
                                }
                                else{
                                    $set('net_amount', $get('total_cost') - ($state / 100 * $get('total_cost')));
                                    $set('discount_amount', $state / 100 * $get('total_cost'));
                                }
                            }),

                    TextInput::make('discount_amount')
                        ->numeric()
                        ->inputMode('decimal')
                        ->label('Discount Amount')
                        ->readonly(),

                    TextInput::make('net_amount')
                        ->numeric()
                        ->inputMode('decimal')
                        ->label('Net Amount')
                        ->readonly(),
                ]),

                Grid::make(1)
                ->schema([
                    Textarea::make('remarks')
                        ->label('Remarks'),
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.product_description')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unitOfMeasurement.unit_code')
                    ->label('Unit of Measurement')
                    ->sortable(),
                TextColumn::make('priceCode.price_code')
                    ->label('Price Code')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                TextColumn::make('unit_cost')
                    ->label('Unit Cost')
                    ->money('PHP', true)
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Total Cost')
                    ->money('PHP', true)
                    ->sortable(),
                TextColumn::make('discount_rate')
                    ->label('Discount Rate (%)')
                    ->sortable(),
                TextColumn::make('discount_amount')
                    ->label('Discount Amount')
                    ->money('PHP', true)
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->label('Net Amount')
                    ->money('PHP', true)
                    ->sortable(),
                
                TextColumn::make('total_cost')
                    ->summarize(Sum::make()
                    ->label('Gross Amount')
                    ->money('PHP', true)
            ),

                TextColumn::make('discount_amount')
                    ->summarize(Sum::make()
                    ->label('Total Discount')
                    ->money('PHP', true)
                ),

                TextColumn::make('net_amount')
                    ->summarize(Sum::make()
                    ->label('Total Net Amount')
                    ->money('PHP', true)
                ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Add Product'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
