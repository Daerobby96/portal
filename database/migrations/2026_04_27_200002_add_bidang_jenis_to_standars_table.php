<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('standars', function (Blueprint $table) {
            // Bidang: pendidikan / penelitian / pkm / institusional
            $table->enum('bidang', ['pendidikan', 'penelitian', 'pkm', 'institusional'])
                  ->default('pendidikan')
                  ->after('is_aktif');

            // Jenis: inti (wajib SN-Dikti), tambahan (institusional)
            $table->enum('jenis', ['inti', 'tambahan'])->default('inti')->after('bidang');

            // Nomor urut standar
            $table->unsignedTinyInteger('nomor')->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('standars', function (Blueprint $table) {
            $table->dropColumn(['bidang', 'jenis', 'nomor']);
        });
    }
};
