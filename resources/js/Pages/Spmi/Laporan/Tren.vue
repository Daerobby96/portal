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
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tren & Perkembangan Mutu Historis
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Analisis grafik komparatif pertumbuhan mutu akademik lintas semester dan siklus SPMI.
                    </p>
                </div>

                <div>
                    <select
                        v-model="selectedTipe"
                        @change="filterTipe"
                        class="px-4 py-2.5 rounded-2xl bg-white/15 text-white border border-white/20 focus:bg-slate-900 font-semibold text-xs"
                    >
                        <option value="IKU" class="text-slate-900">Indikator Kinerja Utama (IKU)</option>
                        <option value="IKT" class="text-slate-900">Indikator Kinerja Tambahan (IKT)</option>
                        <option value="custom" class="text-slate-900">Custom / Khusus</option>
                    </select>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Indikator Mutu</th>
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
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ item.name }}
                                </td>
                                <td
                                    v-for="(val, vIdx) in item.data"
                                    :key="vIdx"
                                    class="py-4 px-4 text-center font-bold text-indigo-600 font-mono"
                                >
                                    {{ val || 0 }}
                                </td>
                            </tr>
                            <tr v-if="!trendData || trendData.length === 0">
                                <td :colspan="(labels?.length || 0) + 1" class="py-12 text-center text-slate-400">
                                    Tidak ada data tren historis.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
