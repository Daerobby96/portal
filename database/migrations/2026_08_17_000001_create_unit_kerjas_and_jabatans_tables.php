<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Unit Kerja
        if (!Schema::hasTable('unit_kerjas')) {
            Schema::create('unit_kerjas', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 50)->unique();
                $table->string('nama', 255);
                $table->string('tipe', 50)->default('jurusan'); // jurusan, prodi, biro, lembaga, upt, pimpinan, lainnya
                $table->string('kepala_unit', 255)->nullable();
                $table->string('lokasi', 255)->nullable();
                $table->text('deskripsi')->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->timestamps();
            });
        }

        // 2. Tabel Jabatan
        if (!Schema::hasTable('jabatans')) {
            Schema::create('jabatans', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 50)->unique();
                $table->string('nama', 255);
                $table->string('kategori', 50)->default('fungsional_dosen'); // struktural, fungsional_dosen, fungsional_tendik, pelaksana
                $table->integer('level_hirarki')->default(1);
                $table->decimal('tunjangan_dasar', 15, 2)->nullable()->default(0);
                $table->text('deskripsi')->nullable();
                $table->boolean('is_aktif')->default(true);
                $table->timestamps();
            });
        }

        // 3. Tambah foreign key opsional ke pegawais
        Schema::table('pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawais', 'unit_kerja_id')) {
                $table->foreignId('unit_kerja_id')->nullable()->after('unit_kerja')->constrained('unit_kerjas')->nullOnDelete();
            }
            if (!Schema::hasColumn('pegawais', 'jabatan_id')) {
                $table->foreignId('jabatan_id')->nullable()->after('jabatan')->constrained('jabatans')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('pegawais', 'unit_kerja_id')) {
                $table->dropForeign(['unit_kerja_id']);
                $table->dropColumn('unit_kerja_id');
            }
            if (Schema::hasColumn('pegawais', 'jabatan_id')) {
                $table->dropForeign(['jabatan_id']);
                $table->dropColumn('jabatan_id');
            }
        });

        Schema::dropIfExists('jabatans');
        Schema::dropIfExists('unit_kerjas');
    }
};
