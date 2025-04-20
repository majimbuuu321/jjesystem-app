<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentTermsResource\Pages;
use App\Filament\Resources\PaymentTermsResource\RelationManagers;
use App\Models\PaymentTerms;
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
class PaymentTermsResource extends Resource
{
    protected static ?string $model = PaymentTerms::class;

    protected static ?string $navigationIcon = 'heroicon-m-credit-card';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('payment_terms')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Payment Terms')
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
                TextColumn::make('payment_terms')
                    ->label('Payment Terms')
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
            'index' => Pages\ListPaymentTerms::route('/'),
            'create' => Pages\CreatePaymentTerms::route('/create'),
            'edit' => Pages\EditPaymentTerms::route('/{record}/edit'),
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
