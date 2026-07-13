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
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Akademik Tambahan
            $table->string('sistem_kuliah')->nullable();
            $table->string('gelombang_daftar')->nullable();
            $table->string('is_transfer')->default('Tidak');
            $table->string('universitas_asal')->nullable();
            $table->string('nim_asal')->nullable();
            $table->string('ipk_asal')->nullable();
            $table->string('kurikulum')->nullable();

            // Biodata Pribadi
            $table->string('agama')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kodepos')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('status_nikah')->nullable();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('dusun')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->date('tgl_daftar')->nullable();

            // Data Ayah
            $table->string('nama_ayah')->nullable();
            $table->text('alamat_ayah')->nullable();
            $table->string('telp_ayah')->nullable();
            $table->string('tgl_lahir_ayah')->nullable(); // Disimpan sbg string dulu atau date jika yakin formatnya bagus, kita pakai string sesuai CSV
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();

            // Data Ibu
            $table->string('nama_ibu')->nullable();
            $table->text('alamat_ibu')->nullable();
            $table->string('telp_ibu')->nullable();
            $table->string('tgl_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();

            // Data Wali
            $table->string('nama_wali')->nullable();
            $table->text('alamat_wali')->nullable();
            $table->string('telp_wali')->nullable();
            $table->string('tgl_lahir_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('penghasilan_wali')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'sistem_kuliah', 'gelombang_daftar', 'is_transfer', 'universitas_asal', 'nim_asal', 'ipk_asal', 'kurikulum',
                'agama', 'kewarganegaraan', 'alamat', 'telepon', 'tempat_lahir', 'tanggal_lahir', 'kodepos', 'golongan_darah', 'status_nikah', 'nik', 'no_kk',
                'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'tgl_daftar',
                'nama_ayah', 'alamat_ayah', 'telp_ayah', 'tgl_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah',
                'nama_ibu', 'alamat_ibu', 'telp_ibu', 'tgl_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
                'nama_wali', 'alamat_wali', 'telp_wali', 'tgl_lahir_wali', 'pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali'
            ]);
        });
    }
};
