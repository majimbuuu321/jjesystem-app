<?php

namespace App\Filament\Resources\BusinessChannelResource\Pages;

use App\Filament\Resources\BusinessChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateBusinessChannel extends CreateRecord
{
    protected static string $resource = BusinessChannelResource::class;

    protected function getCreatedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Business Channel is successfully created.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
