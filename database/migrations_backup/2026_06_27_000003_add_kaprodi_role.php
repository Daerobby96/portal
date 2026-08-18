<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya insert jika belum ada
        if (!DB::table('roles')->where('name', 'kaprodi')->exists()) {
            DB::table('roles')->insert([
                'name'         => 'kaprodi',
                'guard_name'   => 'web',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('roles')->where('name', 'kaprodi')->delete();
    }
};
