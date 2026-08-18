<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapat_peserta', function (Blueprint $table) {
            // user_id jadi nullable (peserta eksternal tidak punya akun)
            $table->foreignId('user_id')->nullable()->change();

            // Field untuk peserta eksternal
            $table->string('nama_eksternal', 255)->nullable()->after('user_id');
            $table->string('instansi', 255)->nullable()->after('nama_eksternal');
            $table->string('jabatan_eksternal', 255)->nullable()->after('instansi');
            $table->string('email_eksternal', 255)->nullable()->after('jabatan_eksternal');
            $table->string('no_hp_eksternal', 50)->nullable()->after('email_eksternal');

            // Ubah unique constraint: user_id bisa null untuk eksternal
            // Drop constraint lama, nanti handle di app layer
            $table->dropUnique(['rapat_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('rapat_peserta', function (Blueprint $table) {
            $table->dropColumn([
                'nama_eksternal', 'instansi', 'jabatan_eksternal',
                'email_eksternal', 'no_hp_eksternal',
            ]);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->unique(['rapat_id', 'user_id']);
        });
    }
};
