<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Actions;
use App\Models\InventoryPerWarehouse;
use App\Models\StockMovements;
use Filament\Resources\Pages\EditRecord;
use Torgodly\Html2Media\Actions\Html2MediaAction;
use Filament\Notifications\Notification;
class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Html2MediaAction::make('print')
    //         ->label('Print')
    //         // ->savePdf()
    //         // ->preview()
    //         // ->orientation('landscape')
    //         ->margin([10, 2, 0, 2])
    //         ->icon('heroicon-o-printer')
    //         ->filename(fn ($record) => 'JJE-PO-' . $record->id . '.pdf')
    //         ->content(fn($record) => view('pdf.purchase_order', [
    //             'record' => $record,
    //             'items' => $record->PurchaseOrderDetail,
    //             'products' => $record->PurchaseOrderDetail->map(fn($item) => $item->product),
    //             'uom' => $record->PurchaseOrderDetail->map(fn($item) => $item->unitOfMeasurement),
    //             // 'price_code' => $record->PurchaseOrderDetail->map(fn($item) => $item->priceCode),
    //         ])),
    //     ];
    // }

    protected function afterSave(): void
    {
        // dd($record);
        // dd($this->record);
        // $oldStatus = $this->record->getOriginal('is_posted');
        // $newStatus = $this->record->is_posted;

        // dd($oldStatus . ' - ' . $newStatus);
        if ($this->record->is_posted == 1) {

            foreach($this->record->PurchaseOrderDetail as $item) {
                $productId = $item->products_id;
                $warehouseId = $this->record->warehouse_id;
                $quantity = $item->quantity;
                $uomId = $item->uom_id;

                // Check if the inventory record exists for the product and warehouse
                $inventory = InventoryPerWarehouse::where('product_id', $productId)
                    ->where('warehouse_id', $warehouseId)
                    ->first();
                if ($inventory) {
                    // Update the existing inventory record
                    $inventory->quantity += $quantity;
                    $inventory->save();
                } else {
                    // Create a new inventory record
                    InventoryPerWarehouse::create([
                        'product_id' => $productId,
                        'warehouse_id' => $warehouseId,
                        'quantity' => $quantity,
                        'uom_id' => $uomId,
                        'updated_at' => now(),
                    ]);
                }

                // Create a new stock movement record
                StockMovements::create([
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'uom_id' => $uomId,
                    'quantity' => $quantity,
                    'movement_type' => 'IN',
                    'reference_note' => $this->record->id . ' - Invoice No:' . $this->record->invoice_no,
                    'created_by' =>  auth()->user()->id,
                    'created_at' => now(),
                ]);
            }
            
            
        }
    }

    protected function getSavedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Purchase Order is successfully updated.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
