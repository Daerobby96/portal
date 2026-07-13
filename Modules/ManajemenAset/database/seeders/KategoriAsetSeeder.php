<?php

namespace Modules\ManajemenAset\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\ManajemenAset\Models\KategoriAset;

class KategoriAsetSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['kode' => 'KOMP', 'nama' => 'Komputer & Laptop', 'icon' => 'bi-laptop', 'color' => 'primary'],
            ['kode' => 'ELEK', 'nama' => 'Elektronik', 'icon' => 'bi-tv', 'color' => 'info'],
            ['kode' => 'LAB', 'nama' => 'Peralatan Lab', 'icon' => 'bi-flask', 'color' => 'success'],
            ['kode' => 'FURN', 'nama' => 'Furniture', 'icon' => 'bi-bookshelf', 'color' => 'warning'],
            ['kode' => 'KEND', 'nama' => 'Kendaraan', 'icon' => 'bi-car-front', 'color' => 'danger'],
            ['kode' => 'BANG', 'nama' => 'Bangunan', 'icon' => 'bi-building', 'color' => 'secondary'],
            ['kode' => 'OLHR', 'nama' => 'Olahraga', 'icon' => 'bi-trophy', 'color' => 'success'],
            ['kode' => 'LAIN', 'nama' => 'Lain-lain', 'icon' => 'bi-box', 'color' => 'dark'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriAset::create($kategori);
        }
    }
}
