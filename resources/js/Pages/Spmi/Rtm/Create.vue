<script setup>
import { ref, watch, nextTick } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
    prefillData: {
        type: Object,
        default: () => ({}),
    },
    stats: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    judul_rapat: 'Rapat Tinjauan Manajemen (RTM) Siklus Mutu',
    tanggal_rapat: new Date().toISOString().split('T')[0],
    agenda: '1. Evaluasi Hasil AMI & Temuan KTS\n2. Umpan Balik Kepuasan Mahasiswa & Dosen\n3. Evaluasi Capaian IKU/IKT\n4. Keputusan Strategis Peningkatan Mutu',
    input_audit_internal: props.prefillData?.input_audit_internal || '',
    input_umpan_balik: props.prefillData?.input_umpan_balik || '',
    input_kinerja_proses: props.prefillData?.input_kinerja_proses || '',
    input_status_tindakan: props.prefillData?.input_status_tindakan || '',
    input_perubahan_sistem: props.prefillData?.input_perubahan_sistem || 'Penyesuaian pedoman kurikulum berbasis OBE dan regulasi Permendikbudristek No. 53 Tahun 2023.',
    input_rekomendasi: props.prefillData?.input_rekomendasi || 'Penguatan sistem penjaminan mutu terintegrasi dan alokasi dana insentif riset dosen.',
    notulensi: '',
    output_keefektifan: '',
    output_perbaikan: '',
    output_sumber_daya: '',
    keputusan_manajemen: '',
    file_absensi: null,
});

const isGeneratingAi = ref(false);
const aiGeneratedCount = ref(0);

// Refs for auto-expanding textareas
const notulensiRef = ref(null);
const keputusanRef = ref(null);
const agendaRef = ref(null);
const keefektifanRef = ref(null);
const perbaikanRef = ref(null);
const sumberDayaRef = ref(null);

const resizeTextarea = (el) => {
    if (!el) return;
    el.style.height = 'auto';
    el.style.height = Math.max(el.scrollHeight, 72) + 'px';
};

const handleInputResize = (e) => {
    resizeTextarea(e.target);
};

// Auto-expand watchers when AI populates fields
watch(() => form.notulensi, () => nextTick(() => resizeTextarea(notulensiRef.value)));
watch(() => form.keputusan_manajemen, () => nextTick(() => resizeTextarea(keputusanRef.value)));
watch(() => form.output_keefektifan, () => nextTick(() => resizeTextarea(keefektifanRef.value)));
watch(() => form.output_perbaikan, () => nextTick(() => resizeTextarea(perbaikanRef.value)));
watch(() => form.output_sumber_daya, () => nextTick(() => resizeTextarea(sumberDayaRef.value)));

const aiGenerateRtmDraft = async () => {
    isGeneratingAi.value = true;
    try {
        const res = await axios.post('/ai/rtm-draft', {
            judul_rapat: form.judul_rapat,
            agenda: form.agenda,
            input_audit_internal: form.input_audit_internal,
            input_umpan_balik: form.input_umpan_balik,
            input_kinerja_proses: form.input_kinerja_proses,
            input_status_tindakan: form.input_status_tindakan,
            input_perubahan_sistem: form.input_perubahan_sistem,
            input_rekomendasi: form.input_rekomendasi,
        });

        if (res.data.status === 'success' && res.data.data) {
            const d = res.data.data;
            if (d.notulensi) form.notulensi = d.notulensi;
            if (d.output_keefektifan) form.output_keefektifan = d.output_keefektifan;
            if (d.output_perbaikan) form.output_perbaikan = d.output_perbaikan;
            if (d.output_sumber_daya) form.output_sumber_daya = d.output_sumber_daya;
            if (d.keputusan_manajemen) form.keputusan_manajemen = d.keputusan_manajemen;
            aiGeneratedCount.value += 1;
        } else {
            alert(res.data.message || 'Gagal menghasilkan draf RTM dengan AI.');
        }
    } catch (err) {
        alert('Gagal terhubung dengan layanan AI RTM.');
    } finally {
        isGeneratingAi.value = false;
    }
};

const handleFileChange = (e) => {
    form.file_absensi = e.target.files[0];
};

