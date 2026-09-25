<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'user_id', 'action', 'notes'];

    public function asset()
    {
        return $this->belongsTo(Asset::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Filter dipakai bersama oleh history() dan exportHistoryPDF()
    public function scopeFilter($query, $request)
    {
        // 1. Filter Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('asset', function ($assetQ) use ($search) {
                      $assetQ->where('name', 'like', "%{$search}%")
                             ->orWhere('asset_code', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Berdasarkan Jenis Aksi
        if ($request->filled('action_filter')) {
            $query->where('action', $request->action_filter);
        }

        // 3. Filter Berdasarkan Rentang Waktu
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate   = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 4. Filter Waktu Cepat (harian/mingguan/bulanan)
        if ($request->filled('time_filter')) {
            if ($request->time_filter === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->time_filter === 'week') {
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay(),
                    Carbon::now()->endOfWeek(Carbon::SUNDAY)->endOfDay(),
                ]);
            } elseif ($request->time_filter === 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        return $query;
    }
}
