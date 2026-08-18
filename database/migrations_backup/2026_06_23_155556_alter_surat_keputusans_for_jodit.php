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
        Schema::table('surat_keputusans', function (Blueprint $table) {
            $table->dropColumn(['menimbang', 'mengingat', 'menetapkan']);
            $table->longText('isi_sk')->after('tentang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keputusans', function (Blueprint $table) {
            $table->dropColumn('isi_sk');
            $table->json('menimbang')->nullable();
            $table->json('mengingat')->nullable();
            $table->json('menetapkan')->nullable();
        });
    }
};
