<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 200);
            $table->enum('jenjang', ['D3', 'D4', 'S1', 'S2', 'S3', 'Profesi']);
            $table->string('akreditasi', 50)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
