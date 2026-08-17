<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->string('nomor_surat_tugas')->nullable()->after('closing_meeting');
            $table->date('tgl_surat_tugas')->nullable()->after('nomor_surat_tugas');
            $table->string('penandatangan_surat_tugas')->nullable()->after('tgl_surat_tugas');
            $table->string('jabatan_penandatangan')->nullable()->after('penandatangan_surat_tugas');
            $table->timestamp('bapa_signed_at_auditor')->nullable()->after('jabatan_penandatangan');
            $table->timestamp('bapa_signed_at_auditee')->nullable()->after('bapa_signed_at_auditor');
            $table->foreignId('bapa_signed_by_auditee_id')->nullable()->after('bapa_signed_at_auditee')->constrained('users')->nullOnDelete();
            $table->text('bapa_catatan')->nullable()->after('bapa_signed_by_auditee_id');
        });

        Schema::table('audit_checklists', function (Blueprint $table) {
            $table->string('tahap', 30)->default('visitasi')->after('pertanyaan'); // desk_evaluation, visitasi
            $table->text('evaluasi_auditee')->nullable()->after('catatan');
            $table->text('bukti_auditee')->nullable()->after('bukti_objektif');
        });

        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->text('tindakan_pencegahan')->nullable()->after('rencana_tindakan');
            $table->text('metode_5_whys')->nullable()->after('analisa_penyebab');
        });
    }

    public function down(): void
    {
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->dropColumn(['tindakan_pencegahan', 'metode_5_whys']);
        });

        Schema::table('audit_checklists', function (Blueprint $table) {
            $table->dropColumn(['tahap', 'evaluasi_auditee', 'bukti_auditee']);
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->dropForeign(['bapa_signed_by_auditee_id']);
            $table->dropColumn([
                'nomor_surat_tugas',
                'tgl_surat_tugas',
                'penandatangan_surat_tugas',
                'jabatan_penandatangan',
                'bapa_signed_at_auditor',
                'bapa_signed_at_auditee',
                'bapa_signed_by_auditee_id',
                'bapa_catatan',
            ]);
        });
    }
};
