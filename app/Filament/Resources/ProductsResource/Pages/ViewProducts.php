<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use App\Filament\Resources\ProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ProductsResource\RelationManagers;
use Filament\Resources\RelationManagers\RelationGroup;
class ViewProducts extends ViewRecord
{
    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Edit Product'),
        ];
    }

    protected function getAllRelationManagers(): array
    {
        return [
            RelationGroup::make('Price per Code', [
                RelationManagers\PricePerCodeRelationManager::class,
            ]),
            RelationGroup::make('Cost History', [
                RelationManagers\CostHistoryRelationManager::class,
            ]),
        ];
    }
}
