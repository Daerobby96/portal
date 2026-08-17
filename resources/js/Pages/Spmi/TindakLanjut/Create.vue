<script setup>
import { ref, watch, nextTick } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
    temuan: Object,
    petugas: Array,
});

const form = useForm({
    temuan_id: props.temuan.id,
    penanggung_jawab_id: props.petugas?.[0]?.id || '',
    analisa_penyebab: '',
    metode_5_whys: '',
    rencana_tindakan: '',
    tindakan_pencegahan: '',
    target_selesai: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    bukti_tindakan: null,
});

const isAnalyzingRca = ref(false);
const isSuggestingAction = ref(false);

// Refs for textareas to trigger auto-resize
const analisaRef = ref(null);
const whysRef = ref(null);
const tindakanRef = ref(null);
const pencegahanRef = ref(null);

const resizeTextarea = (el) => {
    if (!el) return;
    el.style.height = 'auto';
    el.style.height = Math.max(el.scrollHeight, 72) + 'px';
};

const handleInputResize = (e) => {
    resizeTextarea(e.target);
};

// Watch for programmatic changes (such as AI auto-fill)
watch(() => form.analisa_penyebab, () => nextTick(() => resizeTextarea(analisaRef.value)));
watch(() => form.metode_5_whys, () => nextTick(() => resizeTextarea(whysRef.value)));
watch(() => form.rencana_tindakan, () => nextTick(() => resizeTextarea(tindakanRef.value)));
watch(() => form.tindakan_pencegahan, () => nextTick(() => resizeTextarea(pencegahanRef.value)));

const aiAnalyzeRootCause = async () => {
    isAnalyzingRca.value = true;
    try {
        const text = `${props.temuan.kategori}: ${props.temuan.uraian_temuan}. Bukti: ${props.temuan.bukti_objektif || '-'}`;
        const res = await axios.post('/ai/analyze-root-cause', { text });
        if (res.data.status === 'success') {
            form.analisa_penyebab = res.data.data;
            if (!form.metode_5_whys) {
                form.metode_5_whys = `1. Mengapa terjadi? Karena proses belum terdokumentasi optimal.\n2. Mengapa belum terdokumentasi? Kurangnya standarisasi SOP internal.\n3. Mengapa belum terstandarisasi? Belum ada evaluasi berkala di unit.\n4. Mengapa belum ada evaluasi? Beban kerja dan kurangnya reminder jadwal SPMI.\n5. Akar Masalah: Diperlukan sistem checklist operasional terintegrasi.`;
            }
        } else {
            alert(res.data.message || 'Gagal menghasilkan analisis AI.');
        }
    } catch (err) {
        alert('Gagal terhubung dengan layanan AI.');
    } finally {
        isAnalyzingRca.value = false;
    }
};

const aiSuggestAction = async () => {
    isSuggestingAction.value = true;
    try {
        const text = `${props.temuan.uraian_temuan}. Akar Penyebab: ${form.analisa_penyebab}`;
        const res = await axios.post('/ai/suggest-recommendation', { text });
        if (res.data.status === 'success') {
            form.rencana_tindakan = res.data.data;
            form.tindakan_pencegahan = `Melakukan review berkala setiap semester dan menetapkan penanggung jawab pengendali mutu dokumen internal.`;
        } else {
            alert(res.data.message || 'Gagal menghasilkan rekomendasi AI.');
        }
    } catch (err) {
        alert('Gagal terhubung dengan layanan AI.');
    } finally {
        isSuggestingAction.value = false;
    }
};

const handleFileChange = (e) => {
    form.bukti_tindakan = e.target.files[0];
};

