<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_standar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumens')->cascadeOnDelete();
            $table->foreignId('standar_id')->constrained('standars')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dokumen_id', 'standar_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_standar');
    }
};
