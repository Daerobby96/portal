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
        Schema::table('kerjasamas', function (Blueprint $table) {
            $table->string('jenis_dokumen')->nullable()->after('judul_kerjasama');
        });

        Schema::create('evaluasi_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerjasama_id')->constrained('kerjasamas')->cascadeOnDelete();
            $table->date('tanggal_evaluasi');
            $table->tinyInteger('nilai'); // 1-5
            $table->text('catatan')->nullable();
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_mitras');
        
        Schema::table('kerjasamas', function (Blueprint $table) {
            $table->dropColumn('jenis_dokumen');
        });
    }
};
