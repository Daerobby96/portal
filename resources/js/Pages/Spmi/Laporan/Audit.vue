<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    audits: Array,
    temuanPerKategori: Object,
    periodes: Array,
    periodeId: [String, Number],
});

const selectedPeriode = ref(props.periodeId || '');

const filterPeriode = () => {
    router.get('/laporan/audit', {
        periode_id: selectedPeriode.value,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Laporan Audit Mutu Internal (AMI)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/laporan" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Pusat Laporan
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Laporan Komprehensif Audit Mutu Internal
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Rekapitulasi pelaksanaan AMI, sebaran temuan KTS Mayor/Minor, dan kepatuhan standar mutu per unit kerja.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <select
                        v-model="selectedPeriode"
                        @change="filterPeriode"
                        class="px-3 py-2 text-xs rounded-xl bg-white/15 text-white border border-white/20 focus:bg-slate-900 font-semibold"
                    >
                        <option v-for="p in periodes" :key="p.id" :value="p.id" class="text-slate-900">
                            Periode: {{ p.nama }} ({{ p.tahun }})
                        </option>
                    </select>

                    <a
                        :href="`/laporan/export/pdf/audit?periode_id=${selectedPeriode}`"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-file-earmark-pdf"></i>
                        <span>Cetak Laporan PDF</span>
                    </a>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode & Nama Audit</th>
                                <th class="py-3.5 px-6">Unit Kerja / Auditee</th>
                                <th class="py-3.5 px-6">Ketua Auditor</th>
                                <th class="py-3.5 px-6 text-center">Temuan KTS</th>
                                <th class="py-3.5 px-6 text-center">Status Audit</th>
                                <th class="py-3.5 px-6 text-right">Rincian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="a in audits"
                                :key="a.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <span class="font-mono text-indigo-600 block text-[11px]">{{ a.kode_audit }}</span>
                                    {{ a.nama_audit }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ a.unit_yang_diaudit }}
                                </td>
                                <td class="py-4 px-6 text-slate-800 font-semibold">
                                    {{ a.ketua_auditor?.name || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[11px] border border-rose-200">
                                        {{ a.temuans?.length || 0 }} Temuan
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                        {{ a.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/audit/${a.id}`"
                                        class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                    >
                                        Lihat Kertas Kerja
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!audits || audits.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data audit pada periode ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
