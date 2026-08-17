<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    dokumen: Object,
});

const deleteDokumen = () => {
    if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
        router.delete(`/dokumen/${props.dokumen.id}`);
    }
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'approved':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'review':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'obsolete':
            return 'bg-rose-50 text-rose-700 border-rose-200';
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`${dokumen.kode_dokumen} - ${dokumen.judul}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/dokumen" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Dokumen
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 font-mono font-bold text-xs">
                            {{ dokumen.kode_dokumen }}
                        </span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                            :class="getStatusBadge(dokumen.status)"
                        >
                            {{ dokumen.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1">
                        {{ dokumen.judul }}
                    </h1>
                    <p class="text-xs text-slate-300 mt-1">
                        Kategori: <span class="font-bold">{{ dokumen.kategori?.nama || '-' }}</span> | Unit: <span class="font-bold">{{ dokumen.unit_pemilik }}</span> | Versi: <span class="font-bold">v{{ dokumen.versi }}</span>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a
                        v-if="dokumen.file_path"
                        :href="`/dokumen/${dokumen.id}/download`"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-indigo-600/30"
                    >
                        <i class="bi bi-download"></i>
                        <span>Unduh Berkas</span>
                    </a>
                    <a
                        :href="`/dokumen/${dokumen.id}/edit`"
                        class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="bi bi-pencil"></i>
                        <span>Edit</span>
                    </a>
                    <button
                        @click="deleteDokumen"
                        class="px-4 py-2 rounded-xl bg-rose-600/30 hover:bg-rose-600/50 text-rose-200 text-xs font-bold border border-rose-500/30 transition flex items-center gap-1.5 cursor-pointer"
                    >
                        <i class="bi bi-trash"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Info Metadata (1 col) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100">Informasi Dokumen</h3>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Tanggal Terbit</span>
                            <span class="font-semibold text-slate-800">{{ dokumen.tanggal_terbit || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Tanggal Kadaluarsa</span>
                            <span class="font-semibold" :class="dokumen.tanggal_kadaluarsa ? 'text-amber-700' : 'text-slate-800'">
                                {{ dokumen.tanggal_kadaluarsa || 'Tidak Ada Batas Kadaluarsa' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Diupload Oleh</span>
                            <span class="font-semibold text-slate-800">{{ dokumen.pembuat?.name || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Tipe Berkas</span>
                            <span class="font-mono font-bold uppercase text-indigo-600">{{ dokumen.file_type || 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Standar Mutu Terkait & Keterangan (2 cols) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs lg:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Standar Mutu yang Mengacu</h3>
                        <div class="flex flex-wrap gap-2">
                            <a
                                v-for="s in dokumen.standars"
                                :key="s.id"
                                :href="`/standar/${s.id}`"
                                class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition"
                            >
                                <span class="font-mono font-bold text-indigo-600 mr-1">{{ s.kode }}</span>
                                <span>{{ s.nama }}</span>
                            </a>
                            <div v-if="!dokumen.standars || dokumen.standars.length === 0" class="text-xs text-slate-400 py-2">
                                Dokumen ini belum ditautkan ke standar mutu manapun.
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Keterangan / Catatan Tambahan</h3>
                        <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 min-h-[100px]">
                            {{ dokumen.keterangan || 'Tidak ada catatan tambahan.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
