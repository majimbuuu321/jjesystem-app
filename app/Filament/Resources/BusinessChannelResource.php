<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessChannelResource\Pages;
use App\Filament\Resources\BusinessChannelResource\RelationManagers;
use App\Models\BusinessChannel;
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
class BusinessChannelResource extends Resource
{
    protected static ?string $model = BusinessChannel::class;

    protected static ?string $navigationIcon = 'heroicon-m-building-storefront';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
               Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('business_channel_name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Business Channel')
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
                TextColumn::make('business_channel_name')
                    ->label('Business Channel')
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
                    // Perform any additional actions before deletion
                    BusinessChannel::where('id', $record->id)->update([
                        'deleted_by' => auth()->user()->id,
                    ]);
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessChannels::route('/'),
            'create' => Pages\CreateBusinessChannel::route('/create'),
            'edit' => Pages\EditBusinessChannel::route('/{record}/edit'),
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
