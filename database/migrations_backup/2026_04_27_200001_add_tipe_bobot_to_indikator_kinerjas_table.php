<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            // Tipe: IKU (Indikator Kinerja Utama - wajib Kemendikbud)
            //       IKT (Indikator Kinerja Tambahan - institusional)
            //       custom (lainnya)
            $table->enum('tipe', ['IKU', 'IKT', 'custom'])->default('IKT')->after('is_aktif');

            // Bobot: untuk kalkulasi nilai mutu agregat (0.00 - 100.00)
            $table->decimal('bobot', 5, 2)->default(1.00)->after('tipe');

            // Sumber acuan (opsional)
            $table->string('sumber')->nullable()->after('bobot');
        });
    }

    public function down(): void
    {
        Schema::table('indikator_kinerjas', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'bobot', 'sumber']);
        });
    }
};
