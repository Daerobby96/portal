<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('r_t_m_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->string('judul_rapat');
            $table->date('tanggal_rapat');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('tempat')->nullable();
            $table->foreignId('pimpinan_rapat_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('notulis_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('agenda')->nullable();
            $table->text('notulensi')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->text('input_audit_internal')->nullable();
            $table->text('input_umpan_balik')->nullable();
            $table->text('input_kinerja_proses')->nullable();
            $table->text('input_status_tindakan')->nullable();
            $table->text('output_perbaikan')->nullable();
            $table->text('file_notulensi')->nullable();
            $table->enum('status', ['draft', 'terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rtm_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rtm_id')->constrained('r_t_m_s')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_eksternal')->nullable();
            $table->string('instansi')->nullable();
            $table->string('peran')->default('Peserta');
            $table->enum('status_kehadiran', ['diundang', 'hadir', 'izin', 'tidak_hadir'])->default('diundang');
            $table->timestamps();
        });

        Schema::create('rtm_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rtm_id')->constrained('r_t_m_s')->cascadeOnDelete();
            $table->foreignId('pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rekomendasi');
            $table->text('tindakan_perbaikan');
            $table->date('target_selesai')->nullable();
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rtm_tindak_lanjuts');
        Schema::dropIfExists('rtm_pesertas');
        Schema::dropIfExists('r_t_m_s');
    }
};
