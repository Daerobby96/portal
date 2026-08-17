<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    penilaian: Object,
    pegawais: Array,
});

const form = useForm({
    nilai_disiplin: props.penilaian.nilai_disiplin,
    nilai_kinerja: props.penilaian.nilai_kinerja,
    nilai_loyalitas: props.penilaian.nilai_loyalitas,
    nilai_kreativitas: props.penilaian.nilai_kreativitas,
    nilai_kerjasama: props.penilaian.nilai_kerjasama,
    catatan_atasan: props.penilaian.catatan_atasan || '',
    file_dokumen: null,
});

const calculatedTotal = computed(() => {
    const total = (Number(form.nilai_disiplin) + Number(form.nilai_kinerja) + Number(form.nilai_loyalitas) + Number(form.nilai_kreativitas) + Number(form.nilai_kerjasama)) / 5;
    return Math.round(total * 10) / 10;
});

const calculatedPredikat = computed(() => {
    const val = calculatedTotal.value;
    if (val >= 85) return 'Sangat Baik';
    if (val >= 70) return 'Baik';
    if (val >= 55) return 'Cukup';
    return 'Kurang';
});

const submit = () => {
    form.put(`/sdm/penilaian-kinerja/${props.penilaian.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Penilaian Kinerja: ${penilaian.pegawai?.nama}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/penilaian-kinerja" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Penilaian Kinerja
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Tahun {{ penilaian.tahun }} | {{ penilaian.periode }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Penilaian Kinerja
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Penyesuaian skor capaian indikator kinerja pegawai {{ penilaian.pegawai?.nama }}.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            
                            <!-- 5 Skor Aspek Kinerja (0-100) -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-2">
                                    Skor Aspek Penilaian (Skala 0 - 100)
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kedisiplinan (0 - 100)</label>
                                        <input v-model="form.nilai_disiplin" type="number" min="0" max="100" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kinerja & Produktivitas (0 - 100)</label>
                                        <input v-model="form.nilai_kinerja" type="number" min="0" max="100" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Loyalitas & Komitmen (0 - 100)</label>
                                        <input v-model="form.nilai_loyalitas" type="number" min="0" max="100" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono font-bold" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kreativitas & Inovasi (0 - 100)</label>
                                        <input v-model="form.nilai_kreativitas" type="number" min="0" max="100" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono font-bold" />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kerjasama Tim (0 - 100)</label>
                                        <input v-model="form.nilai_kerjasama" type="number" min="0" max="100" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono font-bold" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Catatan & Rekomendasi Atasan Penilai
                                </label>
                                <textarea
                                    v-model="form.catatan_atasan"
                                    rows="3"
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/sdm/penilaian-kinerja"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Penilaian' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Kalkulasi Otomatis</h4>
                        
                        <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-center space-y-1">
                            <span class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider">Rata-rata Skor Total</span>
                            <p class="text-4xl font-black text-indigo-900 font-mono">{{ calculatedTotal }}</p>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-600 text-white">
                                Predikat: {{ calculatedPredikat }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
