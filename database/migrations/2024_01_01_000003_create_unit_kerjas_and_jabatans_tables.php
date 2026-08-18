<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->string('tipe', 50)->default('Unit'); // Akademik, Lembaga, Biro, UPT, Unit
            $table->string('kepala_unit', 100)->nullable();
            $table->string('lokasi', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->string('kategori', 50)->default('Struktural'); // Struktural, Fungsional, Pelaksana
            $table->integer('level_hirarki')->default(1);
            $table->decimal('tunjangan_dasar', 15, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatans');
        Schema::dropIfExists('unit_kerjas');
    }
};
