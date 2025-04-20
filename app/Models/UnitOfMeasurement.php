<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class UnitOfMeasurement extends Model
{
    //
    use SoftDeletes;

    protected $table = 'unit_of_measurement';

    protected $fillable = [
        'unit_code',
        'unit_name',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
