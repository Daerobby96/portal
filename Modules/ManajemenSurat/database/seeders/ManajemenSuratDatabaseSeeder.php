<?php

namespace Modules\ManajemenSurat\Database\Seeders;

use Illuminate\Database\Seeder;

class ManajemenSuratDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            JenisSuratSeeder::class,
            UnitPengelolaSuratSeeder::class,
        ]);
    }
}
