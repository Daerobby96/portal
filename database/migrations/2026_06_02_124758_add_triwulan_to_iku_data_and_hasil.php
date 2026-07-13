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
        // Tambah kolom triwulan di tabel iku_data_input
        Schema::table('iku_data_input', function (Blueprint $table) {
            $table->enum('triwulan', ['TW1', 'TW2', 'TW3', 'TW4', 'TAHUNAN'])->default('TAHUNAN')->after('periode_id');
        });
        
        // Tambah kolom triwulan di tabel iku_hasil
        Schema::table('iku_hasil', function (Blueprint $table) {
            $table->enum('triwulan', ['TW1', 'TW2', 'TW3', 'TW4', 'TAHUNAN'])->default('TAHUNAN')->after('periode_id');
        });
        
        // Update unique constraint di iku_hasil untuk include triwulan
        Schema::table('iku_hasil', function (Blueprint $table) {
            $table->dropUnique(['iku_resmi_id', 'periode_id']);
            $table->unique(['iku_resmi_id', 'periode_id', 'triwulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iku_hasil', function (Blueprint $table) {
            $table->dropUnique(['iku_resmi_id', 'periode_id', 'triwulan']);
            $table->unique(['iku_resmi_id', 'periode_id']);
        });
        
        Schema::table('iku_data_input', function (Blueprint $table) {
            $table->dropColumn('triwulan');
        });
        
        Schema::table('iku_hasil', function (Blueprint $table) {
            $table->dropColumn('triwulan');
        });
    }
};
