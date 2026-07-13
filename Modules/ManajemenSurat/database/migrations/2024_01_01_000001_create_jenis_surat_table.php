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
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()->comment('Kode jenis surat untuk penomoran');
            $table->string('nama', 100)->comment('Nama jenis surat');
            $table->enum('kategori', ['masuk', 'keluar'])->comment('Kategori surat masuk/keluar');
            $table->string('template_path')->nullable()->comment('Path template PDF');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_surat');
    }
};
