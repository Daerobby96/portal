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
        // Insert kop surat settings for Yayasan and PT
        $settings = [
            ['key' => 'kop_surat_yayasan', 'value' => null, 'type' => 'image', 'group' => 'kop_surat', 'label' => 'Kop Surat Yayasan'],
            ['key' => 'kop_surat_pt',      'value' => null, 'type' => 'image', 'group' => 'kop_surat', 'label' => 'Kop Surat Perguruan Tinggi'],
        ];

        foreach ($settings as $setting) {
            \DB::table('settings')->insertOrIgnore($setting);
        }
    }

    public function down(): void
    {
        \DB::table('settings')->whereIn('key', ['kop_surat_yayasan', 'kop_surat_pt'])->delete();
    }
};
