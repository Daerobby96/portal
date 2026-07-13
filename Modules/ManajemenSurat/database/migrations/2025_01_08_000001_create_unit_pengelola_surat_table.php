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
        Schema::create('unit_pengelola_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100); // Nama unit (Yayasan Pendidikan ABC, STMIK XYZ, Prodi TI)
            $table->string('kode', 20)->unique(); // Kode unit (YYS, STMIK, TI, SI, dll)
            $table->enum('jenis_institusi', ['yayasan', 'perguruan_tinggi']); // Hanya 2 jenis
            $table->string('prefix_format')->nullable(); // Format custom: {nomor}/{kode_jenis}/{kode_unit}/{bulan}/{tahun}
            $table->text('deskripsi')->nullable();
            $table->string('pic_nama')->nullable(); // Person in charge
            $table->string('pic_jabatan')->nullable();
            $table->string('pic_nip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Update tabel surat_keluar: tambah unit_id
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('jenis_surat_id')->constrained('unit_pengelola_surat')->nullOnDelete();
            $table->index('unit_id');
        });

        // Update tabel nomor_surat: tambah unit_id untuk tracking per unit
        Schema::table('nomor_surat', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('jenis_surat_id')->constrained('unit_pengelola_surat')->nullOnDelete();
            $table->index(['jenis_surat_id', 'unit_id', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomor_surat', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['jenis_surat_id', 'unit_id', 'tahun', 'bulan']);
            $table->dropColumn('unit_id');
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::dropIfExists('unit_pengelola_surat');
    }
};
