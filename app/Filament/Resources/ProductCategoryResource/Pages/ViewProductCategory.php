<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
class ViewProductCategory extends ViewRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Edit Product Category'),
        ];
    }

    protected function getAllRelationManagers(): array
    {
        return [
            RelationManagers\SubCategoryRelationManager::class,
        ];
    }
}
