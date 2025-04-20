<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customers;
use App\Models\Employee;
use App\Models\PaymentTerms;
use App\Models\PriceCode;
use App\Models\BusinessChannel;
use App\Models\Routes;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;
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
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\DB;
use Closure;

class CustomerResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-plus';
    protected static ?string $navigationGroup = 'Customer Management';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Customer Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('employee_id')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Sales Representative')
                                    ->options(Employee::select(
                                        DB::raw("CONCAT(first_name, ' ' ,last_name) AS name"), 'id')
                                        ->where('is_active', 1)
                                        ->pluck('name', 'id'))
                                    ->loadingMessage('Loading Sales Representative...'),

                                TextInput::make('store_name')
                                    ->label('Store Name')
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

                            Grid::make(4)
                            ->schema([
                                Select::make('price_code_id')
                                ->label('Price Code')
                                ->preload()
                                ->options(PriceCode::where('is_active', 1)->where('deleted_at', null)->pluck('price_code', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Price Code...'),

                                Select::make('business_channel_id')
                                ->label('Business Channel')
                                ->preload()
                                ->options(BusinessChannel::where('is_active', 1)->where('deleted_at', null)->pluck('business_channel_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Business Channel...'),

                                Select::make('payment_terms_id')
                                ->label('Payment Terms')
                                ->preload()
                                ->options(PaymentTerms::where('is_active', 1)->where('deleted_at', null)->pluck('payment_terms', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Price Code...'),

                                Select::make('route_id')
                                ->label('District (Route)')
                                ->preload()
                                ->options(Routes::where('is_active', 1)->where('deleted_at', null)->pluck('route_name', 'id'))
                                ->searchable()
                                ->required()
                                ->loadingMessage('Loading Price Code...'),
                            ]),

                            Grid::make(1)
                            ->schema([
                                TextInput::make('street_unit_building_no')
                                    ->label('Street/Unit/Building No.')
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                            ]),

                            Grid::make(4)
                            ->schema([
                                Select::make('region_code')
                                    ->label('Region')
                                    ->options(Region::all()->pluck('region_name', 'region_code'))
                                    ->searchable()
                                    ->required()
                                    ->loadingMessage('Loading Region...')
                                    ->reactive() // Enables dynamic behavior
                                    ->afterStateUpdated(function ($state, callable $set, callable $get)
                                    {
                                        if($state == null || $state == '')
                                        {
                                            $state = null;
                                            $set('province_code', null);
                                            $set('city_code', null);
                                            $set('barangay_code', null);
                                        }
                                    }),
                            
                                Select::make('province_code')
                                    ->label('Province')
                                    ->options(function (callable $get) {
                                        $regionId = $get('region_code');
                                        
                                        if (!$regionId) {
                                            return [];
                                        }
                                        
                                        return Province::where('region_code', $regionId)->pluck('province_name', 'province_code');
                                    })
                                    ->reactive() // Enables dynamic behavior
                                    ->searchable()
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get)
                                    {
                                        if($state == null || $state == '')
                                        {
                                            $state = null;
                                            $set('city_code', null);
                                            $set('brgy_code', null);
                                        }
                                      
                                    })
                                    ->loadingMessage('Loading Province...'),    

                                    Select::make('city_code')
                                    ->label('City/Municipality')
                                    ->options(function (callable $get) {
                                        $provinceId = $get('province_code');
                                        
                                        if (!$provinceId) {
                                            return [];
                                        }
                                        
                                        return City::where('province_code', $provinceId)->pluck('city_name', 'city_code');
                                    })
                                    ->searchable()
                                    ->reactive() // Enables dynamic behavior
                                    ->required()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get)
                                    {

                                        if($state == null || $state == '')
                                        {
                                            $state = null;
                                            $set('brgy_code', null);
                                        }
                                      
                                    })
                                    ->loadingMessage('Loading City/Municipality...'),

                                    Select::make('brgy_code')
                                    ->label('Barangay')
                                    ->options(function (callable $get) {
                                        $cityId = $get('city_code');
                                        
                                        if (!$cityId) {
                                            return [];
                                        }
                                        
                                        return Barangay::where('city_code', $cityId)->pluck('brgy_name', 'brgy_code');
                                    })
                                    ->searchable()
                                    ->reactive() // Enables dynamic behavior
                                    ->required()
                                    ->loadingMessage('Loading Barangay...'),
                            ]),

                            Grid::make(2)
                            ->schema([
                                TextInput::make('contact_number')
                                    ->label('Contact No.')
                                    ->required()
                                    ->prefix('+63')
                                    ->maxLength(10)
                                    ->tel(),

                                TextInput::make('priority_level')
                                    ->label('Priority Level (1,2,3)')
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(3)
                            ]),

                            ToggleButtons::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->required()
                            ->inline(true)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('store_name')
                    ->label('Store Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn ($record) => $record->first_name . ' ' . $record->last_name),

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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
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
