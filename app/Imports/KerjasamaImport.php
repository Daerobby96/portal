<?php

namespace App\Imports;

use App\Models\Kerjasama;
use App\Models\ProgramStudi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class KerjasamaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Temukan ID prodi dari nama prodi jika ada
        $prodiId = null;
        if (!empty($row['prodi'])) {
            $prodi = ProgramStudi::where('nama', 'ilike', '%' . trim($row['prodi']) . '%')->first();
            if ($prodi) {
                $prodiId = $prodi->id;
            }
        }

        return new Kerjasama([
            'nama_mitra'      => $row['nama_mitra'],
            'jenis_mitra'     => $this->mapJenisMitra($row['jenis_mitra']),
            'tingkat'         => $this->mapTingkat($row['tingkat']),
            'judul_kerjasama' => $row['judul_kerjasama'],
            'tanggal_mulai'   => $this->parseDate($row['tanggal_mulai']),
            'tanggal_selesai' => $this->parseDate($row['tanggal_selesai']),
            'prodi_id'        => $prodiId,
            'status'          => $this->mapStatus($row['status']),
            'keterangan'      => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_mitra'      => 'required|string|max:255',
            'jenis_mitra'     => 'required|string',
            'tingkat'         => 'required|string',
            'judul_kerjasama' => 'required|string|max:255',
            'tanggal_mulai'   => 'required',
            'status'          => 'required|string',
        ];
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;

        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function mapJenisMitra($value)
    {
        $valid = Kerjasama::JENIS_MITRA;
        foreach ($valid as $v) {
            if (strtolower(trim($value)) == strtolower($v)) {
                return $v;
            }
        }
        return 'Lainnya';
    }

    private function mapTingkat($value)
    {
        $valid = Kerjasama::TINGKAT;
        foreach ($valid as $v) {
            if (strtolower(trim($value)) == strtolower($v)) {
                return $v;
            }
        }
        return 'Lokal';
    }

    private function mapStatus($value)
    {
        $valid = Kerjasama::STATUS;
        foreach ($valid as $v) {
            if (strtolower(trim($value)) == strtolower($v)) {
                return $v;
            }
        }
        return 'Aktif';
    }
}
