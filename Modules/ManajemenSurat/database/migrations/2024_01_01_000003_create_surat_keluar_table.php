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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->onDelete('restrict');
            $table->string('nomor_surat', 100)->unique()->comment('Nomor surat lengkap');
            $table->string('nomor_agenda', 50)->nullable()->comment('Nomor agenda surat');
            $table->string('perihal')->comment('Perihal/tentang surat');
            $table->text('isi_surat')->nullable()->comment('Isi/konten surat');
            $table->date('tanggal_surat')->comment('Tanggal surat dibuat/ditetapkan');
            $table->string('tujuan')->comment('Tujuan surat/penerima');
            $table->string('alamat_tujuan')->nullable()->comment('Alamat lengkap tujuan');
            
            // Penandatangan
            $table->string('penandatangan_nama', 100)->comment('Nama penandatangan');
            $table->string('penandatangan_jabatan', 100)->comment('Jabatan penandatangan');
            $table->string('penandatangan_nip', 50)->nullable()->comment('NIP penandatangan');
            
            // Lampiran dan file
            $table->integer('jumlah_lampiran')->default(0)->comment('Jumlah lampiran');
            $table->text('keterangan_lampiran')->nullable()->comment('Keterangan lampiran');
            $table->string('file_path')->nullable()->comment('Path file PDF surat');
            
            // Status dan workflow
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'published'])->default('draft');
            $table->text('catatan')->nullable()->comment('Catatan tambahan');
            
            // Tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('tanggal_surat');
            $table->index('status');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
