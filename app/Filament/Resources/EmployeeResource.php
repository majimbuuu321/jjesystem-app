<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\IconColumn;
class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-circle';
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Employee Details')
                    ->schema([
                        Grid::make(3)
                        ->schema([
                            TextInput::make('employee_code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Employee Code'),
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
                            TextInput::make('address')
                            ->required()
                            ->label('Address')
                            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                        ]),
                        Grid::make(3)
                        ->schema([
                            Select::make('gender')
                            ->label('Gender')
                            ->searchable()
                            ->required()
                            ->options([
                                'MALE' => 'MALE',
                                'FEMALE' => 'FEMALE',
                            ]),
                            TextInput::make('contact_number')
                            ->required()
                            ->label('Contact Number'),
                            DatePicker::make('birth_date')
                            ->label('Birth Date')
                            ->required()
                            ->minDate(now()->subYears(150))
                            ->maxDate(now()),
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
                TextColumn::make('employee_code')
                    ->searchable()
                    ->label('Employee Code'),
                TextColumn::make('first_name')
                    ->searchable()
                    ->label('First Name')
                    ->sortable(),
                TextColumn::make('middle_name')
                    ->searchable()
                    ->label('Middle Name')
                    ->sortable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->label('Last Name')
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
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
