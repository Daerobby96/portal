<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rTM: Object,
    findingStats: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="rTM.judul_rapat" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/rtm" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar RTM
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 text-xs font-bold">
                            {{ rTM.tanggal_rapat }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1">
                        {{ rTM.judul_rapat }}
                    </h1>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <a
                        :href="`/rtm/${rTM.id}/cetak`"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-indigo-600/30"
                    >
                        <i class="bi bi-printer"></i>
                        <span>Cetak Notulensi PDF</span>
                    </a>
                </div>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Agenda Rapat</h3>
                    <p class="text-xs text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line">
                        {{ rTM.agenda || 'Tidak ada catatan agenda.' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Notulensi Pembahasan</h3>
                    <p class="text-xs text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line">
                        {{ rTM.notulensi || 'Belum ada notulensi tertulis.' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Keputusan & Kebijakan Strategis Pimpinan</h3>
                    <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200 text-xs text-indigo-950 font-semibold leading-relaxed whitespace-pre-line">
                        {{ rTM.keputusan_manajemen || 'Belum ada keputusan tertulis.' }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
