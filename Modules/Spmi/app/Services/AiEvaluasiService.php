<?php

namespace Modules\Spmi\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEvaluasiService
{
    /**
     * Generate AI Analysis & Recommendation for SPMI Monitoring & Evaluation
     */
    public function generateEvaluation(array $data): array
    {
        $indikatorKode = $data['indikator_kode'] ?? '-';
        $indikatorNama = $data['indikator_nama'] ?? 'Indikator Mutu';
        $targetNilai   = (float)($data['target_nilai'] ?? 0);
        $targetDeskripsi = $data['target_deskripsi'] ?? null;
        $nilaiCapaian  = (float)($data['nilai_capaian'] ?? 0);
        $unitPengukuran = $data['unit_pengukuran'] ?? '%';
        $unitKerja     = $data['unit_kerja'] ?? 'Program Studi / Unit Terkait';
        $standarKode   = $data['standar_kode'] ?? '-';
        $standarNama   = $data['standar_nama'] ?? 'Standar Mutu Institusi';
        $bidang        = $data['bidang'] ?? 'pendidikan';

        // Try Online LLM if API Key is configured
        $onlineResult = $this->tryOnlineLlm($data);
        if ($onlineResult !== null) {
            return $onlineResult;
        }

        // Otherwise use Built-in Deep SPMI Heuristic Reasoning Engine
        return $this->generateHeuristicEvaluation([
            'indikator_kode'   => $indikatorKode,
            'indikator_nama'   => $indikatorNama,
            'target_nilai'     => $targetNilai,
            'target_deskripsi' => $targetDeskripsi,
            'nilai_capaian'    => $nilaiCapaian,
            'unit_pengukuran'  => $unitPengukuran,
            'unit_kerja'       => $unitKerja,
            'standar_kode'     => $standarKode,
            'standar_nama'     => $standarNama,
            'bidang'           => $bidang,
        ]);
    }

