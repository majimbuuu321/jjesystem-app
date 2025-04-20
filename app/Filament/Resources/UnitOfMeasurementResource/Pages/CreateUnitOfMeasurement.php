<?php

namespace App\Filament\Resources\UnitOfMeasurementResource\Pages;

use App\Filament\Resources\UnitOfMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateUnitOfMeasurement extends CreateRecord
{
    protected static string $resource = UnitOfMeasurementResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Unit of Measurement is successfully created.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

}
