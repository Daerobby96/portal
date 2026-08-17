<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    program_studis: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const jenjangFilter = ref(props.filters?.jenjang || '');

const handleFilter = () => {
    router.get('/program-studi', {
        search: search.value,
        jenjang: jenjangFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteProdi = (prodi) => {
    if (confirm(`Hapus Program Studi "${prodi.nama}"? Tindakan ini tidak dapat dibatalkan.`)) {
        router.delete(`/program-studi/${prodi.id}`);
    }
};

const getAkreditasiBadge = (akr) => {
    const map = {
        'Unggul': 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'A': 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Baik Sekali': 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'B': 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'Baik': 'bg-blue-50 text-blue-700 border-blue-200',
        'C': 'bg-amber-50 text-amber-700 border-amber-200',
    };
    return map[akr] || 'bg-slate-50 text-slate-600 border-slate-200';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Master Program Studi" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-mortarboard"></i>
                        <span>Modul Data Master</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Program Studi & Jurusan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola data program studi resmi perguruan tinggi, jenjang pendidikan vokasi/akademik, dan status akreditasi BAN-PT/LAM.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <Link
                        href="/program-studi/create"
                        class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Tambah Program Studi</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Program Studi</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Prodi Aktif Operasional</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Akreditasi Unggul / A</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.unggul || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="w-full sm:w-80 relative">
                        <input
                            v-model="search"
                            @input="handleFilter"
                            type="text"
                            placeholder="Cari kode atau nama program studi..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    </div>

                    <div class="w-full sm:w-auto flex items-center gap-3">
                        <select
                            v-model="jenjangFilter"
                            @change="handleFilter"
                            class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >
                            <option value="">Semua Jenjang</option>
                            <option value="D3">Diploma 3 (D3)</option>
                            <option value="D4">Diploma 4 / Sarjana Terapan (D4)</option>
                            <option value="S1">Sarjana (S1)</option>
                            <option value="S2">Magister (S2)</option>
                            <option value="Profesi">Profesi</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode Prodi</th>
                                <th class="py-3.5 px-4">Nama Program Studi</th>
                                <th class="py-3.5 px-4 text-center">Jenjang</th>
                                <th class="py-3.5 px-4 text-center">Akreditasi</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="prodi in program_studis.data"
                                :key="prodi.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    {{ prodi.kode }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ prodi.nama }}</div>
                                    <div v-if="prodi.deskripsi" class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ prodi.deskripsi }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ prodi.jenjang }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        v-if="prodi.akreditasi"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                        :class="getAkreditasiBadge(prodi.akreditasi)"
                                    >
                                        {{ prodi.akreditasi }}
                                    </span>
                                    <span v-else class="text-slate-300">-</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        v-if="prodi.is_aktif"
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-50 text-slate-500 border border-slate-200"
                                    >
                                        Nonaktif
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="`/program-studi/${prodi.id}/edit`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Program Studi"
                                        >
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deleteProdi(prodi)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Program Studi"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!program_studis.data || program_studis.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada data program studi yang terdaftar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="program_studis.links && program_studis.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ program_studis.from }} - {{ program_studis.to }} dari total {{ program_studis.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in program_studis.links"
                            :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                            :class="link.active ? 'bg-indigo-600 text-white shadow-xs' : link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none'"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