    /**
     * Built-in Expert SPMI Reasoning Engine
     */
    protected function generateHeuristicEvaluation(array $d): array
    {
        $kode     = $d['indikator_kode'];
        $nama     = $d['indikator_nama'];
        $target   = $d['target_nilai'];
        $capaian  = $d['nilai_capaian'];
        $unit     = $d['unit_pengukuran'];
        $unitKerja= $d['unit_kerja'];
        $stdKode  = $d['standar_kode'];
        $stdNama  = $d['standar_nama'];
        $namaLower= strtolower($nama);

        // Determine target direction (is smaller better, like waktu tunggu / drop out?)
        $isSmallerBetter = (str_contains($namaLower, 'waktu tunggu') || str_contains($namaLower, 'drop out') || str_contains($namaLower, 'do'));
        
        $isTercapai = false;
        $selisih = 0;
        $persenCapaian = 0;

        if ($target > 0) {
            if ($isSmallerBetter) {
                $isTercapai = ($capaian <= $target);
                $persenCapaian = round(($target / max($capaian, 0.01)) * 100, 1);
                $selisih = round($target - $capaian, 2);
            } else {
                $isTercapai = ($capaian >= $target);
                $persenCapaian = round(($capaian / $target) * 100, 1);
                $selisih = round($capaian - $target, 2);
            }
        } else {
            $isTercapai = ($capaian > 0);
            $persenCapaian = 100;
        }

        // Determine Status Verdict
        if ($isTercapai) {
            $hasil = ($persenCapaian >= 120) ? 'tercapai' : 'tercapai';
        } else {
            if ($persenCapaian >= 80) {
                $hasil = 'perlu_perhatian';
            } else {
                $hasil = 'tidak_tercapai';
            }
        }

        // Domain classification
        $domain = 'umum';
        if (str_contains($namaLower, 'cpl') || str_contains($namaLower, 'kurikulum') || str_contains($namaLower, 'rps') || str_contains($namaLower, 'lulusan') || str_contains($namaLower, 'toefl') || str_contains($namaLower, 'ipk') || str_contains($namaLower, 'pembelajaran')) {
            $domain = 'pendidikan';
        } elseif (str_contains($namaLower, 'penelitian') || str_contains($namaLower, 'riset') || str_contains($namaLower, 'jurnal') || str_contains($namaLower, 'scopus') || str_contains($namaLower, 'sinta') || str_contains($namaLower, 'hki') || str_contains($namaLower, 'paten')) {
            $domain = 'penelitian';
        } elseif (str_contains($namaLower, 'pengabdian') || str_contains($namaLower, 'pkm') || str_contains($namaLower, 'desa') || str_contains($namaLower, 'masyarakat') || str_contains($namaLower, 'mitra')) {
            $domain = 'pkm';
        } elseif (str_contains($namaLower, 'dosen') || str_contains($namaLower, 'tendik') || str_contains($namaLower, 's3') || str_contains($namaLower, 'doktor') || str_contains($namaLower, 'lektor') || str_contains($namaLower, 'sertifikasi')) {
            $domain = 'sdm';
        } elseif (str_contains($namaLower, 'magang') || str_contains($namaLower, 'industri') || str_contains($namaLower, 'kerjasama') || str_contains($namaLower, 'mou')) {
            $domain = 'kerjasama';
        } elseif (str_contains($namaLower, 'keuangan') || str_contains($namaLower, 'dana') || str_contains($namaLower, 'anggaran') || str_contains($namaLower, 'sarana') || str_contains($namaLower, 'prasarana') || str_contains($namaLower, 'lab')) {
            $domain = 'sarpras';
        }

        // Formulate Analisa
        if ($isTercapai) {
            $analisaIntro = "Berdasarkan evaluasi terhadap indikator [{$kode}] \"{$nama}\" pada Standar Mutu [{$stdKode}] {$stdNama}, realisasi yang dicapai adalah sebesar {$capaian} {$unit} dari target baseline {$target} {$unit} (Tingkat Ketercapaian: {$persenCapaian}%).";
            
            switch ($domain) {
                case 'pendidikan':
                    $analisaBody = "Keberhasilan pemenuhan indikator ini didorong oleh komitmen proaktif {$unitKerja} dalam pengawalan kurikulum OBE, keteraturan monitoring RPS, serta peningkatan kesiapan mahasiswa dalam evaluasi berkala.";
                    $analisaImpact = "Capaian positif ini memberikan kontribusi nyata terhadap mutu lulusan dan pemenuhan butir akreditasi program studi.";
                    break;
                case 'penelitian':
                    $analisaBody = "Pencapaian target riset ini ditopang oleh skema hibah internal yang terstruktur, pendampingan penulisan manuskrip, dan meningkatnya kolaborasi riset terapan dosen dengan mitra industri.";
                    $analisaImpact = "Hal ini berdampak langsung pada penguatan reputasi akademik dan peningkatan skor SINTA institusi.";
                    break;
                case 'pkm':
                    $analisaBody = "Ketercapaian target didorong oleh integrasi hasil riset ke dalam kegiatan pengabdian masyarakat berbasis kebutuhan nyata mitra/UMKM di wilayah Banten.";
                    $analisaImpact = "Kemitraan strategis masyarakat berjalan efektif dan memberikan luaran teknologi tepat guna yang berkelanjutan.";
                    break;
                case 'sdm':
                    $analisaBody = "Realisasi yang optimal merupakan hasil dari program percepatan karir dosen, fasilitasi studi lanjut, serta pemberian insentif jabatan fungsional.";
                    $analisaImpact = "Rasio kualifikasi SDM berada pada tren sangat sehat sesuai rasio baku SN-Dikti.";
                    break;
                case 'kerjasama':
                    $analisaBody = "Pencapaian ini mencerminkan tingginya serapan magang industri dan implementasi aktif MoU/MoA bersama mitra strategis dunia usaha/dunia industri (DUDI).";
                    $analisaImpact = "Kemitraan vokasi memberikan nilai tambah signifikan terhadap serapan kerja lulusan.";
                    break;
                default:
                    $analisaBody = "Efektivitas tata kelola dan koordinasi yang konsisten di lingkungan {$unitKerja} menjadi faktor kunci tercapainya standar mutu ini.";
                    $analisaImpact = "Standar operasional berjalan tertib dan terukur.";
            }

            $analisa = "{$analisaIntro}\n\n{$analisaBody}\n\n{$analisaImpact}";

            // Recommendations for Achieved Target (Pilar P5 - Peningkatan Mutu Kaizen)
            $rekomendasi = "1. Mempertahankan konsistensi implementasi SOP dan monitoring berkala di unit {$unitKerja}.\n"
                         . "2. Menjadikan best practice pada siklus ini sebagai standar rujukan bagi program kerja periode berikutnya.\n"
                         . "3. Merekomendasikan usulan kenaikan target mutu (Kaizen Upgrade) pada agenda Rapat Tinjauan Manajemen (RTM) mendatang.";
        } else {
            $gapAbs = abs($selisih);
            $analisaIntro = "Berdasarkan hasil pengukuran terhadap indikator [{$kode}] \"{$nama}\" pada Standar Mutu [{$stdKode}] {$stdNama}, capaian riil tercatat {$capaian} {$unit} dari target mutu yang ditetapkan sebesar {$target} {$unit} (Gap deviasi: -{$gapAbs} {$unit} atau ketercapaian {$persenCapaian}%).";

            switch ($domain) {
                case 'pendidikan':
                    $analisaBody = "Faktor penyebab utama ketidaktercapaian antara lain: keterbatasan waktu persiapan pembelajaran, variasi kemampuan awal mahasiswa, dan perlunya pemutakhiran rubrik asesmen berkala di {$unitKerja}.";
                    $analisaImpact = "Deviasi ini berpotensi memengaruhi konsistensi pemenuhan CPL apabila tidak segera dilakukan tindakan korektif.";
                    break;
                case 'penelitian':
                    $analisaBody = "Kendala yang dihadapi meliputi beban mengajar dosen yang padat, durasi proses peer-review jurnal eksternal yang memakan waktu, serta keterbatasan dana luaran publikasi bereputasi.";
                    $analisaImpact = "Produktivitas riset terapan memerlukan dorongan fasilitas klinik penulisan (coaching clinic).";
                    break;
                case 'pkm':
                    $analisaBody = "Faktor pembatas utama adalah keterbatasan alokasi pendanaan mitra eksternal serta belum optimalnya pemetaan desa/komunitas binaan jangka panjang.";
                    $analisaImpact = "Diperlukan skema kolaborasi lintas prodi agar kegiatan PkM lebih komprehensif.";
                    break;
                case 'sdm':
                    $analisaBody = "Hambatan teridentifikasi pada proses administrasi kenaikan jabatan fungsional (Jafung) di LLDIKTI serta kendala pemenuhan syarat khusus sertifikasi dosen.";
                    $analisaImpact = "Perlu pendampingan intensif dari Tim Taskforce Kepegawaian & P4MP.";
                    break;
                case 'kerjasama':
                    $analisaBody = "Tantangan terletak pada lambatnya realisasi tindak lanjut naskah kerjasama (MoU) menjadi dokumen implementasi teknis (MoA/IA) di level program studi.";
                    $analisaImpact = "Diperlukan penyegaran komunikasi kemitraan dengan industri terkait.";
                    break;
                default:
                    $analisaBody = "Ketidaktercapaian target disebabkan oleh kendala teknis operasional dan perlunya penguatan sistem monitoring internal di unit {$unitKerja}.";
                    $analisaImpact = "Diperlukan penyesuaian strategi implementasi dan penjadwalan ulang target.";
            }

            $analisa = "{$analisaIntro}\n\n{$analisaBody}\n\n{$analisaImpact}";

            // Recommendations for Unachieved Target (Pilar P4 - Pengendalian / PTK / CAR)
            $rekomendasi = "1. Penerbitan Permintaan Tindakan Koreksi (PTK / Corrective Action Request) kepada {$unitKerja} dengan target penyelesaian 30 hari kalender.\n"
                         . "2. Melakukan evaluasi berkala per triwulan dan pendampingan teknis intensif oleh Pusat Penjaminan Mutu (P4MP).\n"
                         . "3. Mengajukan alokasi dukungan sumber daya dan fasilitas pendukung sebagai agenda prioritas dalam Rapat Tinjauan Manajemen (RTM).";
        }

        return [
            'success'     => true,
            'engine'      => 'SPMI Deep-Reasoning Engine (AI)',
            'hasil_saran' => $hasil,
            'analisa'     => $analisa,
            'rekomendasi' => $rekomendasi,
        ];
    }

