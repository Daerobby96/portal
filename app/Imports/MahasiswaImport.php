<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Modules\DataMaster\Models\Periode;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class MahasiswaImport implements ToModel, WithHeadingRow, SkipsOnError, WithCustomCsvSettings
{
    use SkipsErrors;

    protected array $prodiCache = [];
    protected array $periodeCache = [];

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function model(array $row): ?Mahasiswa
    {
        if (empty($row['nim']) || empty($row['nama'])) return null;

        $prodiId = $this->resolveProdi($row['program_studi'] ?? null);
        $periodeId = $this->resolvePeriode($row['periode_masuk'] ?? null);

        // Map status
        $statusRaw = strtolower(trim($row['status_mahasiswa'] ?? 'aktif'));
        $status = Mahasiswa::STATUS_AKTIF;
        if (str_contains($statusRaw, 'lulus')) $status = Mahasiswa::STATUS_LULUS;
        if (str_contains($statusRaw, 'cuti')) $status = Mahasiswa::STATUS_CUTI;
        if (str_contains($statusRaw, 'do') || str_contains($statusRaw, 'drop')) $status = Mahasiswa::STATUS_DO;
        if (str_contains($statusRaw, 'mengundurkan')) $status = Mahasiswa::STATUS_MENGUNDURKAN_DIRI;

        $ipk = null;
        if (isset($row['ipk']) && $row['ipk'] !== '') {
            $ipk = min(4.00, max(0.00, (float) str_replace(',', '.', $row['ipk'])));
        }

        $ipk_asal = null;
        if (isset($row['ipk_asal']) && $row['ipk_asal'] !== '') {
            $ipk_asal = (string) str_replace(',', '.', $row['ipk_asal']);
        }

        // Tgl Parse helper
        $tgl_lahir = $this->parseDate($row['tgl_lahir'] ?? null);
        $tgl_daftar = $this->parseDate($row['tgl_daftar'] ?? null);

        // Ekstrak Angkatan dari Periode Masuk (misal "2025 Ganjil" -> 2025)
        $angkatan = null;
        if (!empty($row['periode_masuk'])) {
            preg_match('/(\d{4})/', $row['periode_masuk'], $m);
            if (!empty($m[1])) {
                $angkatan = (int) $m[1];
            }
        }

        // Create or update Mahasiswa based on NIM
        $mahasiswa = Mahasiswa::firstOrNew(['nim' => trim($row['nim'])]);

        $mahasiswa->fill([
            'nama'              => trim($row['nama']),
            'jenis_kelamin'     => in_array(strtoupper(substr($row['jenis_kelamin'] ?? '', 0, 1)), ['L','P'])
                                    ? strtoupper(substr($row['jenis_kelamin'], 0, 1))
                                    : null,
            'no_hp'             => $row['hp'] ?? $row['telepon'] ?? null,
            'email'             => $row['email'] ?? null,
            'prodi_id'          => $prodiId,
            'periode_id'        => $periodeId,
            'angkatan'          => $angkatan,
            'jalur_masuk'       => $row['jalur_penerimaan'] ?? null,
            'status'            => $status,
            
            // Akademik Tambahan
            'sistem_kuliah'     => $row['sistem_kuliah'] ?? null,
            'gelombang_daftar'  => $row['gelombang_daftar'] ?? null,
            'is_transfer'       => $row['transfer_tidak'] ?? 'Tidak',
            'universitas_asal'  => $row['universitas_asal'] ?? null,
            'nim_asal'          => $row['nim_asal'] ?? null,
            'ipk_asal'          => $ipk_asal,
            'kurikulum'         => $row['kurikulum'] ?? null,
            
            // Biodata Pribadi
            'agama'             => $row['agama'] ?? null,
            'kewarganegaraan'   => $row['kewarganegaraan'] ?? null,
            'alamat'            => $row['alamat'] ?? null,
            'telepon'           => $row['telepon'] ?? null,
            'tempat_lahir'      => $row['tempat_lahir'] ?? null,
            'tanggal_lahir'     => $tgl_lahir,
            'kodepos'           => $row['kodepos'] ?? null,
            'golongan_darah'    => $row['golongan_darah'] ?? null,
            'status_nikah'      => $row['status_nikah'] ?? null,
            'nik'               => str_replace(',', '.', $row['no_ktpnik'] ?? ''), // In CSV sometimes it's 3,60406E+15 due to excel formatting, best effort
            'no_kk'             => str_replace(',', '.', $row['no_kk'] ?? ''),
            'rt'                => $row['rt'] ?? null,
            'rw'                => $row['rw'] ?? null,
            'dusun'             => $row['dusun'] ?? null,
            'kelurahan'         => $row['desakelurahan'] ?? null,
            'kecamatan'         => $row['kecamatan'] ?? null,
            'kota'              => $row['kota'] ?? null,
            'provinsi'          => $row['propinsi'] ?? null,
            'tgl_daftar'        => $tgl_daftar,

            // Data Ayah
            'nama_ayah'         => $row['nama_ayah'] ?? null,
            'alamat_ayah'       => $row['alamat_ayah'] ?? null,
            'telp_ayah'         => $row['telp_ayah'] ?? null,
            'tgl_lahir_ayah'    => $row['tgl_lahir_ayah'] ?? null,
            'pendidikan_ayah'   => $row['pendidikan_ayah'] ?? null,
            'pekerjaan_ayah'    => $row['pekerjaan_ayah'] ?? null,
            'penghasilan_ayah'  => $row['penghasilan_ayah'] ?? null,

            // Data Ibu
            'nama_ibu'          => $row['nama_ibu'] ?? null,
            'alamat_ibu'        => $row['alamat_ibu'] ?? null,
            'telp_ibu'          => $row['telp_ibu'] ?? null,
            'tgl_lahir_ibu'     => $row['tgl_lahir_ibu'] ?? null,
            'pendidikan_ibu'    => $row['pendidikan_ibu'] ?? null,
            'pekerjaan_ibu'     => $row['pekerjaan_ibu'] ?? null,
            'penghasilan_ibu'   => $row['penghasilan_ibu'] ?? null,

            // Data Wali
            'nama_wali'         => $row['nama_wali'] ?? null,
            'alamat_wali'       => $row['alamat_wali'] ?? null,
            'telp_wali'         => $row['telp_wali'] ?? null,
            'tgl_lahir_wali'    => $row['tgl_wali'] ?? null, // note header is tgl_wali
            'pendidikan_wali'   => $row['pendidikan_wali'] ?? null,
            'pekerjaan_wali'    => $row['pekerjaan_wali'] ?? null,
            'penghasilan_wali'  => $row['penghasilan_wali'] ?? null,
        ]);

        $mahasiswa->save();

        return $mahasiswa;
    }

    protected function resolveProdi(?string $namaProdi): ?int
    {
        if (!$namaProdi) return null;

        $key = strtolower(trim($namaProdi));
        if (isset($this->prodiCache[$key])) return $this->prodiCache[$key];

        $prodi = ProgramStudi::where('nama', 'ilike', "%{$namaProdi}%")
                    ->orWhere('kode', $namaProdi)
                    ->first();
                    
        if (!$prodi) {
            $kode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $namaProdi), 0, 3)) . rand(100, 999);
            $prodi = ProgramStudi::create([
                'nama' => trim($namaProdi),
                'kode' => $kode,
                'jenjang' => 'D3',
                'is_aktif' => true,
            ]);
        }

        $this->prodiCache[$key] = $prodi?->id;
        return $this->prodiCache[$key];
    }

    protected function resolvePeriode(?string $namaPeriode): ?int
    {
        if (!$namaPeriode) return null;

        $key = strtolower(trim($namaPeriode));
        if (isset($this->periodeCache[$key])) return $this->periodeCache[$key];

        $periode = Periode::where('nama', 'ilike', "%{$namaPeriode}%")->first();
        if(!$periode) {
            // Coba parsing tahun saja dari "2025 Ganjil"
            preg_match('/(\d{4})/', $namaPeriode, $matches);
            if(count($matches) > 1) {
                $periode = Periode::where('tahun', $matches[1])->first();
            }
        }

        $this->periodeCache[$key] = $periode?->id;
        return $this->periodeCache[$key];
    }

    protected function parseDate(?string $dateStr): ?string
    {
        if (!$dateStr) return null;
        try {
            // Format di CSV: 31/08/2007 (d/m/Y)
            if (str_contains($dateStr, '/')) {
                return Carbon::createFromFormat('d/m/Y', $dateStr)->format('Y-m-d');
            }
            return Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

}

