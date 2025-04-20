<?php

namespace App\Filament\Resources\ProductsResource\Pages;
use App\Models\CostHistory;
use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
class EditProducts extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //         Actions\ForceDeleteAction::make(),
    //         Actions\RestoreAction::make(),
    //     ];
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Product Information is successfully updated.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }

    protected function afterSave(): void
    {
        CostHistory::create([
            'price_date' => $this->record->price_date,
            'product_id' => $this->record->id,
            'unit_cost' => $this->record->unit_cost,
            'created_by' => auth()->id(),
            'created_at' => now()
        ]);
    }
}
