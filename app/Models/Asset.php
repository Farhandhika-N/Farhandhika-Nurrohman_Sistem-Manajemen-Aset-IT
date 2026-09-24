<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_code', 'name', 'category', 'condition', 'problem_description', 'image', 'assigned_to'
    ];

    public function histories()
    {
        return $this->hasMany(AssetHistory::class)->latest();
    }
}