const submit = () => {
    form.post('/tindak-lanjut', {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tindak Lanjut Temuan AMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/tindak-lanjut" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Temuan
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            {{ temuan.kode_temuan }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-500/30 text-rose-200 border border-rose-400/30">
                            {{ temuan.kategori }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Formulir Permintaan Tindakan Koreksi (PTK)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Rencanakan tindakan perbaikan akar masalah dan tindakan pencegahan dengan dukungan analisis cerdas Asisten AI SPMI.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <button
                        type="button"
                        @click="aiAnalyzeRootCause"
                        :disabled="isAnalyzingRca"
                        class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-purple-600/30 cursor-pointer disabled:opacity-50"
                    >
                        <i class="bi bi-stars" :class="{ 'animate-spin': isAnalyzingRca }"></i>
                        <span>{{ isAnalyzingRca ? 'Sedang Analisis...' : '✨ AI Analisis RCA' }}</span>
                    </button>
                </div>
            </div>

            <!-- 2-Column Enterprise Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT COLUMN: Main PTK Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- PIC & Deadline -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Penanggung Jawab Tindakan (Auditee) <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.penanggung_jawab_id"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option v-for="u in petugas" :key="u.id" :value="u.id">{{ u.name }} ({{ u.role || 'Staff' }})</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Target Tanggal Selesai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.target_selesai"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>
                            </div>

                            <!-- Root Cause Analysis -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Analisa Akar Penyebab Masalah (Root Cause Analysis) <span class="text-rose-500">*</span>
                                    </label>
                                    <button
                                        type="button"
                                        @click="aiAnalyzeRootCause"
                                        :disabled="isAnalyzingRca"
                                        class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[11px] font-bold shadow-xs hover:opacity-95 transition flex items-center gap-1 cursor-pointer disabled:opacity-50"
                                    >
                                        <i class="bi bi-stars"></i>
                                        <span>{{ isAnalyzingRca ? 'AI Menganalisis...' : '✨ AI Analisis' }}</span>
                                    </button>
                                </div>
                                <textarea
                                    ref="analisaRef"
                                    v-model="form.analisa_penyebab"
                                    @input="handleInputResize"
                                    rows="3"
                                    required
                                    placeholder="Jelaskan faktor mendasar mengapa ketidaksesuaian ini bisa terjadi..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none transition-all"
                                ></textarea>
                            </div>

                            <!-- 5-Whys Method -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Metode Analisa 5-Whys (Opsional)
                                </label>
                                <textarea
                                    ref="whysRef"
                                    v-model="form.metode_5_whys"
                                    @input="handleInputResize"
                                    rows="3"
                                    placeholder="Why 1 -> Why 2 -> Why 3 -> Why 4 -> Why 5 (Akar masalah)"
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none transition-all"
                                ></textarea>
                            </div>

                            <!-- Action Plans -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Rencana Tindakan Korektif <span class="text-rose-500">*</span>
                                        </label>
                                        <button
                                            type="button"
                                            @click="aiSuggestAction"
                                            :disabled="isSuggestingAction"
                                            class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-bold hover:bg-indigo-100 transition flex items-center gap-1 cursor-pointer disabled:opacity-50"
                                        >
                                            <i class="bi bi-magic"></i>
                                            <span>{{ isSuggestingAction ? 'Menyarankan...' : '✨ AI Saran' }}</span>
                                        </button>
                                    </div>
                                    <textarea
                                        ref="tindakanRef"
                                        v-model="form.rencana_tindakan"
                                        @input="handleInputResize"
                                        rows="3"
                                        required
                                        placeholder="Langkah konkrit perbaikan yang akan dilakukan..."
                                        class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none transition-all"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tindakan Pencegahan (Preventive Action)
                                    </label>
                                    <textarea
                                        ref="pencegahanRef"
                                        v-model="form.tindakan_pencegahan"
                                        @input="handleInputResize"
                                        rows="3"
                                        placeholder="Langkah pencegahan agar tidak terulang..."
                                        class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none transition-all"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Lampiran Berkas Pendukung (PDF, Word, Gambar - Max 10MB)
                                </label>
                                <input
                                    type="file"
                                    @change="handleFileChange"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                                />
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/tindak-lanjut"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Kirim Tindak Lanjut' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    
                    <!-- Card 1: Temuan Summary Box -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Detail Temuan Audit</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-rose-100 text-rose-800">
                                {{ temuan.kategori }}
                            </span>
                        </div>

                        <div class="space-y-1.5 text-xs">
                            <p class="font-mono text-indigo-600 font-bold">{{ temuan.kode_temuan }}</p>
                            <p class="font-bold text-slate-900 leading-relaxed">{{ temuan.uraian_temuan }}</p>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 mt-2 space-y-1 text-[11px] text-slate-600">
                                <p><strong>Auditee:</strong> {{ temuan.audit?.unit_yang_diaudit || '-' }}</p>
                                <p><strong>Bukti Objektif:</strong> {{ temuan.bukti_objektif || '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: AI Quality Copilot -->
                    <div class="bg-gradient-to-br from-purple-900 via-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-purple-950/20 border border-white/10 space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-stars text-purple-300 text-base"></i>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-purple-200">AI Root Cause Advisor</h4>
                        </div>
                        <p class="text-xs text-slate-200 leading-relaxed">
                            Gunakan tombol AI di samping kolom untuk merumuskan akar masalah otomatis berbasis metode 5-Whys dan mendapatkan saran tindakan pencegahan yang terstandarisasi.
                        </p>
                    </div>

                    <!-- Card 3: Standar Penutupan PTK -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-check-circle text-emerald-600"></i>
                            <span>Verifikasi Penutupan PTK</span>
                        </h4>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Setelah tindak lanjut dikirim, Auditor akan memverifikasi bukti perbaikan sebelum status temuan berubah menjadi <strong>Closed / Selesai</strong>.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
