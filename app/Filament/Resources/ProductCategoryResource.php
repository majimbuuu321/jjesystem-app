<?php

namespace App\Filament\Resources;
use App\Models\SubCategory;
use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-m-tag';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('product_category_name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Product Category')
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            ]),
                        ToggleButtons::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->required()
                            ->inline(true)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('product_category_name')
                    ->label('Product Category')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function ($record) {
                    $record->deleted_by = auth()->id();
                    $record->save();
                }),
                Tables\Actions\ForceDeleteAction::make()
                ->before(function ($record) {
                    SubCategory::where('product_category_id', $record->id)->forceDelete();
                }),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\SubCategoryRelationManager::class
        ];
    }

 

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
            'view' => Pages\ViewProductCategory::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
