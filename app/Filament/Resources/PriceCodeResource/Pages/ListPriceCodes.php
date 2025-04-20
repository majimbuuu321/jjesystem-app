<?php

namespace App\Filament\Resources\PriceCodeResource\Pages;

use App\Filament\Resources\PriceCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriceCodes extends ListRecords
{
    protected static string $resource = PriceCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
