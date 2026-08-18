<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel utama rapat ──────────────────────────────────────
        Schema::create('rapats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('judul', 255);
            $table->enum('jenis', ['RTM', 'Koordinasi', 'Evaluasi', 'Audit', 'Khusus']);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('tempat', 255);
            $table->text('deskripsi')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->enum('status', ['draft', 'terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('draft');
            $table->text('alasan_pembatalan')->nullable();
            // Field khusus RTM (ISO 9001)
            $table->text('input_audit_internal')->nullable();
            $table->text('input_umpan_balik')->nullable();
            $table->text('input_kinerja_proses')->nullable();
            $table->text('input_status_tindakan')->nullable();
            $table->text('input_perubahan_sistem')->nullable();
            $table->text('input_rekomendasi')->nullable();
            $table->text('output_keefektifan')->nullable();
            $table->text('output_perbaikan')->nullable();
            $table->text('output_sumber_daya')->nullable();
            $table->timestamps();
        });

        // ── Agenda rapat ───────────────────────────────────────────
        Schema::create('rapat_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan');
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('estimasi_durasi')->default(30); // menit
            $table->text('notulensi')->nullable();
            $table->foreignId('notulensi_updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('notulensi_updated_at')->nullable();
            $table->timestamps();
        });

        // ── Peserta rapat ──────────────────────────────────────────
        Schema::create('rapat_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('peran', ['Ketua', 'Notulis', 'Peserta'])->default('Peserta');
            $table->string('keterangan', 500)->nullable();
            $table->enum('status_kehadiran', ['diundang', 'hadir', 'tidak_hadir', 'izin'])->default('diundang');
            $table->timestamp('kehadiran_updated_at')->nullable();
            $table->unique(['rapat_id', 'user_id']);
            $table->timestamps();
        });

        // ── Tindak lanjut rapat ────────────────────────────────────
        Schema::create('rapat_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->foreignId('pic_id')->constrained('users');
            $table->text('deskripsi');
            $table->date('deadline');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');
            $table->enum('status', ['belum_mulai', 'dalam_proses', 'selesai', 'dibatalkan'])->default('belum_mulai');
            $table->text('catatan_progres')->nullable();
            $table->date('tanggal_selesai_aktual')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ── Lampiran rapat ─────────────────────────────────────────
        Schema::create('rapat_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('nama_asli', 255);
            $table->string('path', 500);
            $table->string('mime_type', 100);
            $table->unsignedInteger('ukuran'); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapat_lampirans');
        Schema::dropIfExists('rapat_tindak_lanjuts');
        Schema::dropIfExists('rapat_peserta');
        Schema::dropIfExists('rapat_agendas');
        Schema::dropIfExists('rapats');
    }
};
