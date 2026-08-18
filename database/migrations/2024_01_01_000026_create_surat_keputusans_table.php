<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keputusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studis')->nullOnDelete();
            $table->string('jenis_sk', 50)->default('Direktur'); // Yayasan, Direktur
            $table->string('nomor_sk', 100);
            $table->string('tentang');
            $table->date('tanggal_ditetapkan');
            $table->string('penandatangan_nama', 100);
            $table->string('penandatangan_jabatan', 100);
            $table->longText('konten_html')->nullable();
            $table->string('file_pdf')->nullable();
            $table->enum('status', ['draft', 'final', 'diarsipkan'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keputusans');
    }
};
