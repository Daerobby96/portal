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
        // Tambah kolom target_default di tabel iku_resmi
        Schema::table('iku_resmi', function (Blueprint $table) {
            $table->decimal('target_default', 10, 2)->nullable()->after('satuan');
            $table->text('deskripsi_target')->nullable()->after('target_default');
        });
        
        // Tambah kolom target dan persentase capaian di tabel iku_hasil
        Schema::table('iku_hasil', function (Blueprint $table) {
            $table->decimal('target', 10, 2)->nullable()->after('periode_id');
            $table->decimal('persentase_capaian', 10, 2)->default(0)->after('nilai_hasil');
            $table->decimal('gap', 10, 2)->default(0)->after('persentase_capaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iku_resmi', function (Blueprint $table) {
            $table->dropColumn(['target_default', 'deskripsi_target']);
        });
        
        Schema::table('iku_hasil', function (Blueprint $table) {
            $table->dropColumn(['target', 'persentase_capaian', 'gap']);
        });
    }
};
