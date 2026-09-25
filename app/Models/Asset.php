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

    // Filter dipakai bersama oleh index(), export Excel, dan export PDF
    public function scopeFilter($query, $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%")
                  ->orWhere('assigned_to', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        return $query;
    }
}
