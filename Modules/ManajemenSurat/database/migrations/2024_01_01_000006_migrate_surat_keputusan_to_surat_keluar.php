<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if old surat_keputusans table exists
        if (!Schema::hasTable('surat_keputusans')) {
            return;
        }

        // Insert jenis surat SK Yayasan dan SK PT jika belum ada
        $skYayasan = DB::table('jenis_surat')->where('kode', 'SK-YYS')->first();
        if (!$skYayasan) {
            DB::table('jenis_surat')->insert([
                'kode' => 'SK-YYS',
                'nama' => 'Surat Keputusan Yayasan',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.sk_yayasan',
                'keterangan' => 'Surat Keputusan yang dikeluarkan oleh Yayasan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $skYayasanId = DB::getPdo()->lastInsertId();
        } else {
            $skYayasanId = $skYayasan->id;
        }

        $skPt = DB::table('jenis_surat')->where('kode', 'SK-PT')->first();
        if (!$skPt) {
            DB::table('jenis_surat')->insert([
                'kode' => 'SK-PT',
                'nama' => 'Surat Keputusan Perguruan Tinggi',
                'kategori' => 'keluar',
                'template_path' => 'manajemen-surat.pdf.sk_pt',
                'keterangan' => 'Surat Keputusan yang dikeluarkan oleh Perguruan Tinggi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $skPtId = DB::getPdo()->lastInsertId();
        } else {
            $skPtId = $skPt->id;
        }

        // Migrate data from surat_keputusans to surat_keluar
        $suratKeputusans = DB::table('surat_keputusans')->get();
        
        foreach ($suratKeputusans as $sk) {
            $jenisId = $sk->jenis_sk === 'yayasan' ? $skYayasanId : $skPtId;
            
            DB::table('surat_keluar')->insert([
                'jenis_surat_id' => $jenisId,
                'nomor_surat' => $sk->nomor_sk,
                'nomor_agenda' => null,
                'perihal' => $sk->tentang,
                'isi_surat' => $sk->isi_sk,
                'tanggal_surat' => $sk->tanggal_ditetapkan,
                'tujuan' => 'Umum',
                'alamat_tujuan' => null,
                'penandatangan_nama' => $sk->penandatangan_nama,
                'penandatangan_jabatan' => $sk->penandatangan_jabatan,
                'penandatangan_nip' => null,
                'jumlah_lampiran' => 0,
                'keterangan_lampiran' => null,
                'file_path' => $sk->file_path,
                'status' => 'published',
                'catatan' => 'Migrasi dari tabel surat_keputusans',
                'created_by' => $sk->created_by,
                'approved_by' => $sk->created_by,
                'approved_at' => $sk->created_at,
                'created_at' => $sk->created_at,
                'updated_at' => $sk->updated_at,
                'deleted_at' => null,
            ]);
        }

        // Optional: Drop old table after migration (uncomment if you want to remove old table)
        // Schema::dropIfExists('surat_keputusans');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration safely
        // Data has been merged into surat_keluar table
    }
};
