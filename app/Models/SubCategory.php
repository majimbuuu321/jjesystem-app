<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SubCategory extends Model
{
    //
    use SoftDeletes;

    protected $table = 'sub_category';

    protected $fillable = [
        'sub_category_name',
        'product_category_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

}
