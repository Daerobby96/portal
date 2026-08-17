<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    trendData: Array,
    labels: Array,
    tipeFilter: String,
    indikators: Array,
});

const selectedTipe = ref(props.tipeFilter || 'IKU');

const filterTipe = () => {
    router.get('/laporan/tren', {
        tipe: selectedTipe.value,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tren Mutu & Kinerja Historis SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/laporan" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Pusat Laporan
                    </a>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-emerald-300 text-xs font-semibold backdrop-blur-md mb-2">
                        <i class="bi bi-graph-up"></i>
                        <span>Pilar Peningkatan Standar (P5)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tren Perkembangan Mutu Historis
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Analisis komparatif multi-periode untuk melihat pertumbuhan realisasi capaian standar tridharma perguruan tinggi.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="text-xs text-slate-300 font-bold hidden sm:inline">Kategori:</label>
                    <select
                        v-model="selectedTipe"
                        @change="filterTipe"
                        class="px-4 py-2.5 rounded-2xl bg-white/15 text-white border border-white/20 focus:bg-slate-900 font-semibold text-xs cursor-pointer shadow-xs"
                    >
                        <option value="IKU" class="text-slate-900">Indikator Kinerja Utama (IKU)</option>
                        <option value="IKT" class="text-slate-900">Indikator Kinerja Tambahan (IKT)</option>
                        <option value="custom" class="text-slate-900">Custom / Spesifik</option>
                    </select>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tabel Komparasi Capaian Antar Periode Mutu</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Membandingkan target mutu standar dengan realisasi per semester.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold font-mono">
                        {{ trendData?.length || 0 }} Indikator
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Indikator Mutu</th>
                                <th class="py-3.5 px-4 text-center">Target Standar</th>
                                <th v-for="(lbl, idx) in labels" :key="idx" class="py-3.5 px-4 text-center">
                                    {{ lbl }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="(item, idx) in trendData"
                                :key="idx"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <span v-if="item.kode" class="font-mono text-[11px] font-bold text-indigo-600 mr-1.5">
                                        [{{ item.kode }}]
                                    </span>
                                    <span class="font-bold text-slate-900">{{ item.name }}</span>
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-slate-700 font-mono bg-slate-50/50">
                                    {{ item.target }} {{ item.satuan }}
                                </td>
                                <td
                                    v-for="(val, vIdx) in item.data"
                                    :key="vIdx"
                                    class="py-4 px-4 text-center font-extrabold font-mono"
                                    :class="val >= (parseFloat(item.target) || 0) && val > 0 ? 'text-emerald-600 bg-emerald-50/30' : val > 0 ? 'text-amber-600' : 'text-slate-400'"
                                >
                                    {{ val || '-' }}
                                </td>
                            </tr>
                            <tr v-if="!trendData || trendData.length === 0">
                                <td :colspan="(labels?.length || 0) + 2" class="py-12 text-center text-slate-400">
                                    Belum ada data tren historis untuk kategori indikator ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
