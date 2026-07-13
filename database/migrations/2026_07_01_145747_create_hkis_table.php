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
        Schema::create('hkis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained()->cascadeOnDelete(); // Dosen
            $table->string('judul_hki');
            $table->string('jenis_hki'); // Paten, Paten Sederhana, Hak Cipta, Merek, Desain Industri
            $table->string('nomor_pencatatan')->nullable();
            $table->integer('tahun_terbit');
            $table->string('status')->default('Terdaftar'); // Terdaftar, Granted/Sertifikat
            $table->string('sertifikat')->nullable(); // path file
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hkis');
    }
};