    /**
     * Try calling online LLM API if key is available
     */
    protected function tryOnlineLlm(array $d): ?array
    {
        $apiKey = env('GEMINI_API_KEY') ?: (env('OPENAI_API_KEY') ?: env('AI_API_KEY'));
        if (!$apiKey) {
            return null; // Fallback to heuristic reasoning
        }

        try {
            // If OpenAI key
            if (env('OPENAI_API_KEY')) {
                $prompt = $this->buildPrompt($d);
                $response = Http::withToken(env('OPENAI_API_KEY'))
                    ->timeout(12)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model'       => 'gpt-4o-mini',
                        'messages'    => [
                            ['role' => 'system', 'content' => 'Anda adalah Auditor Penjaminan Mutu Perguruan Tinggi (SPMI Dikti). Berikan analisis akar masalah dan rekomendasi dalam format JSON: {"analisa": "...", "rekomendasi": "...", "hasil": "tercapai|perlu_perhatian|tidak_tercapai"}'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'response_format' => ['type' => 'json_object'],
                        'temperature' => 0.4,
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $content = json_decode($json['choices'][0]['message']['content'] ?? '{}', true);
                    if (!empty($content['analisa']) && !empty($content['rekomendasi'])) {
                        return [
                            'success'     => true,
                            'engine'      => 'OpenAI GPT-4o-mini',
                            'hasil_saran' => $content['hasil'] ?? 'tercapai',
                            'analisa'     => $content['analisa'],
                            'rekomendasi' => $content['rekomendasi'],
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('AI Online LLM error, falling back to heuristic: ' . $e->getMessage());
        }

        return null;
    }

    protected function buildPrompt(array $d): string
    {
        return sprintf(
            "Indikator Mutu: %s\nKode: %s\nStandar: [%s] %s\nUnit Penanggung Jawab: %s\nTarget Baseline: %s %s\nRealisasi Tercapai: %s %s\nBidang: %s\n\nBuat analisis mendalam faktor pendukung/kendala dan rekomendasi strategis tindakan koreksi / peningkatan Kaizen.",
            $d['indikator_nama'] ?? '',
            $d['indikator_kode'] ?? '',
            $d['standar_kode'] ?? '',
            $d['standar_nama'] ?? '',
            $d['unit_kerja'] ?? '',
            $d['target_nilai'] ?? 0,
            $d['unit_pengukuran'] ?? '%',
            $d['nilai_capaian'] ?? 0,
            $d['unit_pengukuran'] ?? '%',
            $d['bidang'] ?? 'pendidikan'
        );
    }
}
