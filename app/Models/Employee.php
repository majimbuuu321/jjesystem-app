<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'employees';
    protected $fillable = [
        'employee_code',
        'first_name',
        'middle_name',
        'address',
        'last_name',
        'address',
        'gender',
        'contact_number',
        'birth_date',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
