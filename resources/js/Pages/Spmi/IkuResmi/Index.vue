<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    ikuList: Array,
    periodes: Array,
    periodeId: [String, Number],
    summary: Object,
    triwulan: String,
    triwulanOptions: Object,
});

const selectedPeriode = ref(props.periodeId || '');
const selectedTriwulan = ref(props.triwulan || 'TAHUNAN');

const filterData = () => {
    router.get('/iku-resmi', {
        periode_id: selectedPeriode.value,
        triwulan: selectedTriwulan.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const calculateAll = () => {
    if (confirm('Hitung otomatis seluruh capaian IKU Kemdiktisaintek sekarang?')) {
        router.post('/iku-resmi/calculate-all', {
            periode_id: selectedPeriode.value,
            triwulan: selectedTriwulan.value,
        });
    }
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'Tercapai':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'Dalam Progress':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="IKU Resmi Kemdiktisaintek" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-award"></i>
                        <span>Kepmendiktisaintek 358/2025</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Indikator Kinerja Utama (IKU) Resmi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Evaluasi kepatuhan 8 IKU Kemdiktisaintek: Lulusan bekerja, MBKM, Dosen berkegiatan tridharma, dan Akreditasi Internasional.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <button
                        @click="calculateAll"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-calculator"></i>
                        <span>Hitung Capaian IKU</span>
                    </button>
                </div>
            </div>

            <!-- Summary KPI Badges -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Total IKU</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-blue-600 uppercase">IKU Wajib</p>
                        <p class="text-xl font-bold text-blue-700">{{ summary?.wajib || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase">Tercapai</p>
                        <p class="text-xl font-bold text-emerald-700">{{ summary?.tercapai || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-amber-600 uppercase">Dalam Progress</p>
                        <p class="text-xl font-bold text-amber-700">{{ summary?.progress || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="text-sm font-bold text-slate-900">Daftar Indikator Kemdiktisaintek</h3>

                    <div class="flex items-center gap-2.5">
                        <select
                            v-model="selectedPeriode"
                            @change="filterData"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option v-for="p in periodes" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.tahun }})</option>
                        </select>

                        <select
                            v-model="selectedTriwulan"
                            @change="filterData"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                        >
                            <option value="TAHUNAN">Tahunan (Penuh)</option>
                            <option value="TW1">Triwulan 1</option>
                            <option value="TW2">Triwulan 2</option>
                            <option value="TW3">Triwulan 3</option>
                            <option value="TW4">Triwulan 4</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">No & Kode</th>
                                <th class="py-3.5 px-6">Nama Indikator Kinerja</th>
                                <th class="py-3.5 px-6">Sifat</th>
                                <th class="py-3.5 px-6">Target</th>
                                <th class="py-3.5 px-6">Realisasi</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="iku in ikuList"
                                :key="iku.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    IKU-{{ iku.nomor_iku }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900 max-w-sm">
                                    {{ iku.nama_indikator || iku.nama }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                        {{ iku.sifat || 'WAJIB' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ iku.target_default || '-' }} {{ iku.satuan }}
                                </td>
                                <td class="py-4 px-6 font-bold text-indigo-600">
                                    {{ iku.nilai_hasil || 0 }} {{ iku.satuan }}
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getStatusBadge(iku.status_capaian)"
                                    >
                                        {{ iku.status_capaian }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/iku-resmi/${iku.id}`"
                                        class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
