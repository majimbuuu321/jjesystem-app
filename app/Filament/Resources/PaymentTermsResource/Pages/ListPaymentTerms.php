<?php

namespace App\Filament\Resources\PaymentTermsResource\Pages;

use App\Filament\Resources\PaymentTermsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentTerms extends ListRecords
{
    protected static string $resource = PaymentTermsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
