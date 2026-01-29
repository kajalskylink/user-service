<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $fillable = [
        'name',
        'mobile_number',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'village',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_active',
        'is_editable',
    ];
}
