<?php

namespace App\Filament\Resources\BusinessChannelResource\Pages;

use App\Filament\Resources\BusinessChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
class EditBusinessChannel extends EditRecord
{
    protected static string $resource = BusinessChannelResource::class;

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
                ->title('Business Channel is successfully updated.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }
}
