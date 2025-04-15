<?php

namespace App\Filament\Resources\WarehouseTypeResource\Pages;

use App\Filament\Resources\WarehouseTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseTypes extends ListRecords
{
    protected static string $resource = WarehouseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
