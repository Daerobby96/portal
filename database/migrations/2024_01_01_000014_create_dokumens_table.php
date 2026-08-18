<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_dokumens')->cascadeOnDelete();
            $table->foreignId('standar_id')->nullable()->constrained('standars')->nullOnDelete();
            $table->foreignId('pembuat_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode_dokumen', 50)->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path');
            $table->string('tipe_file', 20)->nullable();
            $table->bigInteger('ukuran_file')->nullable();
            $table->string('versi', 20)->default('1.0');
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak', 'kadaluarsa'])->default('draft');
            $table->boolean('is_public')->default(false);
            $table->date('tanggal_berlaku')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dokumen_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumens')->cascadeOnDelete();
            $table->string('versi', 20);
            $table->string('file_path');
            $table->text('catatan_perubahan')->nullable();
            $table->foreignId('diunggah_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('dokumen_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumens')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_approval')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_approvals');
        Schema::dropIfExists('dokumen_versions');
        Schema::dropIfExists('dokumens');
    }
};
