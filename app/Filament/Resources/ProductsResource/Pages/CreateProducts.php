<?php

namespace App\Filament\Resources\ProductsResource\Pages;
use App\Models\PriceHistory;
use App\Models\CostHistory;
use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateProducts extends CreateRecord
{
    protected static string $resource = ProductsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        CostHistory::create([
            'price_date' => $this->record->price_date,
            'product_id' => $this->record->id,
            'unit_cost' => $this->record->unit_cost,
            'created_by' => auth()->id(),
            'created_at' => now()
        ]);
    }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }

    protected function getCreatedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Product Information is successfully created.');
    }
}
