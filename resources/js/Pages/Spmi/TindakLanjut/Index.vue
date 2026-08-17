<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    temuans: Object,
    stats: Object,
});

const searchQuery = ref('');
const kategoriFilter = ref('');

const search = () => {
    router.get('/tindak-lanjut', {
        search: searchQuery.value,
        kategori: kategoriFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const getKategoriBadge = (kategori) => {
    switch (kategori) {
        case 'KTS_Mayor':
            return 'bg-rose-50 text-rose-700 border-rose-200';
        case 'KTS_Minor':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'OB':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tindak Lanjut Temuan Audit" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Pengendalian Mutu (P4)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tindak Lanjut & Koreksi Temuan AMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Monitoring penyelesaian temuan audit, tindakan perbaikan korektif (PTK), dan verifikasi status oleh auditor.
                    </p>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-rose-600 uppercase">Temuan Terbuka</p>
                        <p class="text-xl font-bold text-rose-700">{{ stats?.open || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-amber-600 uppercase">Sedang Diproses</p>
                        <p class="text-xl font-bold text-amber-700">{{ stats?.in_progress || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase">Selesai / Closed</p>
                        <p class="text-xl font-bold text-emerald-700">{{ stats?.closed || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-alarm"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-red-600 uppercase">Lewat Batas Waktu</p>
                        <p class="text-xl font-bold text-red-700">{{ stats?.overdue || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari uraian atau kode temuan..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <select
                            v-model="kategoriFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Kategori</option>
                            <option value="KTS_Mayor">KTS Mayor</option>
                            <option value="KTS_Minor">KTS Minor</option>
                            <option value="OB">Observasi (OB)</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode Temuan</th>
                                <th class="py-3.5 px-6">Uraian Temuan</th>
                                <th class="py-3.5 px-6">Audit & Unit</th>
                                <th class="py-3.5 px-6">Kategori</th>
                                <th class="py-3.5 px-6">Batas Waktu</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="t in temuans.data"
                                :key="t.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    {{ t.kode_temuan }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900 max-w-sm">
                                    <p class="truncate">{{ t.uraian_temuan }}</p>
                                    <p class="text-[10px] text-slate-400 font-normal mt-0.5">Auditor: {{ t.auditor?.name || '-' }}</p>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <span class="font-semibold text-slate-800">{{ t.audit?.unit_yang_diaudit || '-' }}</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ t.audit?.kode_audit }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getKategoriBadge(t.kategori)"
                                    >
                                        {{ t.kategori }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-700">
                                    {{ t.batas_tindak_lanjut || '-' }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/tindak-lanjut/create?temuan_id=${t.id}`"
                                        class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold transition shadow-xs"
                                    >
                                        Tindak Lanjut
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!temuans.data || temuans.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada temuan audit yang memerlukan tindak lanjut saat ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="temuans.links && temuans.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Menampilkan {{ temuans.from || 0 }} - {{ temuans.to || 0 }} dari {{ temuans.total }} data
                    </div>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in temuans.links"
                            :key="idx"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 rounded-lg font-medium transition"
                            :class="link.active ? 'bg-indigo-600 text-white font-bold' : (link.url ? 'hover:bg-slate-100 text-slate-700' : 'text-slate-300 pointer-events-none')"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
