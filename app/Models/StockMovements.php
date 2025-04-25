<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovements extends Model
{
    //
    public $timestamps = false;
    protected $table = 'stock_movements';
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'uom_id',
        'movement_type',
        'quantity',
        'reference_note',
        'created_by',
        'created_at',
    ];
}
