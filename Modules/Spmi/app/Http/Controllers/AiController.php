<?php
namespace Modules\Spmi\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Analyze Root Cause
     */
    public function analyzeRootCause(Request $request)
    {
        $request->validate(['text' => 'required|string|min:10']);
        $result = $this->aiService->analyzeRootCause($request->text);
        return response()->json($result);
    }

    /**
     * Suggest Recommendation
     */
    public function suggestRecommendation(Request $request)
    {
        $request->validate(['text' => 'required|string|min:10']);
        $result = $this->aiService->suggestCorrectiveAction($request->text);
        return response()->json($result);
    }

    /**
     * Summarize Text
     */
    public function summarize(Request $request)
    {
        $request->validate(['text' => 'required|string|min:10']);
        $result = $this->aiService->summarizeNarration($request->text);
        return response()->json($result);
    }

    /**
     * Generate Audit Executive Summary
     */
    public function generateAuditSummary(Request $request)
    {
        $audit = \Modules\Spmi\Models\Audit::with('temuans')->findOrFail($request->audit_id);
        $findingTexts = $audit->temuans->map(fn($t) => "- [{$t->kategori}] {$t->uraian_temuan}")->join("\n");
        $unit = $audit->unit_yang_diaudit;
        
        $prompt = "Sebagai pakar SPMI (Sistem Penjaminan Mutu Internal), buat laporan **Ringkasan Eksekutif (Executive Summary)** untuk unit $unit berdasarkan temuan audit berikut:\n\n$findingTexts\n\n" .
                  "STRUKTUR JAWABAN (WAJIB):\n" .
                  "1. Gunakan tag HTML sederhana: <strong> untuk penebalan, <ul> dan <li> untuk poin-poin.\n" .
                  "2. Bagian 1: **Gambaran Kepatuhan Umum** (analisa tingkat ketaatan).\n" .
                  "3. Bagian 2: **Masalah Kritis** (soroti temuan yang paling berdampak).\n" .
                  "4. Bagian 3: **Saran Strategis** (langkah konkret peningkatan mutu).\n\n" .
                  "JANGAN gunakan Markdown triple backticks. Berikan langsung kontennya. Gunakan bahasa Indonesia resmi dan profesional.";
        
        $result = $this->aiService->generate($prompt);
        
        if ($result['status'] === 'success') {
            $audit->update(['ai_summary' => $result['data']]);
        }
        
        return response()->json($result);
    }

    /**
     * Generate RTM Draft
     */
    public function generateRtmDraft(Request $request)
    {
        $request->validate([
            'judul_rapat' => 'required|string',
            'agenda' => 'nullable|string',
            'input_audit_internal' => 'nullable|string',
            'input_umpan_balik' => 'nullable|string',
            'input_kinerja_proses' => 'nullable|string',
            'input_status_tindakan' => 'nullable|string',
            'input_perubahan_sistem' => 'nullable|string',
            'input_rekomendasi' => 'nullable|string',
        ]);

        $prompt = "Sebagai seorang Auditor Ahli dan Konsultan SPMI (Sistem Penjaminan Mutu Internal) Perguruan Tinggi, analisislah input tinjauan Rapat Tinjauan Manajemen (RTM) berikut:\n\n" .
                  "- Judul Rapat: \"{$request->judul_rapat}\"\n" .
                  "- Agenda Utama: \"{$request->agenda}\"\n" .
                  "- 1. Hasil Audit Internal: \"" . ($request->input_audit_internal ?: 'Membahas temuan audit periode berjalan.') . "\"\n" .
                  "- 2. Umpan Balik Pelanggan: \"" . ($request->input_umpan_balik ?: 'Umpan balik dari stakeholder, mahasiswa, dan dosen.') . "\"\n" .
                  "- 3. Kinerja Proses: \"" . ($request->input_kinerja_proses ?: 'Evaluasi pencapaian target IKU/IKT.') . "\"\n" .
                  "- 4. Status Tindakan Perbaikan: \"" . ($request->input_status_tindakan ?: 'Penyelesaian temuan audit sebelumnya.') . "\"\n" .
                  "- 5. Perubahan Sistem Pengelolaan: \"" . ($request->input_perubahan_sistem ?: 'Perubahan regulasi atau kebijakan internal/eksternal.') . "\"\n" .
                  "- 6. Rekomendasi Peningkatan: \"" . ($request->input_rekomendasi ?: 'Rekomendasi tindak lanjut perbaikan.') . "\"\n\n" .
                  "Berdasarkan analisis di atas, rumuskan **Rencana Output & Keputusan RTM** yang logis, strategis, konkret, dan profesional.\n\n" .
                  "STRUKTUR JAWABAN (WAJIB JAWAB DALAM FORMAT JSON RAW):\n" .
                  "Anda WAJIB memberikan respon berupa valid JSON objek dengan key berikut (gunakan Bahasa Indonesia formal, rapi dengan penomoran poin-poin/paragraf pendek yang formal):\n" .
                  "{\n" .
                  "  \"notulensi\": \"(Catatan jalannya rapat/notulensi ringkas yang merangkum poin-poin diskusi dan suasana rapat secara formal)\",\n" .
                  "  \"output_keefektifan\": \"(Rencana strategis dan keputusan manajemen untuk meningkatkan efektivitas penerapan sistem SPMI secara berkelanjutan)\",\n" .
                  "  \"output_perbaikan\": \"(Langkah konkret dan rencana tindakan korektif/preventif untuk meningkatkan mutu layanan/produk akademik dan non-akademik)\",\n" .
                  "  \"output_sumber_daya\": \"(Rencana kebutuhan, penyediaan, atau alokasi sumber daya baru seperti staf, sarpras, pelatihan, atau alokasi anggaran khusus)\",\n" .
                  "  \"keputusan_manajemen\": \"(Kesimpulan akhir rapat, resume keputusan strategis, tenggat waktu penyelesaian, dan penutup formal manajemen)\"\n" .
                  "}\n\n" .
                  "ATURAN KHUSUS:\n" .
                  "1. Jangan berikan teks pembuka atau penutup seperti 'Berikut adalah draf...', '```json', atau penjelasan lainnya. Berikan langsung kurung kurawal pembuka { sampai kurung kurawal penutup } saja agar bisa langsung didecode oleh PHP json_decode().\n" .
                  "2. Pastikan teks di dalam string tidak mengandung karakter kutip ganda (\") kecuali telah di-escape dengan backslash (\\\") untuk menjaga validitas JSON.";

        $result = $this->aiService->generate($prompt);

        if ($result['status'] === 'success') {
            $jsonText = $result['data'];
            
            // Bersihkan jika ada backticks markdown secara tidak sengaja
            $jsonText = preg_replace('/^```(?:json)?\s*/i', '', $jsonText);
            $jsonText = preg_replace('/\s*```$/i', '', $jsonText);
            $jsonText = trim($jsonText);

            $parsed = json_decode($jsonText, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                return response()->json([
                    'status' => 'success',
                    'data' => $parsed
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Format draf AI tidak valid. Silakan coba kembali.',
                    'raw' => $jsonText
                ]);
            }
        }

        return response()->json($result);
    }
}
