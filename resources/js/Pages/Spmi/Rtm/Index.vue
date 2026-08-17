<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rtms: Array,
    stats: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Rapat Tinjauan Manajemen (RTM)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-people-fill"></i>
                        <span>Evaluasi & Kepemimpinan Mutu</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Rapat Tinjauan Manajemen (RTM)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Dokumentasi agenda tinjauan manajemen, notulensi rapat, rekomendasi pimpinan, dan keputusan strategis peningkatan mutu.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/rtm/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat RTM Baru</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-clipboard2-data"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Temuan</p>
                        <p class="text-xl font-bold text-slate-900">{{ stats?.total_temuan || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-exclamation-octagon"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-rose-600 uppercase">KTS Mayor</p>
                        <p class="text-xl font-bold text-rose-700">{{ stats?.kts_mayor || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-amber-600 uppercase">KTS Minor</p>
                        <p class="text-xl font-bold text-amber-700">{{ stats?.kts_minor || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-check2-all"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase">IKU Tercapai</p>
                        <p class="text-xl font-bold text-emerald-700">{{ stats?.indikator_tercapai || 0 }} / {{ stats?.indikator_total || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- List Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Riwayat Rapat Tinjauan Manajemen</h3>

                <div class="space-y-3">
                    <div
                        v-for="rtm in rtms"
                        :key="rtm.id"
                        class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ rtm.tanggal_rapat }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">{{ rtm.judul_rapat }}</h4>
                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ rtm.agenda || 'Tidak ada catatan agenda khusus.' }}</p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a
                                :href="`/rtm/${rtm.id}`"
                                class="px-3.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold transition"
                            >
                                Lihat RTM & Notulensi
                            </a>
                            <a
                                :href="`/rtm/${rtm.id}/cetak`"
                                class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                title="Cetak PDF RTM"
                            >
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>
                    </div>

                    <div v-if="!rtms || rtms.length === 0" class="py-12 text-center text-slate-400 text-xs">
                        Belum ada data Rapat Tinjauan Manajemen pada periode aktif.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
