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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->onDelete('cascade');
            $table->foreignId('dari_user_id')->constrained('users')->onDelete('restrict')->comment('User yang mendisposisi');
            $table->foreignId('kepada_user_id')->constrained('users')->onDelete('restrict')->comment('User yang menerima disposisi');
            
            $table->text('isi_disposisi')->comment('Isi/instruksi disposisi');
            $table->date('batas_waktu')->nullable()->comment('Batas waktu tindak lanjut');
            
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['pending', 'dibaca', 'proses', 'selesai'])->default('pending');
            
            $table->text('catatan_tindak_lanjut')->nullable()->comment('Catatan dari penerima disposisi');
            $table->timestamp('dibaca_at')->nullable()->comment('Waktu disposisi dibaca');
            $table->timestamp('selesai_at')->nullable()->comment('Waktu disposisi diselesaikan');
            
            $table->timestamps();
            
            // Indexes
            $table->index('kepada_user_id');
            $table->index('status');
            $table->index('batas_waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
