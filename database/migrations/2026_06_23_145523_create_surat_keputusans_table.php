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
        Schema::create('surat_keputusans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_sk', ['yayasan', 'pt']);
            $table->string('nomor_sk');
            $table->text('tentang');
            $table->json('menimbang')->nullable();
            $table->json('mengingat')->nullable();
            $table->json('menetapkan')->nullable();
            $table->date('tanggal_ditetapkan');
            $table->string('penandatangan_nama');
            $table->string('penandatangan_jabatan');
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keputusans');
    }
};
