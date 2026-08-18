<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
    monitorings: Array,
    selected: Object,
});

const defaultMonitoringId = props.selected?.id || props.monitorings?.[0]?.id || '';

const form = useForm({
    monitoring_id: defaultMonitoringId,
    analisa: '',
    rekomendasi: '',
    hasil: 'tercapai',
    tanggal_evaluasi: new Date().toISOString().split('T')[0],
});

const isGeneratingAi = ref(false);
const aiSuccessMsg = ref('');

const selectedMonitoring = computed(() => {
    return props.monitorings?.find(m => String(m.id) === String(form.monitoring_id)) || props.selected;
});

// Otomatis sesuaikan hasil default saat ganti monitoring
watch(() => selectedMonitoring.value, (newVal) => {
    if (newVal && newVal.is_tercapai !== undefined) {
        form.hasil = newVal.is_tercapai ? 'tercapai' : 'tidak_tercapai';
    }
}, { immediate: true });

const generateAiAnalysis = async () => {
    if (!form.monitoring_id) {
        alert('Silakan pilih data monitoring terlebih dahulu.');
        return;
    }

    isGeneratingAi.value = true;
    aiSuccessMsg.value = '';

    try {
        const response = await axios.post('/evaluasi/generate-ai', {
            monitoring_id: form.monitoring_id,
        });

        if (response.data && response.data.success) {
            form.analisa = response.data.analisa;
            form.rekomendasi = response.data.rekomendasi;
            if (response.data.hasil_saran) {
                form.hasil = response.data.hasil_saran;
            }
            aiSuccessMsg.value = `Berhasil! Analisa & Rekomendasi di-generate otomatis oleh ${response.data.engine}.`;
        }
    } catch (err) {
        console.error('Error generating AI evaluation:', err);
        alert('Gagal menghasilkan analisis AI. Silakan coba kembali.');
    } finally {
        isGeneratingAi.value = false;
    }
};

