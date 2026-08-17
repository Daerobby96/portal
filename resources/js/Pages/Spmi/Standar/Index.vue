<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    standars: Object,
    bidangOptions: Object,
    summary: Object,
});

const searchQuery = ref('');
const bidangFilter = ref('');

const search = () => {
    router.get('/standar', {
        search: searchQuery.value,
        bidang: bidangFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteStandar = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus standar mutu ini?')) {
        router.delete(`/standar/${id}`);
    }
};

const getBidangBadge = (bidang) => {
    const badges = {
        pendidikan: 'bg-blue-50 text-blue-700 border-blue-200',
        penelitian: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        pkm: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        institusional: 'bg-purple-50 text-purple-700 border-purple-200',
    };
    return badges[bidang] || 'bg-slate-100 text-slate-700 border-slate-200';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Standar Mutu SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-bookmark-check"></i>
                        <span>Standar Mutu Institusi</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Daftar Standar Mutu SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Pengelolaan Standar Nasional Pendidikan Tinggi (SN-Dikti) dan Standar Institusional (Tridharma).
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/standar/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Standar</span>
                    </a>
                </div>
            </div>

            <!-- Summary KPI Badges -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Pendidikan</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.pendidikan || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Penelitian</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.penelitian || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Pengabdian (PkM)</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.pkm || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Institusional</p>
                        <p class="text-xl font-bold text-slate-900">{{ summary?.institusional || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari kode atau nama standar..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <select
                            v-model="bidangFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Bidang</option>
                            <option v-for="(label, key) in bidangOptions" :key="key" :value="key">{{ label }}</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode</th>
                                <th class="py-3.5 px-6">Nama Standar</th>
                                <th class="py-3.5 px-6">Bidang</th>
                                <th class="py-3.5 px-6">Jenis</th>
                                <th class="py-3.5 px-6 text-center">Dokumen</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="std in standars.data"
                                :key="std.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    {{ std.kode }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    <a :href="`/standar/${std.id}`" class="hover:text-indigo-600 transition">
                                        {{ std.nama }}
                                    </a>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getBidangBadge(std.bidang)"
                                    >
                                        {{ std.bidang }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-600 capitalize">
                                    {{ std.jenis }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 font-bold text-slate-700 text-[11px]">
                                        {{ std.dokumens_count || 0 }} Dok
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            :href="`/standar/${std.id}`"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                        >
                                            Rincian
                                        </a>
                                        <a
                                            :href="`/standar/${std.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit Standar"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            @click="deleteStandar(std.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!standars.data || standars.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data standar mutu yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="standars.links && standars.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Menampilkan {{ standars.from || 0 }} - {{ standars.to || 0 }} dari {{ standars.total }} data
                    </div>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in standars.links"
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
