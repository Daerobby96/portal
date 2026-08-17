<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    dokumens: Array,
    perKategori: Object,
    perStatus: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Laporan Dokumen Mutu SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/laporan" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Pusat Laporan
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Laporan Inventaris Dokumen Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Rekapitulasi seluruh Kebijakan, Manual, Standar, SOP, dan Formulir SPMI beserta status approval dan masa berlaku.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/laporan/export/pdf/dokumen"
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
                                <th class="py-3.5 px-6">Kode & Judul Dokumen</th>
                                <th class="py-3.5 px-6">Kategori</th>
                                <th class="py-3.5 px-6">Standar Acuan</th>
                                <th class="py-3.5 px-6 text-center">Versi</th>
                                <th class="py-3.5 px-6 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="d in dokumens"
                                :key="d.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <span class="font-mono text-indigo-600 block text-[11px]">{{ d.kode }}</span>
                                    {{ d.judul }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ d.kategori?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ d.standar?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold font-mono text-slate-700">
                                    v{{ d.versi || '1.0' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ d.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/dokumen/${d.id}`"
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
