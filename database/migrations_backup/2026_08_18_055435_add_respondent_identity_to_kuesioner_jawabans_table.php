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
        Schema::table('kuesioner_jawabans', function (Blueprint $table) {
            $table->string('nama_responden')->nullable()->after('user_id');
            $table->string('identitas_nomor')->nullable()->after('nama_responden'); // NIM, NIP, NIDN, NIK
            $table->string('kategori_responden')->nullable()->after('identitas_nomor'); // Mahasiswa, Dosen, Tendik, Alumni, Pengguna Lulusan, Mitra Kerjasama, Umum
            $table->string('program_studi')->nullable()->after('kategori_responden');
            $table->string('angkatan_semester')->nullable()->after('program_studi');
            $table->string('instansi')->nullable()->after('angkatan_semester');
            $table->string('jabatan')->nullable()->after('instansi');
            $table->string('email_responden')->nullable()->after('jabatan');
            $table->string('no_hp_responden')->nullable()->after('email_responden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuesioner_jawabans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_responden',
                'identitas_nomor',
                'kategori_responden',
                'program_studi',
                'angkatan_semester',
                'instansi',
                'jabatan',
                'email_responden',
                'no_hp_responden',
            ]);
        });
    }
};
