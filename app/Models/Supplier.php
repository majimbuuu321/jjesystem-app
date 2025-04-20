<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Supplier extends Model
{

    use SoftDeletes;
    protected $table = 'suppliers';
    protected $fillable = [
        'company_name',
        'first_name',
        'middle_name',
        'last_name',
        'supplier_address',
        'email',
        'contact_number',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


}
