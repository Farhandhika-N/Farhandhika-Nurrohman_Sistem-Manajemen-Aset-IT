<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel assets (jika aset dihapus, riwayatnya ikut terhapus)
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            
            // Relasi ke tabel users (untuk mencatat siapa yang melakukan perubahan)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('action'); // Contoh: "Registrasi Baru", "Dipinjamkan", "Diservis", dll.
            $table->text('notes')->nullable(); // Detail perubahannya
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
