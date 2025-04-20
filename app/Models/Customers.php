<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customers extends Model
{
    //

    use SoftDeletes;
    
    protected $table = 'customers';
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'employee_id',
        'price_code_id',
        'payment_terms_id',
        'route_id',
        'business_channel_id',
        'store_name',
        'street_unit_building_no',
        'region_code',
        'province_code',
        'city_code',
        'brgy_code',
        'contact_number',
        'priority_level',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

}
