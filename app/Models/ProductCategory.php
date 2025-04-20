<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProductCategory extends Model
{
    //
    use SoftDeletes;

    protected $table = 'product_category';

    protected $fillable = [
        'product_category_name',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function SubCategory(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
