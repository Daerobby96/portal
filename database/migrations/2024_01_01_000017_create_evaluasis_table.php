<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_id')->constrained('monitorings')->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->text('analisa')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->enum('hasil', ['tercapai', 'belum_tercapai', 'melampaui'])->default('belum_tercapai');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasis');
    }
};
