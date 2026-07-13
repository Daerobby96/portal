<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'standars',
            'indikator_kinerjas',
            'monitorings',
            'audits',
            'dokumens',
        ];

        foreach ($tables as $tabel) {
            Schema::table($tabel, function (Blueprint $table) {
                $table->foreignId('prodi_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('program_studis')
                    ->nullOnDelete();
                $table->index('prodi_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'standars',
            'indikator_kinerjas',
            'monitorings',
            'audits',
            'dokumens',
        ];

        foreach ($tables as $tabel) {
            Schema::table($tabel, function (Blueprint $table) use ($tabel) {
                $table->dropForeign([$tabel . '_prodi_id_foreign']);
                $table->dropIndex([$tabel . '_prodi_id_index']);
                $table->dropColumn('prodi_id');
            });
        }
    }
};
