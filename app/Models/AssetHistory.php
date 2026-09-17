<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    protected $fillable = ['asset_id', 'user_id', 'action', 'notes'];

    // Relasi balik ke Aset
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // Relasi balik ke User pencatat
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
