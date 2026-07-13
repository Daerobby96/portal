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
        Schema::create('nomor_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->onDelete('cascade');
            $table->string('tahun', 4)->comment('Tahun surat');
            $table->string('bulan', 2)->comment('Bulan surat');
            $table->integer('nomor_urut')->comment('Nomor urut terakhir');
            $table->timestamps();
            
            // Unique constraint untuk memastikan tidak ada duplikasi nomor per jenis per bulan per tahun
            $table->unique(['jenis_surat_id', 'tahun', 'bulan'], 'unique_nomor_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomor_surat');
    }
};
