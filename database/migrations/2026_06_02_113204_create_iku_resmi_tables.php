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
        // Tabel master IKU resmi
        Schema::create('iku_resmi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_iku', 10)->unique(); // IKU1, IKU2, dst
            $table->string('nama', 255);
            $table->enum('sifat', ['WAJIB', 'PILIHAN', 'WAJIB PTN-BH', 'PILIHAN PTN']);
            $table->text('formula')->nullable(); // Formula perhitungan
            $table->string('satuan', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('referensi', 255)->default('Kepmendikti 358/M/KEP/2025');
            $table->string('sheet_name', 50)->nullable(); // Nama sheet di Excel
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        // Tabel data input IKU (fleksibel untuk berbagai jenis input)
        Schema::create('iku_data_input', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iku_resmi_id')->constrained('iku_resmi')->onDelete('cascade');
            $table->foreignId('periode_id')->nullable()->constrained('periodes')->onDelete('cascade');
            $table->string('kategori', 100)->nullable(); // Misal: "Diploma 3", "Bekerja <6bln", dst
            $table->decimal('nilai_input', 15, 2)->default(0);
            $table->decimal('bobot', 5, 2)->nullable(); // Bobot untuk perhitungan
            $table->json('metadata')->nullable(); // Data tambahan fleksibel
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index(['iku_resmi_id', 'periode_id']);
        });

        // Tabel hasil perhitungan IKU per periode
        Schema::create('iku_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iku_resmi_id')->constrained('iku_resmi')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->decimal('nilai_hasil', 10, 2)->default(0);
            $table->enum('status_capaian', ['Tercapai', 'Tidak Tercapai', 'Dalam Progress', 'Belum Dihitung'])->default('Belum Dihitung');
            $table->text('catatan')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
            
            $table->unique(['iku_resmi_id', 'periode_id']);
        });

        // Tambah kolom di tabel indikator_kinerjas untuk link ke IKU resmi
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            $table->foreignId('iku_resmi_id')->nullable()->after('standar_id')->constrained('iku_resmi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            $table->dropForeign(['iku_resmi_id']);
            $table->dropColumn('iku_resmi_id');
        });
        
        Schema::dropIfExists('iku_hasil');
        Schema::dropIfExists('iku_data_input');
        Schema::dropIfExists('iku_resmi');
    }
};
