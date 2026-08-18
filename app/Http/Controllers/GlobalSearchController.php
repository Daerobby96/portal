<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Sdm\Models\Pegawai;
use App\Models\Mahasiswa;
use Modules\Spmi\Models\Dokumen;
use Modules\Spmi\Models\Standar;
use Modules\ManajemenSurat\Models\SuratMasuk;
use Modules\ManajemenSurat\Models\SuratKeluar;
use Modules\ManajemenRapat\Models\Rapat;
use Modules\ManajemenAset\Models\Aset;
use Modules\Tridharma\Models\Penelitian;
use Modules\TracerStudy\Models\TracerStudy;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // 1. SDM - Pegawai & Dosen
        if (class_exists(Pegawai::class)) {
            $pegawais = Pegawai::where('nama', 'ilike', "%{$q}%")
                ->orWhere('nip', 'ilike', "%{$q}%")
                ->orWhere('email', 'ilike', "%{$q}%")
                ->limit(4)->get();

            foreach ($pegawais as $p) {
                $results[] = [
                    'category' => 'SDM & Dosen',
                    'title'    => $p->nama,
                    'subtitle' => "NIP: " . ($p->nip ?? '-') . " · " . ($p->jenis_pegawai ?? 'Dosen/Pegawai'),
                    'url'      => "/sdm/pegawai/{$p->id}",
                    'icon'     => 'bi-person-badge',
                    'badge'    => 'SDM',
                    'badge_bg' => 'bg-purple-50 text-purple-700',
                ];
            }
        }

        // 2. Data Akademik - Mahasiswa
        if (class_exists(Mahasiswa::class)) {
            $mahasiswas = Mahasiswa::where('nama', 'ilike', "%{$q}%")
                ->orWhere('nim', 'ilike', "%{$q}%")
                ->limit(4)->get();

            foreach ($mahasiswas as $m) {
                $results[] = [
                    'category' => 'Data Akademik',
                    'title'    => $m->nama,
                    'subtitle' => "NIM: {$m->nim} · Status: " . ucfirst($m->status),
                    'url'      => "/mahasiswa/{$m->id}",
                    'icon'     => 'bi-mortarboard',
                    'badge'    => 'Mahasiswa',
                    'badge_bg' => 'bg-sky-50 text-sky-700',
                ];
            }
        }

        // 3. SPMI - Dokumen Mutu & Standar
        if (class_exists(Dokumen::class)) {
            $dokumens = Dokumen::where('judul', 'ilike', "%{$q}%")
                ->orWhere('kode_dokumen', 'ilike', "%{$q}%")
                ->limit(3)->get();

            foreach ($dokumens as $d) {
                $results[] = [
                    'category' => 'Dokumen Mutu SPMI',
                    'title'    => $d->judul,
                    'subtitle' => "Kode: " . ($d->kode_dokumen ?? '-') . " · Status: " . ($d->status ?? '-'),
                    'url'      => "/dokumen/{$d->id}",
                    'icon'     => 'bi-file-earmark-text',
                    'badge'    => 'Dokumen',
                    'badge_bg' => 'bg-indigo-50 text-indigo-700',
                ];
            }
        }

        // 4. Persuratan - Surat Masuk & Keluar
        if (class_exists(SuratMasuk::class)) {
            $surats = SuratMasuk::where('perihal', 'ilike', "%{$q}%")
                ->orWhere('nomor_surat', 'ilike', "%{$q}%")
                ->orWhere('pengirim', 'ilike', "%{$q}%")
                ->limit(3)->get();

            foreach ($surats as $s) {
                $results[] = [
                    'category' => 'Persuratan',
                    'title'    => $s->perihal,
                    'subtitle' => "No: {$s->nomor_surat} · Dari: {$s->pengirim}",
                    'url'      => "/surat-masuk/{$s->id}",
                    'icon'     => 'bi-envelope-paper',
                    'badge'    => 'Surat Masuk',
                    'badge_bg' => 'bg-amber-50 text-amber-700',
                ];
            }
        }

        // 5. Sarpras & Rapat
        if (class_exists(Rapat::class)) {
            $rapats = Rapat::where('judul', 'ilike', "%{$q}%")
                ->orWhere('tempat', 'ilike', "%{$q}%")
                ->limit(3)->get();

            foreach ($rapats as $r) {
                $results[] = [
                    'category' => 'Manajemen Rapat',
                    'title'    => $r->judul,
                    'subtitle' => "Lokasi: {$r->tempat} · Tanggal: " . ($r->tanggal ? date('d M Y', strtotime($r->tanggal)) : '-'),
                    'url'      => "/rapat/{$r->id}",
                    'icon'     => 'bi-calendar-event',
                    'badge'    => 'Rapat',
                    'badge_bg' => 'bg-teal-50 text-teal-700',
                ];
            }
        }

        // 6. Sarpras - Inventaris Aset
        if (class_exists(Aset::class)) {
            $asets = Aset::where('nama_aset', 'ilike', "%{$q}%")
                ->orWhere('kode_aset', 'ilike', "%{$q}%")
                ->limit(3)->get();

            foreach ($asets as $ast) {
                $results[] = [
                    'category' => 'Sarpras & Aset',
                    'title'    => $ast->nama_aset,
                    'subtitle' => "Kode: {$ast->kode_aset} · Kondisi: " . ($ast->kondisi ?? '-'),
                    'url'      => "/aset/{$ast->id}",
                    'icon'     => 'bi-box-seam',
                    'badge'    => 'Aset',
                    'badge_bg' => 'bg-teal-50 text-teal-700',
                ];
            }
        }

        // 7. Tridharma - Penelitian & Riset
        if (class_exists(Penelitian::class)) {
            $penelitians = Penelitian::where('judul', 'ilike', "%{$q}%")
                ->limit(3)->get();

            foreach ($penelitians as $pn) {
                $results[] = [
                    'category' => 'Tridharma Perguruan Tinggi',
                    'title'    => $pn->judul,
                    'subtitle' => "Tahun: {$pn->tahun} · Sumber: " . ($pn->sumber_dana ?? '-'),
                    'url'      => "/penelitian",
                    'icon'     => 'bi-journal-bookmark',
                    'badge'    => 'Penelitian',
                    'badge_bg' => 'bg-rose-50 text-rose-700',
                ];
            }
        }

        // 8. Tracer Study - Alumni
        if (class_exists(TracerStudy::class)) {
            $tracers = TracerStudy::where('nama', 'ilike', "%{$q}%")
                ->orWhere('nim', 'ilike', "%{$q}%")
                ->orWhere('perusahaan', 'ilike', "%{$q}%")
                ->limit(3)->get();

            foreach ($tracers as $ts) {
                $results[] = [
                    'category' => 'Tracer Study & Alumni',
                    'title'    => $ts->nama,
                    'subtitle' => "NIM: {$ts->nim} · Karir: " . ($ts->status_kerja ?? '-') . " (" . ($ts->perusahaan ?? '-') . ")",
                    'url'      => "/tracer-study?search=" . urlencode($ts->nim),
                    'icon'     => 'bi-person-check',
                    'badge'    => 'Alumni',
                    'badge_bg' => 'bg-emerald-50 text-emerald-700',
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}

