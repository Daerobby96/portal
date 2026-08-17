<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    dokumens: Object,
    kategoris: Array,
    standars: Array,
    stats: Object,
});

const searchQuery = ref('');
const kategoriFilter = ref('');
const statusFilter = ref('');
const standarFilter = ref('');

const search = () => {
    router.get('/dokumen', {
        search: searchQuery.value,
        kategori_id: kategoriFilter.value,
        status: statusFilter.value,
        standar_id: standarFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteDokumen = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus dokumen mutu ini?')) {
        router.delete(`/dokumen/${id}`);
    }
};

const getStatusBadgeClass = (status) => {
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
        <Head title="Dokumen Mutu SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-folder2-open"></i>
                        <span>E-Repositori Dokumen Mutu</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Daftar Dokumen Mutu SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Arsip dokumen kebijakan, manual mutu, standar SPMI, dan SOP institusi pendidikan tinggi.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/dokumen/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Unggah Dokumen</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Dokumen</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">{{ stats?.total || 0 }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-emerald-600 uppercase">Approved / Aktif</p>
                    <p class="text-xl font-bold text-emerald-700 mt-0.5">{{ stats?.approved || 0 }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-amber-600 uppercase">Review</p>
                    <p class="text-xl font-bold text-amber-700 mt-0.5">{{ stats?.review || 0 }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase">Draft</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5">{{ stats?.draft || 0 }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs col-span-2 sm:col-span-1">
                    <p class="text-[11px] font-semibold text-rose-600 uppercase">Kadaluarsa</p>
                    <p class="text-xl font-bold text-rose-700 mt-0.5">{{ stats?.kadaluarsa || 0 }}</p>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari kode, judul, atau unit kerja..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <select
                            v-model="kategoriFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Kategori</option>
                            <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                        </select>

                        <select
                            v-model="statusFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="approved">Approved</option>
                            <option value="review">Review</option>
                            <option value="draft">Draft</option>
                            <option value="obsolete">Obsolete</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode Dokumen</th>
                                <th class="py-3.5 px-6">Judul Dokumen</th>
                                <th class="py-3.5 px-6">Kategori & Unit</th>
                                <th class="py-3.5 px-6">Versi & Tanggal</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="doc in dokumens.data"
                                :key="doc.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    {{ doc.kode_dokumen }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    <a :href="`/dokumen/${doc.id}`" class="hover:text-indigo-600 transition">
                                        {{ doc.judul }}
                                    </a>
                                    <p class="text-[11px] text-slate-400 font-normal mt-0.5">Oleh: {{ doc.pembuat?.name || '-' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-800">{{ doc.kategori?.nama || '-' }}</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ doc.unit_pemilik || '-' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-700">v{{ doc.versi }}</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ doc.tanggal_terbit || '-' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getStatusBadgeClass(doc.status)"
                                    >
                                        {{ doc.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            :href="`/dokumen/${doc.id}`"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                        >
                                            Buka
                                        </a>
                                        <a
                                            v-if="doc.file_path"
                                            :href="`/dokumen/${doc.id}/download`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Unduh File"
                                        >
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a
                                            :href="`/dokumen/${doc.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit Dokumen"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            @click="deleteDokumen(doc.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!dokumens.data || dokumens.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data dokumen mutu yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="dokumens.links && dokumens.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Menampilkan {{ dokumens.from || 0 }} - {{ dokumens.to || 0 }} dari {{ dokumens.total }} data
                    </div>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in dokumens.links"
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
