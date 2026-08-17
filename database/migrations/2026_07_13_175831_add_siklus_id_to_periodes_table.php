<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->foreignId('siklus_spmi_id')
                ->nullable()
                ->after('keterangan')
                ->constrained('siklus_spmis')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->dropForeignIdFor(\Modules\Spmi\Models\SiklusSpmi::class, 'siklus_spmi_id');
            $table->dropColumn('siklus_spmi_id');
        });
    }
};
