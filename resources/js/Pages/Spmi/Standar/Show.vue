<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    standar: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`${standar.kode} - ${standar.nama}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/standar" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Standar
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 font-mono font-bold text-xs">
                            {{ standar.kode }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            {{ standar.nama }}
                        </h1>
                    </div>
                    <p class="text-xs text-slate-300">
                        Bidang: <span class="capitalize font-bold">{{ standar.bidang }}</span> | Jenis: <span class="capitalize font-bold">{{ standar.jenis }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <a
                        :href="`/standar/${standar.id}/edit`"
                        class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="bi bi-pencil"></i>
                        <span>Edit Standar</span>
                    </a>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Deskripsi & Info (1 col) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Pernyataan Standar</h3>
                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 min-h-[120px]">
                        {{ standar.deskripsi || 'Tidak ada deskripsi rinci untuk standar ini.' }}
                    </p>

                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Status:</span>
                            <span :class="standar.is_aktif ? 'text-emerald-600 font-bold' : 'text-slate-400'">
                                {{ standar.is_aktif ? 'Aktif Digunakan' : 'Non-Aktif' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Dokumen:</span>
                            <span class="font-bold text-slate-900">{{ standar.dokumens?.length || 0 }} Dokumen</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Indikator:</span>
                            <span class="font-bold text-slate-900">{{ standar.indikators?.length || 0 }} Indikator</span>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Mutu Terkait (2 cols) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs lg:col-span-2">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Dokumen Mutu Terkait</h3>
                            <p class="text-xs text-slate-400">SOP, Kebijakan, dan Manual yang mengacu pada standar ini.</p>
                        </div>
                        <a href="/dokumen" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua</a>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="doc in standar.dokumens"
                            :key="doc.id"
                            class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-100 flex items-center justify-between gap-4"
                        >
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="font-mono text-[11px] font-bold text-indigo-600">{{ doc.kode_dokumen }}</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-white border border-slate-200 text-slate-600">
                                        {{ doc.kategori?.nama || 'Dokumen' }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-slate-800">{{ doc.judul }}</p>
                                <p class="text-[10px] text-slate-400">Unit: {{ doc.unit_pemilik }}</p>
                            </div>
                            <a
                                :href="`/dokumen/${doc.id}`"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition shrink-0"
                            >
                                Buka &rarr;
                            </a>
                        </div>

                        <div v-if="!standar.dokumens || standar.dokumens.length === 0" class="py-10 text-center text-xs text-slate-400">
                            Belum ada dokumen mutu yang ditautkan ke standar ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
