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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->onDelete('restrict');
            $table->string('nomor_agenda', 50)->unique()->comment('Nomor agenda surat masuk');
            $table->string('nomor_surat', 100)->comment('Nomor surat dari pengirim');
            $table->date('tanggal_surat')->comment('Tanggal surat dari pengirim');
            $table->date('tanggal_terima')->comment('Tanggal surat diterima');
            
            $table->string('pengirim')->comment('Nama pengirim/instansi');
            $table->string('alamat_pengirim')->nullable()->comment('Alamat pengirim');
            $table->string('perihal')->comment('Perihal surat');
            
            // Lampiran dan file
            $table->integer('jumlah_lampiran')->default(0)->comment('Jumlah lampiran');
            $table->text('keterangan_lampiran')->nullable()->comment('Keterangan lampiran');
            $table->string('file_path')->nullable()->comment('Path file scan surat');
            
            // Klasifikasi
            $table->enum('sifat', ['biasa', 'segera', 'sangat_segera', 'rahasia'])->default('biasa');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            
            // Status
            $table->enum('status', ['baru', 'proses', 'selesai', 'arsip'])->default('baru');
            $table->text('catatan')->nullable()->comment('Catatan penerimaan');
            
            // Tracking
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete()->comment('Diterima oleh');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('Diinput oleh');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('tanggal_terima');
            $table->index('tanggal_surat');
            $table->index('status');
            $table->index('sifat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
