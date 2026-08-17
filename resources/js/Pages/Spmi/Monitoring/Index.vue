<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    indikators: Array,
    periodes: Array,
    stats: Object,
    periodeSel: Object,
});

const searchQuery = ref('');
const selectedPeriodeId = ref(props.periodeSel?.id || '');

const filterData = () => {
    router.get('/monitoring', {
        search: searchQuery.value,
        periode_id: selectedPeriodeId.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const syncSiakad = () => {
    if (confirm('Apakah Anda ingin menyinkronkan data indikator akademik dari SIAKAD sekarang?')) {
        router.post('/monitoring/sync-siakad');
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Monitoring Capaian IKU/IKT SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Pelaksanaan & Pengukuran (P2)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Monitoring Indikator Mutu (IKU / IKT)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Pencatatan realisasi capaian indikator kinerja utama & tambahan terhadap target baseline standar mutu.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <button
                        @click="syncSiakad"
                        class="px-4 py-2.5 rounded-2xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Sync SIAKAD</span>
                    </button>
                    <a
                        href="/monitoring/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Input Realisasi</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Indikator</p>
                        <p class="text-xl font-bold text-slate-900">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase">Tercapai</p>
                        <p class="text-xl font-bold text-emerald-700">{{ stats?.tercapai || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-rose-600 uppercase">Belum Tercapai</p>
                        <p class="text-xl font-bold text-rose-700">{{ stats?.tidak || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Belum Dievaluasi</p>
                        <p class="text-xl font-bold text-slate-800">{{ stats?.belum_eval || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="filterData"
                            type="text"
                            placeholder="Cari indikator kinerja atau kode..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <select
                            v-model="selectedPeriodeId"
                            @change="filterData"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option v-for="p in periodes" :key="p.id" :value="p.id">
                                Periode: {{ p.nama }} ({{ p.tahun }})
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode & Nama Indikator</th>
                                <th class="py-3.5 px-6">Standar Mutu</th>
                                <th class="py-3.5 px-6">Target Baseline</th>
                                <th class="py-3.5 px-6">Realisasi Capaian</th>
                                <th class="py-3.5 px-6">Status Capaian</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="ind in indikators"
                                :key="ind.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-indigo-600 block">{{ ind.kode }}</span>
                                    <span class="font-semibold text-slate-900">{{ ind.nama || ind.nama_indikator }}</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Unit: {{ ind.unit_kerja || '-' }}</p>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ ind.standar?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ ind.target }} {{ ind.satuan }}
                                </td>
                                <td class="py-4 px-6">
                                    <span v-if="ind.monitorings && ind.monitorings.length > 0" class="font-bold text-indigo-600">
                                        {{ ind.monitorings[0].nilai_capaian }} {{ ind.satuan }}
                                    </span>
                                    <span v-else class="text-slate-400 italic">Belum diinput</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        v-if="ind.monitorings && ind.monitorings.length > 0"
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="ind.monitorings[0].is_tercapai ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'"
                                    >
                                        {{ ind.monitorings[0].is_tercapai ? 'Tercapai' : 'Belum Tercapai' }}
                                    </span>
                                    <span v-else class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                        Belum Ada Data
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/monitoring/create?indikator_id=${ind.id}&periode_id=${selectedPeriodeId}`"
                                        class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                    >
                                        Input Realisasi
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!indikators || indikators.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada indikator mutu ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
