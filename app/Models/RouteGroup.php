<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RouteGroup extends Model
{
    //
    use SoftDeletes;

    protected $table = 'route_group';

    protected $fillable = [
        'route_group_name',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
