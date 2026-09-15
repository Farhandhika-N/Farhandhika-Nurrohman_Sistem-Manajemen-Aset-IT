<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_code', 'name', 'category', 'condition', 'problem_description', 'image', 'assigned_to'
    ];
}
