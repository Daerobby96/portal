<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    indikators: Object,
    standars: Array,
    summary: Object,
});

const searchQuery = ref('');
const standarFilter = ref('');
const tipeFilter = ref('');

const search = () => {
    router.get('/indikator-kinerja', {
        search: searchQuery.value,
        standar_id: standarFilter.value,
        tipe: tipeFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteIndikator = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus indikator kinerja ini?')) {
        router.delete(`/indikator-kinerja/${id}`);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Indikator Mutu (IKU / IKT)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-bullseye"></i>
                        <span>Pernyataan Kinerja Mutu</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Indikator Kinerja (IKU / IKT) SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Definisi metrik mutu terukur, target standar, dan unit penanggung jawab pelaksanaan mutu.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/indikator-kinerja/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Indikator</span>
                    </a>
                </div>
            </div>

            <!-- Summary KPI Badges -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-award"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Indikator Kinerja Utama (IKU)</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.IKU || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Indikator Kinerja Tambahan (IKT)</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.IKT || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-sliders"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Indikator Custom / Spesifik</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.custom || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari kode atau nama indikator..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <select
                            v-model="standarFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Standar</option>
                            <option v-for="s in standars" :key="s.id" :value="s.id">{{ s.kode }} - {{ s.nama }}</option>
                        </select>

                        <select
                            v-model="tipeFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="IKU">IKU</option>
                            <option value="IKT">IKT</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode & Tipe</th>
                                <th class="py-3.5 px-6">Nama Indikator Mutu</th>
                                <th class="py-3.5 px-6">Standar Acuan</th>
                                <th class="py-3.5 px-6">Target</th>
                                <th class="py-3.5 px-6">Unit Penanggung Jawab</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="ind in indikators.data"
                                :key="ind.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-indigo-600 block">{{ ind.kode }}</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                        {{ ind.tipe }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900 max-w-sm">
                                    {{ ind.nama }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ ind.standar?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ ind.target }} {{ ind.satuan }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ ind.unit_penanggung_jawab || '-' }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            :href="`/indikator-kinerja/${ind.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            @click="deleteIndikator(ind.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!indikators.data || indikators.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data indikator kinerja ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="indikators.links && indikators.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Menampilkan {{ indikators.from || 0 }} - {{ indikators.to || 0 }} dari {{ indikators.total }} data
                    </div>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in indikators.links"
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
