<?php

namespace App\Imports;

use Modules\Sdm\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PegawaiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    public function model(array $row): ?Pegawai
    {
        if (empty($row['nama'])) return null;

        return new Pegawai([
            'nip'                 => $row['nip'] ?? null,
            'nama'                => $row['nama'],
            'email'               => $row['email'] ?? null,
            'no_hp'               => $row['no_hp'] ?? null,
            'jabatan'             => $row['jabatan'] ?? null,
            'unit_kerja'          => $row['unit_kerja'] ?? null,
            'jenis_pegawai'       => $row['jenis_pegawai'] ?? Pegawai::JENIS_LAINNYA,
            'status_kepegawaian'  => $row['status_kepegawaian'] ?? null,
            'is_aktif'            => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'nip'   => 'nullable|string|max:50',
        ];
    }
}
