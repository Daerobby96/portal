<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('kode_ruangan', 50)->unique();
            $table->string('nama_ruangan');
            $table->enum('jenis', ['kelas', 'lab', 'ruang_rapat', 'ruang_dosen', 'perpustakaan', 'lainnya'])->default('kelas');
            $table->string('gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->decimal('luas', 10, 2)->nullable()->comment('dalam m2');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('status', ['tersedia', 'tidak_tersedia', 'dalam_perbaikan'])->default('tersedia');
            $table->boolean('ber_ac')->default(false);
            $table->boolean('ber_proyektor')->default(false);
            $table->string('penanggung_jawab')->nullable();
            $table->text('fasilitas')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
