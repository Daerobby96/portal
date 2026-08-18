<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    dokumens: Object,
    kategoris: Array,
    standars: Array,
    stats: Object,
    filters: {
        type: Object,
        default: () => ({ search: '', kategori_id: '', status: '', standar_id: '', per_page: '15' }),
    },
});

const searchQuery = ref(props.filters.search || '');
const kategoriFilter = ref(props.filters.kategori_id || '');
const statusFilter = ref(props.filters.status || '');
const standarFilter = ref(props.filters.standar_id || '');
const perPageFilter = ref(props.filters.per_page || '15');

const search = () => {
    router.get('/dokumen', {
        search: searchQuery.value,
        kategori_id: kategoriFilter.value,
        status: statusFilter.value,
        standar_id: standarFilter.value,
        per_page: perPageFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const filterByKategori = (id) => {
    kategoriFilter.value = id;
    search();
};

const showAllData = () => {
    perPageFilter.value = 'all';
    search();
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
        <Head title="Katalog & Dokumen Mutu SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-folder2-open"></i>
                        <span>E-Repositori & Bank Dokumen Mutu SPMI</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Katalog Dokumen & Formulir SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Repositori resmi 4 Buku Dokumen SPMI (Kebijakan, Manual, 31 Standar, dan Master Template Formulir SPMI) Politeknik Krakatau.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        v-if="perPageFilter !== 'all'"
                        @click="showAllData"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/15 cursor-pointer backdrop-blur-md"
                    >
                        <i class="bi bi-list-ul"></i>
                        <span>Tampilkan Semua Dokumen</span>
                    </button>

                    <Link
                        href="/dokumen/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Unggah Dokumen</span>
                    </Link>
                </div>
            </div>

            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Total Dokumen Mutu</span>
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-folder-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mt-2 font-mono">{{ stats?.total || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Tersimpan di repositori resmi</span>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-800">Buku 4: Formulir SPMI</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-700 mt-2 font-mono">{{ stats?.formulir || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Template instrumen & checklist</span>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Status Approved / Aktif</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 mt-2 font-mono">{{ stats?.approved || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Disahkan pimpinan institusi</span>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Kategori Dokumen</span>
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-collection-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-sky-600 mt-2 font-mono">{{ kategoris?.length || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">4 Buku SPMI + SOP + Laporan</span>
                </div>
            </div>

            <!-- Category Quick Pills / Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <button
                    @click="filterByKategori('')"
                    class="px-4 py-2 rounded-2xl text-xs font-bold transition shrink-0 cursor-pointer border"
                    :class="!kategoriFilter ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                >
                    Semua Kategori ({{ stats?.total || 0 }})
                </button>
                <button
                    v-for="k in kategoris"
                    :key="k.id"
                    @click="filterByKategori(k.id)"
                    class="px-4 py-2 rounded-2xl text-xs font-bold transition shrink-0 cursor-pointer border flex items-center gap-2"
                    :class="kategoriFilter == k.id ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/20' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                >
                    <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: k.warna || '#4F46E5' }"></span>
                    <span>{{ k.nama }}</span>
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari kode, judul dokumen, atau unit pemilik..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <select
                            v-model="standarFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium bg-white"
                        >
                            <option value="">Semua Standar Mutu</option>
                            <option v-for="s in standars" :key="s.id" :value="s.id">[{{ s.kode }}] {{ s.nama }}</option>
                        </select>

                        <select
                            v-model="statusFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="approved">Approved</option>
                            <option value="review">Review</option>
                            <option value="draft">Draft</option>
                            <option value="obsolete">Obsolete</option>
                        </select>

                        <div class="flex items-center gap-1.5 pl-1 border-l border-slate-200">
                            <span class="text-[11px] font-bold text-slate-400">Tampil:</span>
                            <select
                                v-model="perPageFilter"
                                @change="search"
                                class="px-2.5 py-2 text-xs rounded-xl border border-indigo-200 bg-indigo-50/40 text-indigo-900 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="15">15 / hal</option>
                                <option value="25">25 / hal</option>
                                <option value="50">50 / hal</option>
                                <option value="all">Semua ({{ dokumens.total }})</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100 text-[11px]">
                            <tr>
                                <th class="py-3.5 px-5">Kode & Kategori</th>
                                <th class="py-3.5 px-5">Judul Dokumen / Formulir Mutu</th>
                                <th class="py-3.5 px-5">Standar Acuan</th>
                                <th class="py-3.5 px-5">Unit Pemilik</th>
                                <th class="py-3.5 px-5">Versi</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="doc in dokumens.data"
                                :key="doc.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-5">
                                    <span class="font-mono font-black text-indigo-600 block text-xs">{{ doc.kode_dokumen }}</span>
                                    <span
                                        v-if="doc.kategori"
                                        class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase mt-0.5 inline-block text-white"
                                        :style="{ backgroundColor: doc.kategori.warna || '#4F46E5' }"
                                    >
                                        {{ doc.kategori.kode }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 max-w-md">
                                    <div class="font-bold text-slate-900 text-xs hover:text-indigo-600 transition">
                                        <Link :href="`/dokumen/${doc.id}`">{{ doc.judul }}</Link>
                                    </div>
                                    <p v-if="doc.keterangan" class="text-[11px] text-slate-500 mt-1 leading-relaxed line-clamp-2">
                                        {{ doc.keterangan }}
                                    </p>
                                </td>

                                <td class="py-4 px-5 text-slate-600 max-w-xs">
                                    <span v-if="doc.standar || (doc.standars && doc.standars.length > 0)" class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100">
                                            {{ (doc.standar || doc.standars[0]).kode }}
                                        </span>
                                        <span class="truncate">{{ (doc.standar || doc.standars[0]).nama }}</span>
                                    </span>
                                    <span v-else class="text-slate-400">-</span>
                                </td>

                                <td class="py-4 px-5 font-semibold text-slate-700">
                                    {{ doc.unit_pemilik || '-' }}
                                </td>

                                <td class="py-4 px-5">
                                    <span class="px-2 py-0.5 rounded-md font-mono font-bold bg-slate-100 text-slate-700 text-[10px]">
                                        v{{ doc.versi }}
                                    </span>
                                </td>

                                <td class="py-4 px-5">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getStatusBadgeClass(doc.status)"
                                    >
                                        {{ doc.status }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/dokumen/${doc.id}`"
                                            class="px-2.5 py-1 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold transition text-xs flex items-center gap-1"
                                            title="Buka Detail Dokumen"
                                        >
                                            <i class="bi bi-eye"></i>
                                            <span>Buka</span>
                                        </Link>
                                        <Link
                                            :href="`/dokumen/${doc.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit Dokumen"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </Link>
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
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-folder2 text-3xl block mb-2 opacity-40"></i>
                                    <span>Tidak ada dokumen mutu yang sesuai dengan filter.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                    <div class="font-medium">
                        <span v-if="perPageFilter === 'all'">
                            Menampilkan seluruh <strong class="text-indigo-600 font-bold">{{ dokumens.total }}</strong> dokumen mutu
                        </span>
                        <span v-else>
                            Menampilkan <strong class="text-slate-900">{{ dokumens.from || 0 }}</strong> - <strong class="text-slate-900">{{ dokumens.to || 0 }}</strong> dari <strong class="text-slate-900">{{ dokumens.total }}</strong> dokumen
                        </span>
                    </div>

                    <div v-if="perPageFilter !== 'all' && dokumens.links && dokumens.links.length > 3" class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in dokumens.links"
                            :key="idx"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 rounded-lg font-medium transition text-xs"
                            :class="link.active ? 'bg-indigo-600 text-white font-bold' : (link.url ? 'hover:bg-slate-100 text-slate-700' : 'text-slate-300 pointer-events-none')"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
