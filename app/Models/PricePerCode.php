<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricePerCode extends Model
{
    //
    protected $table = 'price_per_code';
    protected $fillable = [
        'price_date',
        'product_id',
        'unit_of_measurement_id',
        'price_code_id',
        'unit_price',
        'created_by',
        'updated_by',
    ];

    public function priceCode(){
        return $this->belongsTo(PriceCode::class, 'price_code_id', 'id');
    }

    public function UOM(){
        return $this->belongsTo(UnitOfMeasurement::class, 'unit_of_measurement_id', 'id');
    }
}
