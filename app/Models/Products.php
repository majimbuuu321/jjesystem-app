<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Products extends Model
{
    //
    use SoftDeletes;
    protected $table = 'products';
    protected $fillable = [
        'price_date',
        'product_category_id',
        'sub_category_id',
        'supplier_id',
        'warehouse_id',
        'product_code',
        'product_description',
        'unit_ctn',
        'unit_cost',
        'unit_price',
        'reorder_level',
        'weight',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function PricePerCode(): HasMany
    {
        return $this->hasMany(PricePerCode::class);
    }

    public function CostHistory(): HasMany
    {
        return $this->hasMany(CostHistory::class);
    }
}
