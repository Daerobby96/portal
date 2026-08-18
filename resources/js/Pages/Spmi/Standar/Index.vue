<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    standars: Object,
    bidangOptions: Object,
    summary: Object,
    filters: {
        type: Object,
        default: () => ({ search: '', bidang: '', per_page: '15' }),
    },
});

const searchQuery = ref(props.filters.search || '');
const bidangFilter = ref(props.filters.bidang || '');
const perPageFilter = ref(props.filters.per_page || '15');

const search = () => {
    router.get('/standar', {
        search: searchQuery.value,
        bidang: bidangFilter.value,
        per_page: perPageFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const filterByBidang = (val) => {
    bidangFilter.value = bidangFilter.value === val ? '' : val;
    search();
};

const showAllData = () => {
    perPageFilter.value = 'all';
    search();
};

const deleteStandar = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus standar mutu ini?')) {
        router.delete(`/standar/${id}`);
    }
};

const getBidangBadge = (bidang) => {
    const badges = {
        pendidikan: 'bg-blue-50 text-blue-700 border-blue-200/80',
        penelitian: 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        pkm: 'bg-amber-50 text-amber-700 border-amber-200/80',
        institusional: 'bg-purple-50 text-purple-700 border-purple-200/80',
    };
    return badges[bidang] || 'bg-slate-100 text-slate-700 border-slate-200';
};

const getBidangLabel = (bidang) => {
    const labels = {
        pendidikan: 'Pendidikan',
        penelitian: 'Penelitian',
        pkm: 'Pengabdian (PkM)',
        institusional: 'Institusional',
    };
    return labels[bidang] || bidang;
};

const getBidangIcon = (bidang) => {
    const icons = {
        pendidikan: 'bi-mortarboard-fill',
        penelitian: 'bi-search',
        pkm: 'bi-people-fill',
        institusional: 'bi-building',
    };
    return icons[bidang] || 'bi-bookmark';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="31 Standar Mutu SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-bookmark-check"></i>
                        <span>Pilar Penetapan (P1) - Buku 3 SPMI</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Daftar 31 Standar Mutu SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Standar Nasional Pendidikan Tinggi (SN-Dikti: 24 Standar) dan Standar Khusus Institusional (7 Standar) Politeknik Krakatau.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        v-if="perPageFilter !== 'all'"
                        @click="showAllData"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/15 cursor-pointer backdrop-blur-md"
                    >
                        <i class="bi bi-list-ul"></i>
                        <span>Tampilkan Semua (31 Standar)</span>
                    </button>

                    <Link
                        href="/standar/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Standar Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Summary KPI 4 Bidang -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    @click="filterByBidang('pendidikan')"
                    class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs cursor-pointer hover:border-blue-300 transition"
                    :class="bidangFilter === 'pendidikan' ? 'ring-2 ring-blue-500 bg-blue-50/20' : ''"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-blue-900">Bidang Pendidikan (A)</span>
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-blue-700 mt-2 font-mono">{{ summary?.pendidikan || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">A.1 s/d A.8 SN-Dikti</span>
                </div>

                <div
                    @click="filterByBidang('penelitian')"
                    class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs cursor-pointer hover:border-emerald-300 transition"
                    :class="bidangFilter === 'penelitian' ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : ''"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-emerald-900">Bidang Penelitian (B)</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-700 mt-2 font-mono">{{ summary?.penelitian || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">B.1 s/d B.8 SN-Dikti</span>
                </div>

                <div
                    @click="filterByBidang('pkm')"
                    class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs cursor-pointer hover:border-amber-300 transition"
                    :class="bidangFilter === 'pkm' ? 'ring-2 ring-amber-500 bg-amber-50/20' : ''"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-amber-900">Bidang PkM (C)</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-700 mt-2 font-mono">{{ summary?.pkm || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">C.1 s/d C.8 SN-Dikti</span>
                </div>

                <div
                    @click="filterByBidang('institusional')"
                    class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-2xs cursor-pointer hover:border-purple-300 transition"
                    :class="bidangFilter === 'institusional' ? 'ring-2 ring-purple-500 bg-purple-50/20' : ''"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-900">Bidang Institusional (D)</span>
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-purple-700 mt-2 font-mono">{{ summary?.institusional || 0 }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">D.1 s/d D.7 Standar Khusus</span>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="search"
                            type="text"
                            placeholder="Cari kode atau nama standar mutu..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <select
                            v-model="bidangFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium bg-white"
                        >
                            <option value="">Semua Bidang (31 Standar)</option>
                            <option value="pendidikan">Bidang Pendidikan (A.1 - A.8)</option>
                            <option value="penelitian">Bidang Penelitian (B.1 - B.8)</option>
                            <option value="pkm">Bidang Pengabdian PkM (C.1 - C.8)</option>
                            <option value="institusional">Bidang Institusional (D.1 - D.7)</option>
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
                                <option value="all">Semua ({{ standars.total }})</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table Content (Proportional Layout) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700 table-auto">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100 text-[11px]">
                            <tr>
                                <th class="py-3 px-4 w-20 text-center">Kode</th>
                                <th class="py-3 px-4">Nama Standar Mutu</th>
                                <th class="py-3 px-4 w-44">Bidang Standar</th>
                                <th class="py-3 px-4 w-36">Jenis Standar</th>
                                <th class="py-3 px-4 w-24 text-center">Status</th>
                                <th class="py-3 px-4 w-28 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="std in standars.data"
                                :key="std.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <!-- Kode -->
                                <td class="py-3.5 px-4 font-mono font-black text-indigo-600 text-center whitespace-nowrap">
                                    {{ std.kode }}
                                </td>

                                <!-- Nama & Deskripsi -->
                                <td class="py-3.5 px-4">
                                    <Link :href="`/standar/${std.id}`" class="font-bold text-slate-900 text-xs hover:text-indigo-600 transition block">
                                        {{ std.nama }}
                                    </Link>
                                    <p v-if="std.deskripsi" class="text-[11px] text-slate-400 mt-0.5 line-clamp-1 leading-snug">
                                        {{ std.deskripsi }}
                                    </p>
                                </td>

                                <!-- Bidang Standar (Clean Single Line Badge) -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border"
                                        :class="getBidangBadge(std.bidang)"
                                    >
                                        <i class="bi text-xs" :class="getBidangIcon(std.bidang)"></i>
                                        <span>{{ getBidangLabel(std.bidang) }}</span>
                                    </span>
                                </td>

                                <!-- Jenis Standar -->
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold border"
                                        :class="std.jenis === 'inti' ? 'bg-slate-50 text-slate-700 border-slate-200' : 'bg-purple-50 text-purple-700 border-purple-200'"
                                    >
                                        {{ std.jenis === 'inti' ? 'SN-Dikti' : 'Institusional' }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                        :class="std.is_aktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" :class="std.is_aktif ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        <span>{{ std.is_aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">
                                        <Link
                                            :href="`/standar/${std.id}`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Lihat Detail Standar"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <Link
                                            :href="`/standar/${std.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Standar"
                                        >
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deleteStandar(std.id)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!standars.data || standars.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="bi bi-bookmark-x text-3xl block mb-2 opacity-40"></i>
                                    <span>Tidak ada standar mutu ditemukan.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                    <div class="font-medium">
                        <span v-if="perPageFilter === 'all'">
                            Menampilkan seluruh <strong class="text-indigo-600 font-bold">{{ standars.total }}</strong> standar mutu
                        </span>
                        <span v-else>
                            Menampilkan <strong class="text-slate-900">{{ standars.from || 0 }}</strong> - <strong class="text-slate-900">{{ standars.to || 0 }}</strong> dari <strong class="text-slate-900">{{ standars.total }}</strong> standar
                        </span>
                    </div>

                    <div v-if="perPageFilter !== 'all' && standars.links && standars.links.length > 3" class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in standars.links"
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
