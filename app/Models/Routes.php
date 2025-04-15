<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RouteGroup;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Routes extends Model
{
    //
    use SoftDeletes;

    protected $table = 'routes';

    protected $fillable = [
        'employee_id',
        'route_name',
        'route_group_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function route_group(){
        return $this->belongsTo(RouteGroup::class, 'route_group_id', 'id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
