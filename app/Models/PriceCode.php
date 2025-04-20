<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PriceCode extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'price_code';
    protected $fillable = [
        'price_code',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
