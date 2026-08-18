<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    indikators: Object,
    standars: Array,
    summary: Object,
    filters: {
        type: Object,
        default: () => ({ search: '', standar_id: '', tipe: '', per_page: '15' }),
    },
});

const searchQuery = ref(props.filters.search || '');
const standarFilter = ref(props.filters.standar_id || '');
const tipeFilter = ref(props.filters.tipe || '');
const perPageFilter = ref(props.filters.per_page || '15');

const search = () => {
    router.get('/indikator-kinerja', {
        search: searchQuery.value,
        standar_id: standarFilter.value,
        tipe: tipeFilter.value,
        per_page: perPageFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const showAllData = () => {
    perPageFilter.value = 'all';
    search();
};

const deleteIndikator = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus indikator kinerja ini?')) {
        router.delete(`/indikator-kinerja/${id}`);
    }
};

const getTipeBadge = (tipe) => {
    switch (tipe) {
        case 'IKU':
            return 'bg-indigo-50 text-indigo-700 border-indigo-200';
        case 'IKT':
            return 'bg-sky-50 text-sky-700 border-sky-200';
        default:
            return 'bg-purple-50 text-purple-700 border-purple-200';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Indikator Kinerja Mutu (IKU / IKT)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-bullseye"></i>
                        <span>Pilar Penetapan (P1) - Indikator Mutu</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Indikator Kinerja (IKU / IKT) SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Tolok ukur kuantitatif dan kualitatif ketercapaian Standar Nasional Pendidikan Tinggi (SN-Dikti) dan Rencana Strategis Institusi.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        v-if="perPageFilter !== 'all'"
                        @click="showAllData"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/15 cursor-pointer backdrop-blur-md"
                        title="Tampilkan semua 156 data indikator sekaligus"
                    >
                        <i class="bi bi-list-ul"></i>
                        <span>Tampilkan Semua Data</span>
                    </button>

                    <Link
                        href="/indikator-kinerja/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Indikator Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Summary KPI Badges -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xl">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Indikator Kinerja Utama (IKU)</p>
                        <p class="text-2xl font-black text-slate-900 font-mono">{{ summary?.IKU || 0 }}</p>
                    </div>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-black text-xl">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Indikator Kinerja Tambahan (IKT)</p>
                        <p class="text-2xl font-black text-sky-600 font-mono">{{ summary?.IKT || 0 }}</p>
                    </div>
                </div>

                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-xl">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Indikator Custom / Spesifik</p>
                        <p class="text-2xl font-black text-purple-600 font-mono">{{ summary?.custom || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari kode atau nama indikator..."
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
                            v-model="tipeFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium bg-white"
                        >
                            <option value="">Semua Tipe (IKU / IKT)</option>
                            <option value="IKU">IKU Saja</option>
                            <option value="IKT">IKT Saja</option>
                            <option value="custom">Custom</option>
                        </select>

                        <!-- Pilihan Jumlah Baris per Halaman Termasuk SEMUA DATA -->
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
                                <option value="100">100 / hal</option>
                                <option value="all">Semua Data ({{ indikators.total }})</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100 text-[11px]">
                            <tr>
                                <th class="py-3.5 px-5">Kode & Tipe</th>
                                <th class="py-3.5 px-5">Pernyataan Indikator Mutu</th>
                                <th class="py-3.5 px-5">Standar Acuan</th>
                                <th class="py-3.5 px-5">Target Baseline</th>
                                <th class="py-3.5 px-5">Unit Penanggung Jawab</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="ind in indikators.data"
                                :key="ind.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-5">
                                    <span class="font-mono font-black text-indigo-600 block text-xs">{{ ind.kode }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border mt-0.5 inline-block" :class="getTipeBadge(ind.tipe)">
                                        {{ ind.tipe }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 font-bold text-slate-900 max-w-sm">
                                    {{ ind.nama }}
                                </td>

                                <td class="py-4 px-5 text-slate-600 max-w-xs">
                                    <span v-if="ind.standar" class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100">{{ ind.standar.kode }}</span>
                                        <span class="truncate">{{ ind.standar.nama }}</span>
                                    </span>
                                    <span v-else class="text-slate-400">-</span>
                                </td>

                                <td class="py-4 px-5 font-bold text-emerald-700 font-mono">
                                    {{ ind.target_deskripsi || (ind.target_nilai ? `${ind.target_nilai} ${ind.unit_pengukuran || ''}` : '-') }}
                                </td>

                                <td class="py-4 px-5 font-semibold text-slate-600">
                                    {{ ind.unit_kerja || '-' }}
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/indikator-kinerja/${ind.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-slate-100 transition"
                                            title="Edit Indikator"
                                        >
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deleteIndikator(ind.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!indikators.data || indikators.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-bullseye text-3xl block mb-2 opacity-40"></i>
                                    <span>Tidak ada data indikator kinerja ditemukan.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                    <div class="font-medium">
                        <span v-if="perPageFilter === 'all'">
                            Menampilkan seluruh <strong class="text-indigo-600 font-bold">{{ indikators.total }}</strong> data indikator kinerja
                        </span>
                        <span v-else>
                            Menampilkan <strong class="text-slate-900">{{ indikators.from || 0 }}</strong> - <strong class="text-slate-900">{{ indikators.to || 0 }}</strong> dari <strong class="text-slate-900">{{ indikators.total }}</strong> data
                        </span>
                    </div>

                    <div v-if="perPageFilter !== 'all' && indikators.links && indikators.links.length > 3" class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in indikators.links"
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
