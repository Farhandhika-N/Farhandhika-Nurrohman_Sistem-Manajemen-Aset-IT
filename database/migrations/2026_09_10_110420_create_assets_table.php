<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::dropIfExists('assets');
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('condition');
            $table->text('problem_description')->nullable();
            $table->string('image')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('assets');
    }
};