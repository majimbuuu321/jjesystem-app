<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

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
                ->title('Product Category is successfully updated.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }
}
