<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_aset')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode_aset', 100)->unique();
            $table->string('nama_aset');
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->string('nomor_seri')->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->enum('status', ['aktif', 'non_aktif', 'dalam_perbaikan', 'dihapuskan'])->default('aktif');
            $table->string('lokasi');
            $table->string('ruangan')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('sumber_perolehan')->nullable(); // hibah, pembelian, dll
            $table->decimal('harga_perolehan', 15, 2)->nullable();
            $table->integer('umur_ekonomis')->nullable()->comment('dalam tahun');
            $table->string('penanggung_jawab')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
