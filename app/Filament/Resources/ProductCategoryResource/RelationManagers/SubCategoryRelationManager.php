<?php

namespace App\Filament\Resources\ProductCategoryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;

class SubCategoryRelationManager extends RelationManager
{
    protected static string $relationship = 'SubCategory';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Forms\Components\Hidden::make('focus_blocker')->dehydrated(false),
                    
                    TextInput::make('sub_category_name')
                    ->required()
                    // ->unique(ignoreRecord: true)
                    ->label('Sub Category')
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                ]),
                ToggleButtons::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->required()
                            ->inline(true)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sub_category_name')
                    ->label('Sub Category')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->after(function ($livewire, $record) {
                    $record->created_by = auth()->id();
                    $record->save();
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->after(function ($livewire, $record) {
                    $record->updated_by = auth()->id();
                    $record->save();
                }),
                Tables\Actions\DeleteAction::make()
                ->after(function ($livewire, $record) {
                    $record->deleted_by = auth()->id();
                    $record->save();
                }),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }


}
