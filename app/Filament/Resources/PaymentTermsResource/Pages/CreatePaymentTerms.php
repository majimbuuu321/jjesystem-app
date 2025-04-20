<?php

namespace App\Filament\Resources\PaymentTermsResource\Pages;

use App\Filament\Resources\PaymentTermsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreatePaymentTerms extends CreateRecord
{
    protected static string $resource = PaymentTermsResource::class;

    protected function getCreatedNotification(): ?Notification {
        return Notification::make()
                 ->success()
                ->title('Payment Terms is successfully created.');
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
