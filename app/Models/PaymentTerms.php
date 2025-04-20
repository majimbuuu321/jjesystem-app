<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentTerms extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'payment_terms';
    protected $fillable = [
        'payment_terms',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
