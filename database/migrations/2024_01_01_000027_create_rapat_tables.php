<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('judul');
            $table->string('jenis', 50)->default('Koordinasi'); // RTM, Koordinasi, Evaluasi, Audit, Khusus
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('tempat');
            $table->text('deskripsi')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->enum('status', ['draft', 'terjadwal', 'berlangsung', 'selesai', 'dibatalkan'])->default('draft');
            $table->text('alasan_pembatalan')->nullable();

            // RTM Specific fields
            $table->text('input_audit_internal')->nullable();
            $table->text('input_umpan_balik')->nullable();
            $table->text('input_kinerja_proses')->nullable();
            $table->text('input_status_tindakan')->nullable();
            $table->text('input_rekomendasi')->nullable();
            $table->text('output_perbaikan')->nullable();

            $table->timestamps();
        });

        Schema::create('rapat_agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->integer('urutan')->default(1);
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->integer('estimasi_durasi')->default(30); // menit
            $table->text('notulensi')->nullable();
            $table->foreignId('notulensi_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('notulensi_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('rapat_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->string('tipe_peserta', 20)->default('internal'); // internal, eksternal
            $table->string('nama_eksternal')->nullable();
            $table->string('instansi')->nullable();
            $table->string('email_eksternal')->nullable();
            $table->string('no_hp_eksternal')->nullable();
            $table->string('peran', 50)->default('Peserta'); // Ketua, Notulis, Peserta, Narasumber
            $table->enum('status_kehadiran', ['diundang', 'hadir', 'izin', 'tidak_hadir'])->default('diundang');
            $table->text('keterangan')->nullable();
            $table->timestamp('kehadiran_updated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('rapat_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->foreignId('pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('deskripsi');
            $table->date('deadline')->nullable();
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');
            $table->enum('status', ['belum_mulai', 'dalam_proses', 'selesai', 'dibatalkan'])->default('dalam_proses');
            $table->text('catatan_progres')->nullable();
            $table->timestamps();
        });

        Schema::create('rapat_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained('rapats')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('nama_asli');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->bigInteger('ukuran')->default(0);
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
