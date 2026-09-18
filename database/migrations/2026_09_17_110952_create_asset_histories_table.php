<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel assets (Boleh kosong/null jika aset dihapus permanen)
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null');
            
            // Relasi ke tabel users (Boleh kosong jika user admin dihapus)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('action');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};