<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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

const selectedMonitoring = computed(() => {
    return props.monitorings?.find(m => String(m.id) === String(form.monitoring_id)) || props.selected;
});

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
                    <a href="/evaluasi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Evaluasi
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Evaluasi Capaian Standar (P3)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Evaluasi Capaian Standar Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Analisis faktor keberhasilan atau kendala ketidaktercapaian target sebelum tahapan Audit Mutu Internal (AMI).
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Pilih Data Monitoring yang Dievaluasi <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.monitoring_id"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                >
                                    <option v-for="m in monitorings" :key="m.id" :value="m.id">
                                        {{ m.indikator?.kode }} - {{ m.indikator?.nama || m.indikator?.nama_indikator }} (Capaian: {{ m.nilai_capaian }} / Target: {{ m.indikator?.target_nilai || m.indikator?.target }})
                                    </option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Kesimpulan Hasil Evaluasi <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.hasil"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option value="tercapai">Tercapai (Memenuhi / Melampaui Target)</option>
                                        <option value="perlu_perhatian">Perlu Perhatian (Mendekati / Ada Risiko)</option>
                                        <option value="tidak_tercapai">Tidak Tercapai (Di Bawah Standar Minimum)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
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

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Analisis Akar Masalah & Kendala Kinerja <span class="text-rose-500">*</span>
                                </label>
                                <textarea
                                    v-model="form.analisa"
                                    rows="4"
                                    required
                                    placeholder="Jelaskan faktor pendukung keberhasilan atau kendala penyebab ketidaktercapaian target..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Rekomendasi Tindakan / Peningkatan Mutu
                                </label>
                                <textarea
                                    v-model="form.rekomendasi"
                                    rows="3"
                                    placeholder="Saran perbaikan untuk unit kerja atau rekomendasi agenda RTM..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/evaluasi"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Hasil Evaluasi' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-graph-up-arrow text-indigo-600"></i>
                            <span>Data Realisasi Indikator</span>
                        </h4>

                        <div v-if="selectedMonitoring" class="space-y-2.5 text-xs">
                            <p class="font-mono font-bold text-indigo-600">{{ selectedMonitoring.indikator?.kode }}</p>
                            <p class="font-bold text-slate-900">{{ selectedMonitoring.indikator?.nama || selectedMonitoring.indikator?.nama_indikator }}</p>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1 text-[11px] text-slate-600">
                                <p><strong>Target:</strong> {{ selectedMonitoring.indikator?.target || selectedMonitoring.indikator?.target_nilai }} {{ selectedMonitoring.indikator?.satuan }}</p>
                                <p><strong>Capaian:</strong> <span class="font-bold text-indigo-600">{{ selectedMonitoring.nilai_capaian }} {{ selectedMonitoring.indikator?.satuan }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-950/20 border border-white/10 space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-200 flex items-center gap-2">
                            <i class="bi bi-lightbulb"></i>
                            <span>Pedoman Evaluasi P3</span>
                        </h4>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Evaluasi berkala memastikan deviasi capaian terdeteksi dini sebelum pelaksanaan Audit Mutu Internal (AMI) resmi.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
