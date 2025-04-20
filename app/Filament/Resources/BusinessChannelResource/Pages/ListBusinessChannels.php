<?php

namespace App\Filament\Resources\BusinessChannelResource\Pages;

use App\Filament\Resources\BusinessChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessChannels extends ListRecords
{
    protected static string $resource = BusinessChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