const submit = () => {
    form.post('/rtm', {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat Rapat Tinjauan Manajemen (RTM)" />

        <div class="space-y-6">
            <!-- Top Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/rtm" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar RTM
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Pengendalian & Peningkatan (P4 & P5)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Rapat Tinjauan Manajemen (RTM)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Dokumentasikan input hasil evaluasi, dinamika pembahasan, dan keputusan strategis pimpinan sesuai standar SN-Dikti & ISO 9001.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        type="button"
                        @click="aiGenerateRtmDraft"
                        :disabled="isGeneratingAi"
                        class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold transition flex items-center gap-2.5 shadow-lg shadow-purple-600/30 cursor-pointer disabled:opacity-50"
                    >
                        <i class="bi bi-stars text-sm" :class="{ 'animate-spin': isGeneratingAi }"></i>
                        <span>{{ isGeneratingAi ? 'AI Sedang Merumuskan RTM...' : '✨ AI Generate Draf RTM' }}</span>
                    </button>
                </div>
            </div>

            <!-- 2-Column Enterprise Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT COLUMN: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <!-- Section 1: Informasi Dasar RTM -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                                <i class="bi bi-info-circle text-indigo-600"></i>
                                <span>Informasi Pokok Pelaksanaan RTM</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Judul Rapat <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.judul_rapat"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Rapat <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_rapat"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Agenda Pembahasan
                                </label>
                                <textarea
                                    ref="agendaRef"
                                    v-model="form.agenda"
                                    @input="handleInputResize"
                                    rows="3"
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Section 2: Input Tinjauan Manajemen (6 Elemen SN-Dikti / ISO) -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                        <i class="bi bi-file-earmark-spreadsheet text-indigo-600"></i>
                                        <span>Input Tinjauan Manajemen (Bahan RTM)</span>
                                    </h3>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Materi pokok masukan yang dikaji dalam forum rapat pimpinan.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">1. Hasil Audit Mutu Internal (AMI)</label>
                                    <textarea
                                        v-model="form.input_audit_internal"
                                        rows="2"
                                        placeholder="Ringkasan temuan KTS dan kepatuhan standar..."
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">2. Umpan Balik Kepuasan (Survei / Kuesioner)</label>
                                    <textarea
                                        v-model="form.input_umpan_balik"
                                        rows="2"
                                        placeholder="Indeks kepuasan mahasiswa, tendik, dan dosen..."
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">3. Kinerja Proses & Capaian IKU</label>
                                    <textarea
                                        v-model="form.input_kinerja_proses"
                                        rows="2"
                                        placeholder="Indikator kinerja yang tercapai dan belum..."
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">4. Status Tindakan Perbaikan Lalu</label>
                                    <textarea
                                        v-model="form.input_status_tindakan"
                                        rows="2"
                                        placeholder="Progres penyelesaian temuan audit sebelumnya..."
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">5. Perubahan Sistem Pengelolaan / Regulasi</label>
                                    <textarea
                                        v-model="form.input_perubahan_sistem"
                                        rows="2"
                                        placeholder="Perubahan regulasi Dikti atau kebijakan kampus..."
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">6. Rekomendasi Peningkatan Mutu</label>
                                    <textarea
                                        v-model="form.input_rekomendasi"
                                        rows="2"
                                        placeholder="Saran perbaikan dari unit atau pimpinan..."
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Pembahasan, Notulensi & Keputusan Strategis -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 pb-3 border-b border-slate-100">
                                <i class="bi bi-chat-left-quote text-indigo-600"></i>
                                <span>Notulensi & Keputusan Strategis Pimpinan</span>
                            </h3>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Notulensi Jalannya Rapat
                                </label>
                                <textarea
                                    ref="notulensiRef"
                                    v-model="form.notulensi"
                                    @input="handleInputResize"
                                    rows="3"
                                    placeholder="Catatan dinamika pembahasan (dapat di-generate otomatis via AI)..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Keputusan / Kebijakan Strategis Pimpinan <span class="text-rose-500">*</span>
                                </label>
                                <textarea
                                    ref="keputusanRef"
                                    v-model="form.keputusan_manajemen"
                                    @input="handleInputResize"
                                    rows="3"
                                    required
                                    placeholder="Instruksi dan keputusan resmi pimpinan terkait perbaikan mutu..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed overflow-hidden resize-none font-medium"
                                ></textarea>
                            </div>

                            <!-- Additional Outputs Generated by AI -->
                            <div v-if="form.output_keefektifan || form.output_perbaikan || form.output_sumber_daya" class="p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-4">
                                <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-stars text-indigo-600"></i>
                                    <span>Output Tambahan Hasil Rekomendasi AI</span>
                                </h4>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Rencana Peningkatan Keefektifan SPMI</label>
                                    <textarea
                                        ref="keefektifanRef"
                                        v-model="form.output_keefektifan"
                                        @input="handleInputResize"
                                        rows="2"
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-indigo-200 bg-white leading-relaxed overflow-hidden resize-none"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Rencana Tindakan Korektif & Perbaikan Mutu</label>
                                    <textarea
                                        ref="perbaikanRef"
                                        v-model="form.output_perbaikan"
                                        @input="handleInputResize"
                                        rows="2"
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-indigo-200 bg-white leading-relaxed overflow-hidden resize-none"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Kebutuhan Alokasi Sumber Daya / Anggaran</label>
                                    <textarea
                                        ref="sumberDayaRef"
                                        v-model="form.output_sumber_daya"
                                        @input="handleInputResize"
                                        rows="2"
                                        class="w-full px-3 py-2 text-xs rounded-xl border border-indigo-200 bg-white leading-relaxed overflow-hidden resize-none"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <a
                                href="/rtm"
                                class="px-6 py-3 rounded-2xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition"
                            >
                                Batal
                            </a>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-8 py-3 rounded-2xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                            >
                                {{ form.processing ? 'Menyimpan...' : 'Simpan & Publikasikan RTM' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- RIGHT COLUMN: Sticky Sidebars & Guidance (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    
                    <!-- Card 1: AI Assistant Status & Quick Prompt -->
                    <div class="bg-gradient-to-br from-purple-900 via-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-purple-950/20 border border-white/10 space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-purple-500/30 border border-purple-400/30 flex items-center justify-center font-bold text-sm">
                                <i class="bi bi-stars text-purple-300"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-purple-200">AI Quality Copilot</h4>
                                <p class="text-[11px] text-slate-300">Asisten Perumus RTM</p>
                            </div>
                        </div>

                        <p class="text-xs text-slate-200 leading-relaxed">
                            AI dapat otomatis menganalisis 6 input bahan rapat dan menyusun draf notulensi, keefektifan, perbaikan, alokasi sumber daya, dan resume keputusan pimpinan.
                        </p>

                        <button
                            type="button"
                            @click="aiGenerateRtmDraft"
                            :disabled="isGeneratingAi"
                            class="w-full py-2.5 rounded-xl bg-white text-slate-900 hover:bg-slate-100 text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer disabled:opacity-50"
                        >
                            <i class="bi bi-magic"></i>
                            <span>{{ isGeneratingAi ? 'Sedang Merumuskan...' : 'Generate Notulensi & Keputusan' }}</span>
                        </button>
                    </div>

                    <!-- Card 2: Lampiran Berkas Absensi / Dokumentasi -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-paperclip text-indigo-600"></i>
                            <span>Lampiran Daftar Hadir / Absensi</span>
                        </h4>
                        <p class="text-[11px] text-slate-500">Unggah berkas bukti kehadiran peserta rapat atau foto dokumentasi (PDF/Gambar - Max 5MB).</p>
                        
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                        />
                    </div>

                    <!-- Card 3: Panduan Standar SN-Dikti / PPEPP P4 -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-shield-check text-emerald-600"></i>
                            <span>Kriteria Keabsahan RTM (SN-Dikti)</span>
                        </h4>
                        <ul class="text-[11px] text-slate-600 space-y-2 leading-relaxed">
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check2 text-emerald-600 font-bold shrink-0 mt-0.5"></i>
                                <span>Dipimpin langsung oleh Pimpinan Tertinggi (Rektor / Direktur / Ketua).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check2 text-emerald-600 font-bold shrink-0 mt-0.5"></i>
                                <span>Membahas tuntas temuan KTS hasil Audit Mutu Internal periode aktif.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bi bi-check2 text-emerald-600 font-bold shrink-0 mt-0.5"></i>
                                <span>Menghasilkan keputusan tertulis peningkatan standar mutu (Pilar P5).</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
