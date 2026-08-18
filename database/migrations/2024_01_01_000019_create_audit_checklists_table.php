<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->foreignId('indikator_id')->nullable()->constrained('indikator_kinerjas')->nullOnDelete();
            $table->text('pertanyaan');
            $table->enum('status', ['belum_diisi', 'sesuai', 'tidak_sesuai', 'observasi', 'tidak_terkait'])->default('belum_diisi');
            $table->text('catatan')->nullable();
            $table->string('bukti_objektif')->nullable();
            $table->text('desk_eval_catatan')->nullable();
            $table->string('desk_eval_kesiapan')->nullable();
            $table->foreignId('auditee_reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('desk_eval_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_checklists');
    }
};
