<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Product Category is successfully created.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
