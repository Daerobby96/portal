<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    periodes: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

const handleSearch = () => {
    router.get('/periode', {
        search: search.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const activatePeriode = (p) => {
    if (confirm(`Apakah Anda yakin ingin menetapkan "${p.nama}" sebagai Periode Akademik Aktif?`)) {
        router.post(`/periode/${p.id}/activate`);
    }
};

const deletePeriode = (p) => {
    if (p.is_aktif) {
        alert('Periode aktif tidak dapat dihapus.');
        return;
    }
    if (confirm(`Hapus periode "${p.nama}"? Tindakan ini tidak dapat dibatalkan.`)) {
        router.delete(`/periode/${p.id}`);
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const cleanStr = String(dateStr).split('T')[0];
    const parts = cleanStr.split('-');
    if (parts.length === 3) {
        const year = parts[0];
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return `${day < 10 ? '0' + day : day} ${months[month - 1] || ''} ${year}`;
    }
    return dateStr;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Master Periode Akademik" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-calendar3"></i>
                        <span>Modul Data Master</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Periode Akademik & Semester
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola rentang kalender semester aktif sebagai acuan operasional perkuliahan, audit mutu, dan evaluasi kinerja tridharma.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <Link
                        href="/periode/create"
                        class="px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Tambah Periode Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-calendar-range"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Periode</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Periode Aktif</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-sun"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Semester Ganjil</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.ganjil || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-moon-stars"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Semester Genap</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.genap || 0 }}</p>
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
                            @input="handleSearch"
                            type="text"
                            placeholder="Cari nama periode atau tahun..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Nama Periode</th>
                                <th class="py-3.5 px-4 text-center">Tahun</th>
                                <th class="py-3.5 px-4 text-center">Semester</th>
                                <th class="py-3.5 px-4">Rentang Tanggal</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="p in periodes.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                                :class="{ 'bg-emerald-50/20': p.is_aktif }"
                            >
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ p.nama }}</div>
                                    <div v-if="p.keterangan" class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">{{ p.keterangan }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-bold font-mono text-slate-900">
                                    {{ p.tahun }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase"
                                        :class="p.semester === 'ganjil' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200'"
                                    >
                                        {{ p.semester }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    <div class="font-medium text-[11px] font-mono">{{ formatDate(p.tanggal_mulai) }} s.d. {{ formatDate(p.tanggal_selesai) }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        v-if="p.is_aktif"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                    <button
                                        v-else
                                        @click="activatePeriode(p)"
                                        class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition cursor-pointer border border-slate-200"
                                        title="Jadikan Periode Aktif"
                                    >
                                        Set Aktif
                                    </button>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="`/periode/${p.id}/edit`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Periode"
                                        >
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button
                                            v-if="!p.is_aktif"
                                            @click="deletePeriode(p)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Periode"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!periodes.data || periodes.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada data periode akademik yang terdaftar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="periodes.links && periodes.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ periodes.from }} - {{ periodes.to }} dari total {{ periodes.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in periodes.links"
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
