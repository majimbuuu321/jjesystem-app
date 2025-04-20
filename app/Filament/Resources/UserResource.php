<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;
use Filament\Support\RawJs;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')
                    ->schema([
                        Select::make('name')
                            ->label('Employee Name')
                            ->preload()
                            ->options(Employee::select(
                                DB::raw("CONCAT(ifnull(first_name, ''), ' ', ifnull(middle_name, ''), ' ' ,ifnull(last_name, '')) AS name"))
                                ->where('is_active', 1)
                                ->pluck('name', 'name'))
                            ->searchable()
                            ->required(),

                        // Select::make('name')
                        //     ->label('Employee Name')
                        //     ->preload()
                        //     ->options(Employee::select(
                        //         DB::raw("CONCAT(first_name, ' ' , middle_name, ' ' ,last_name) AS name"))
                        //         ->where('is_active', 1)
                        //         ->pluck('name', 'name'))
                        //     ->searchable()
                        //     ->required(),
                            
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->maxLength(255)
                            ->required()
                            ->revealable()
                            ->visibleOn('create'),
                        
                            Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable()
                    ->label('Full Name')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->dateTime('M d, Y h:m A')
                    ->label('Created Date')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('M d, Y h:m A')
                    ->sortable()
                    ->label('Updated Date'),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
