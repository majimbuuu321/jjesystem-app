<?php

namespace App\Filament\Resources\UnitOfMeasurementResource\Pages;

use App\Filament\Resources\UnitOfMeasurementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
class EditUnitOfMeasurement extends EditRecord
{
    protected static string $resource = UnitOfMeasurementResource::class;

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
                ->title('Unit of Measurement is successfully updated.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }
}
