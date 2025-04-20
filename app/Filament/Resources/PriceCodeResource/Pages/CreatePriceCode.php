<?php

namespace App\Filament\Resources\PriceCodeResource\Pages;

use App\Filament\Resources\PriceCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreatePriceCode extends CreateRecord
{
    protected static string $resource = PriceCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Price Code is successfully created.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
