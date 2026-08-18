<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapat_peserta', function (Blueprint $table) {
            // Kategori orang: siapa mereka di institusi
            // Diisi bebas — bisa 'Dosen', 'Tendik', 'Mahasiswa', 'Tamu', 'Narasumber', dll.
            $table->string('tipe_peserta', 100)->nullable()->after('user_id')
                ->comment('Kategori peserta: Dosen, Tendik, Mahasiswa, Tamu, Narasumber, dll.');

            // Rename field agar lebih generik (nama_eksternal → nama_manual, dst.)
            // Karena PostgreSQL tidak support RENAME COLUMN langsung via Blueprint change(),
            // kita tambah alias accessor di model — kolom lama tetap dipakai.
        });
    }

    public function down(): void
    {
        Schema::table('rapat_peserta', function (Blueprint $table) {
            $table->dropColumn('tipe_peserta');
        });
    }
};
