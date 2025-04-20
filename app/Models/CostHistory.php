<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostHistory extends Model
{
    //
    protected $table = 'unit_cost_history';
    protected $fillable = [
        'price_date',
        'product_id',
        'unit_cost',
        'created_by',
    ];
}
