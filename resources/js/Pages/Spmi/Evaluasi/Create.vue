<script setup>
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

const submit = () => {
    form.post('/evaluasi');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Formulir Evaluasi Capaian Standar" />

        <div class="max-w-3xl mx-auto space-y-6">
            <div>
                <a href="/evaluasi" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Evaluasi
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Evaluasi Capaian Standar Mutu</h1>
                <p class="text-xs text-slate-500 mt-0.5">Analisis hasil realisasi dan tentukan rekomendasi perbaikan sebelum AMI.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pilih Data Monitoring yang Dievaluasi <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.monitoring_id"
                            required
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Analisis Akar Masalah & Kinerja <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            v-model="form.analisa"
                            rows="4"
                            required
                            placeholder="Jelaskan faktor pendukung keberhasilan atau kendala penyebab ketidaktercapaian target..."
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/evaluasi"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Hasil Evaluasi' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
