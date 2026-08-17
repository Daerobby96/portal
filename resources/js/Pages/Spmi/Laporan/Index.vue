<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    ringkasan: Object,
    periodes: Array,
    periode: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pusat Laporan SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Pelaporan & Ekspor Mutu</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Pusat Laporan & Eksekutif SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Rekapitulasi komprehensif audit mutu internal, status dokumen, capaian indikator, dan laporan EDOM.
                    </p>
                </div>
            </div>

            <!-- Report Category Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Laporan Audit -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-4 font-bold">
                            <i class="bi bi-clipboard2-check"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Laporan Audit AMI</h3>
                        <p class="text-xs text-slate-500 mb-4">Rekapitulasi audit, KTS Mayor/Minor, dan status temuan.</p>
                        <div class="text-xs font-semibold text-slate-700 space-y-1">
                            <p>Total Audit: {{ ringkasan?.audit?.total || 0 }}</p>
                            <p>Selesai: {{ ringkasan?.audit?.selesai || 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="/laporan/audit" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Laporan &rarr;</a>
                        <a href="/laporan/export/pdf/audit" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600" title="PDF">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Laporan Dokumen -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl mb-4 font-bold">
                            <i class="bi bi-folder2"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Laporan Dokumen Mutu</h3>
                        <p class="text-xs text-slate-500 mb-4">Statistik dokumen approved, draft, dan masa kadaluarsa.</p>
                        <div class="text-xs font-semibold text-slate-700 space-y-1">
                            <p>Total Dokumen: {{ ringkasan?.dokumen?.total || 0 }}</p>
                            <p>Approved: {{ ringkasan?.dokumen?.approved || 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="/laporan/dokumen" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Laporan &rarr;</a>
                        <a href="/laporan/export/pdf/dokumen" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600" title="PDF">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Laporan Monitoring -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4 font-bold">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Laporan Monitoring IKU</h3>
                        <p class="text-xs text-slate-500 mb-4">Capaian realisasi indikator terhadap target standar mutu.</p>
                        <div class="text-xs font-semibold text-slate-700 space-y-1">
                            <p>Total Terukur: {{ ringkasan?.monitoring?.total || 0 }}</p>
                            <p>Tercapai: {{ ringkasan?.monitoring?.tercapai || 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="/laporan/monitoring" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Laporan &rarr;</a>
                        <a href="/laporan/export/pdf/monitoring" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600" title="PDF">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 4: Laporan Tren Kinerja -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4 font-bold">
                            <i class="bi bi-activity"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Tren Mutu Tahunan</h3>
                        <p class="text-xs text-slate-500 mb-4">Perkembangan siklus mutu antar periode secara historis.</p>
                        <div class="text-xs font-semibold text-slate-700 space-y-1">
                            <p>EDOM Dosen: {{ Number(ringkasan?.edom?.avg || 0).toFixed(2) }} / 4.00</p>
                            <p>Temuan Terbuka: {{ ringkasan?.temuan?.open || 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="/laporan/tren" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Tren &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
