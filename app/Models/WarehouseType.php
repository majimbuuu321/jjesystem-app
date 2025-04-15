<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WarehouseType extends Model
{
    use SoftDeletes;
    //
    protected $table = 'warehouse_type';
    protected $fillable = [
        'warehouse_type_name',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