const submit = () => {
    form.post('/evaluasi');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Formulir Evaluasi Capaian Standar Mutu" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <Link href="/evaluasi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Evaluasi</span>
                    </Link>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Evaluasi Capaian Standar (P3)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Evaluasi Capaian Standar Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Analisis faktor keberhasilan atau akar kendala mutu sebelum pelaksanaan Audit Mutu Internal (AMI) & RTM.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        type="button"
                        @click="generateAiAnalysis"
                        :disabled="isGeneratingAi || !form.monitoring_id"
                        class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white text-xs font-bold transition flex items-center gap-2.5 shadow-lg shadow-purple-600/30 cursor-pointer disabled:opacity-50 border border-white/20 animate-pulse"
                    >
                        <i class="bi" :class="isGeneratingAi ? 'bi-arrow-clockwise animate-spin' : 'bi-stars'"></i>
                        <span>{{ isGeneratingAi ? 'Menganalisis dengan AI...' : '✨ Generate Analisis AI' }}</span>
                    </button>
                </div>
            </div>

            <!-- AI Success Notification Banner -->
            <div
                v-if="aiSuccessMsg"
                class="p-4 rounded-2xl bg-gradient-to-r from-purple-50 via-indigo-50 to-pink-50 border border-purple-200/80 flex items-center justify-between text-xs text-purple-900 shadow-xs"
            >
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-xl bg-purple-600 text-white flex items-center justify-center text-sm">
                        <i class="bi bi-stars"></i>
                    </div>
                    <span class="font-bold">{{ aiSuccessMsg }}</span>
                </div>
                <button @click="aiSuccessMsg = ''" class="text-purple-400 hover:text-purple-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5 text-xs">
                            <!-- Pilih Data Monitoring -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider">
                                        Pilih Data Monitoring yang Dievaluasi <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-slate-400 font-semibold">Tersedia {{ monitorings?.length || 0 }} data siap telaah</span>
                                </div>
                                <select
                                    v-model="form.monitoring_id"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900 bg-white"
                                >
                                    <option v-for="m in monitorings" :key="m.id" :value="m.id">
                                        [{{ m.indikator?.kode }}] {{ m.indikator?.nama }} — Capaian: {{ m.nilai_capaian }} {{ m.indikator?.unit_pengukuran || '%' }} (Target: {{ m.indikator?.target_nilai || '-' }} {{ m.indikator?.unit_pengukuran || '' }})
                                    </option>
                                </select>
                            </div>

                            <!-- Kesimpulan & Tanggal -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Kesimpulan Hasil Evaluasi <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.hasil"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold bg-white"
                                        :class="{
                                            'text-emerald-700': form.hasil === 'tercapai',
                                            'text-amber-700': form.hasil === 'perlu_perhatian',
                                            'text-rose-700': form.hasil === 'tidak_tercapai'
                                        }"
                                    >
                                        <option value="tercapai">✅ Tercapai (Memenuhi / Melampaui Target)</option>
                                        <option value="perlu_perhatian">⚠️ Perlu Perhatian (Mendekati / Ada Risiko)</option>
                                        <option value="tidak_tercapai">❌ Tidak Tercapai (Di Bawah Standar Minimum)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Evaluasi <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_evaluasi"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>
                            </div>

                            <!-- Analisis Akar Masalah (AI Powered) -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider">
                                        Analisis Akar Masalah & Faktor Kinerja <span class="text-rose-500">*</span>
                                    </label>
                                    <button
                                        type="button"
                                        @click="generateAiAnalysis"
                                        :disabled="isGeneratingAi"
                                        class="text-[11px] font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1 cursor-pointer"
                                    >
                                        <i class="bi bi-stars"></i>
                                        <span>Auto-Fill AI</span>
                                    </button>
                                </div>
                                <textarea
                                    v-model="form.analisa"
                                    rows="5"
                                    required
                                    placeholder="Tuliskan analisis komparatif atau klik tombol '✨ Generate Analisis AI' untuk menghasilkan draf otomatis..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed font-medium"
                                ></textarea>
                            </div>

                            <!-- Rekomendasi Tindakan / Peningkatan Kaizen -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Rekomendasi Tindakan Koreksi (PTK) / Peningkatan Kaizen
                                </label>
                                <textarea
                                    v-model="form.rekomendasi"
                                    rows="4"
                                    placeholder="Rekomendasi langkah korektif, alokasi anggaran RTM, atau program tindak lanjut..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed font-medium"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <Link
                                    href="/evaluasi"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Hasil Evaluasi Mutu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Helper & Live Metric (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <!-- Live Metric Card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-graph-up-arrow text-indigo-600"></i>
                            <span>Data Realisasi Indikator Terpilih</span>
                        </h4>

                        <div v-if="selectedMonitoring" class="space-y-3 text-xs">
                            <div>
                                <span class="font-mono font-black text-indigo-600 text-xs block">{{ selectedMonitoring.indikator?.kode }}</span>
                                <p class="font-bold text-slate-900 mt-0.5">{{ selectedMonitoring.indikator?.nama }}</p>
                            </div>

                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 space-y-2 text-[11px] text-slate-600">
                                <p><strong>Standar:</strong> <span class="text-slate-800 font-semibold">{{ selectedMonitoring.indikator?.standar?.nama || '-' }}</span></p>
                                <p><strong>Unit Pengampu:</strong> <span class="text-slate-800 font-semibold">{{ selectedMonitoring.indikator?.unit_kerja || '-' }}</span></p>
                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                    <span>Target Baseline:</span>
                                    <strong class="text-slate-900 font-mono">{{ selectedMonitoring.indikator?.target_nilai || '-' }} {{ selectedMonitoring.indikator?.unit_pengukuran || '%' }}</strong>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Realisasi Capaian:</span>
                                    <strong class="text-indigo-600 font-mono text-xs">{{ selectedMonitoring.nilai_capaian }} {{ selectedMonitoring.indikator?.unit_pengukuran || '%' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-xs text-slate-400 text-center py-4">
                            Pilih data monitoring untuk melihat detail realisasi.
                        </div>
                    </div>

                    <!-- AI Assistant Card -->
                    <div class="bg-gradient-to-br from-purple-900 via-indigo-950 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-purple-950/20 border border-white/10 space-y-2.5">
                        <div class="flex items-center gap-2 text-purple-300 text-xs font-bold uppercase tracking-wider">
                            <i class="bi bi-stars text-base"></i>
                            <span>AI Evaluation Assistant</span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            AI secara otomatis membandingkan gap deviasi target, menganalisis faktor penyebab, dan merumuskan usulan rekomendasi tindakan koreksi (PTK) berbasis standar SN-Dikti.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
