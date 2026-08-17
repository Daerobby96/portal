<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    monitorings: Array,
    stats: Object,
    periodes: Array,
    periodeSel: Object,
});

const selectedPeriodeId = ref(props.periodeSel?.id || '');
const hasilFilter = ref('');

const filterData = () => {
    router.get('/evaluasi', {
        periode_id: selectedPeriodeId.value,
        hasil: hasilFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const getHasilBadge = (hasil) => {
    switch (hasil) {
        case 'tercapai':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'tidak_tercapai':
            return 'bg-rose-50 text-rose-700 border-rose-200';
        case 'perlu_perhatian':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        default:
            return 'bg-slate-100 text-slate-600 border-slate-200';
    }
};

const formatHasilLabel = (hasil) => {
    switch (hasil) {
        case 'tercapai':
            return 'Tercapai';
        case 'tidak_tercapai':
            return 'Tidak Tercapai';
        case 'perlu_perhatian':
            return 'Perlu Perhatian';
        default:
            return 'Belum Evaluasi';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Evaluasi Capaian Standar SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Pilar Evaluasi (P3)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Evaluasi Capaian Standar Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Analisis berkala ketercapaian standar, identifikasi kendala, dan perumusan rekomendasi perbaikan sebelum tahap Audit Mutu Internal.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/evaluasi/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Input Evaluasi Baru</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
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
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-amber-600 uppercase">Perlu Perhatian</p>
                        <p class="text-xl font-bold text-amber-700">{{ stats?.perlu_perhatian || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-rose-600 uppercase">Tidak Tercapai</p>
                        <p class="text-xl font-bold text-rose-700">{{ stats?.tidak_tercapai || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-hourglass"></i>
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
                    <h3 class="text-sm font-bold text-slate-900">Daftar Hasil Realisasi & Evaluasi</h3>

                    <div class="flex items-center gap-2.5">
                        <select
                            v-model="hasilFilter"
                            @change="filterData"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status Evaluasi</option>
                            <option value="tercapai">Tercapai</option>
                            <option value="perlu_perhatian">Perlu Perhatian</option>
                            <option value="tidak_tercapai">Tidak Tercapai</option>
                        </select>

                        <select
                            v-model="selectedPeriodeId"
                            @change="filterData"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
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
                                <th class="py-3.5 px-6">Indikator & Standar</th>
                                <th class="py-3.5 px-6">Target</th>
                                <th class="py-3.5 px-6">Realisasi</th>
                                <th class="py-3.5 px-6">Hasil Evaluasi</th>
                                <th class="py-3.5 px-6">Analisa / Rekomendasi</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="m in monitorings"
                                :key="m.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-indigo-600 block">{{ m.indikator?.kode }}</span>
                                    <span class="font-semibold text-slate-900">{{ m.indikator?.nama || m.indikator?.nama_indikator }}</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Standar: {{ m.indikator?.standar?.nama || '-' }}</p>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-700">
                                    {{ m.indikator?.target || m.indikator?.target_nilai }} {{ m.indikator?.satuan }}
                                </td>
                                <td class="py-4 px-6 font-bold text-indigo-600">
                                    {{ m.nilai_capaian }} {{ m.indikator?.satuan }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getHasilBadge(m.evaluasi?.hasil)"
                                    >
                                        {{ formatHasilLabel(m.evaluasi?.hasil) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 max-w-xs text-slate-600">
                                    <p v-if="m.evaluasi" class="line-clamp-2">{{ m.evaluasi.analisa }}</p>
                                    <span v-else class="text-slate-400 italic">Belum ada evaluasi</span>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <a
                                        :href="`/evaluasi/create?monitoring_id=${m.id}`"
                                        class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                    >
                                        {{ m.evaluasi ? 'Edit Evaluasi' : 'Evaluasi' }}
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!monitorings || monitorings.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data monitoring yang perlu dievaluasi pada periode ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
