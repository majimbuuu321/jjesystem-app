<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers;
use App\Models\PurchaseOrderHeader;
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
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;
class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrderHeader::class;

    protected static ?string $navigationIcon = 'heroicon-m-document-plus';
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?string $navigationLabel = 'Purchase Orders';
    protected static ?string $label = 'Purchase Order';
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
                            DatePicker::make('received_date')
                            ->required()
                            ->label('Received Date')
                            ->minDate(now()->subYears(150))
                            ->maxDate(now())
                            ->default(now()),
                            
                            TextInput::make('invoice_no')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->label('Invoice No.'),
                            
                            Select::make('payment_terms_id')
                                ->relationship('paymentTerms', 'payment_terms')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->label('Payment Terms')
                                ->loadingMessage('Loading Payment Terms...'),
                        ]),
                    Grid::make(2)
                        ->schema([
                            Select::make('supplier_id')
                                ->relationship('supplier', 'company_name')
                                ->required()
                                ->preload()
                                ->searchable()
                                ->label('Delivery From')
                                ->loadingMessage('Loading Suppliers...'),
                            Select::make('warehouse_id')
                                ->relationship('warehouse', 'warehouse_name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->label('Delivery To')
                                ->loadingMessage('Loading Warehouses...'),
                            
                        ]),

                        ToggleButtons::make('is_posted')
                        ->label('Is Posted?')
                        ->boolean()
                        ->required()
                        ->inline(true)
                        ->visibleOn('edit'),
                        
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('invoice_no')
                    ->label('Invoice No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('received_date')
                    ->label('Received Date')
                    ->sortable(),
                TextColumn::make('supplier.company_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouse.warehouse_name')
                    ->label('Warehouse')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_posted')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
               Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(function ($record) {
                    // Display the "Edit" action only if status == 1
                    return $record->is_posted == 0;
                }),
                Html2MediaAction::make('print')
                    ->label('Print')
                    // ->savePdf()
                    // ->preview()
                    // ->orientation('landscape')
                    ->color('warning')
                    ->margin([10, 2, 0, 2])
                    ->icon('heroicon-o-printer')
                    ->filename(fn ($record) => 'JJE-PO-' . $record->id . '.pdf')
                    ->content(fn($record) => view('pdf.purchase_order', [
                        'record' => $record,
                        'items' => $record->PurchaseOrderDetail,
                        'products' => $record->PurchaseOrderDetail->map(fn($item) => $item->product),
                        'uom' => $record->PurchaseOrderDetail->map(fn($item) => $item->unitOfMeasurement),
                        // 'price_code' => $record->PurchaseOrderDetail->map(fn($item) => $item->priceCode),
                ])),
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
            RelationManagers\PurchaseOrderDetailRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
            'view' => Pages\ViewPurchaseOrder::route('/{record}'),
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
