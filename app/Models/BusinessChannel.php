<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BusinessChannel extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'business_channel';
    protected $fillable = [
        'business_channel_name',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
