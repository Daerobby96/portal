<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_aset', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->string('icon')->default('bi-box');
            $table->string('color', 20)->default('primary');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_aset');
    }
};
