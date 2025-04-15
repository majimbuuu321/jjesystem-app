<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    //

    use SoftDeletes;
    
    protected $table = 'warehouse';
    protected $fillable = [
        'employee_id',
        'warehouse_name',
        'warehouse_address',
        'warehouse_type_id',
        'route_id',
        'is_active',
        'gender',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function route(){
        return $this->belongsTo(Routes::class, 'route_id', 'id');
    }
}
