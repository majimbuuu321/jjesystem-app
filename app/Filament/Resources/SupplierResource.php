<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-m-building-office-2';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 10;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('company_name')
                                ->required()
                                ->label('Company Name')
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            
                           
                        ]),
                    
                    Grid::make(3)
                        ->schema([
                            TextInput::make('first_name')
                                ->required()
                                ->label('First Name')
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            TextInput::make('middle_name')
                                ->label('Middle Name')
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            TextInput::make('last_name')
                                ->required()
                                ->label('Last Name')
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                        ]),
                    Grid::make(1)
                        ->schema([
                            Textarea::make('supplier_address')
                                ->required()
                                ->label('Supplier Address')
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                        ]),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->extraInputAttributes(['onInput' => 'this.value = this.value.toLowerCase()']),
                            TextInput::make('contact_number')
                                ->required()
                                ->label('Contact Number')
                                ->tel()
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
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('full_name')
                    ->label('Field Supervisor')
                    ->getStateUsing(fn ($record) => $record->first_name . ' ' . $record->middle_name . ' ' . $record->last_name),

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
                    Employee::where('id', $record->id)->update([
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
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
