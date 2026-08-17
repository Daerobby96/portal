<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    sikluses: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref('');
const statusFilter = ref('all');

const filteredSikluses = computed(() => {
    return props.sikluses.filter(item => {
        const matchesSearch = item.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              String(item.tahun_siklus).includes(searchQuery.value) ||
                              (item.penanggung_jawab?.name && item.penanggung_jawab.name.toLowerCase().includes(searchQuery.value.toLowerCase()));
        const matchesStatus = statusFilter.value === 'all' || item.status === statusFilter.value;
        return matchesSearch && matchesStatus;
    });
});

const deleteSiklus = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus siklus mutu ini?')) {
        router.delete(`/siklus-spmi/${id}`);
    }
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'berjalan':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'evaluasi':
            return 'bg-amber-50 text-amber-700 border-amber-200';
        case 'ditutup':
            return 'bg-slate-100 text-slate-700 border-slate-200';
        default:
            return 'bg-blue-50 text-blue-700 border-blue-200';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Siklus Mutu SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-indigo-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Siklus Penjaminan Mutu</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Siklus Mutu (PPEPP)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola siklus penjaminan mutu tahunan, agregasi capaian 5 pilar PPEPP, dan riwayat continuous quality improvement (Kaizen).
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-3 shrink-0">
                    <a
                        href="/siklus-spmi/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 cursor-pointer shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat Siklus Baru</span>
                    </a>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Filters & Search Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari nama siklus, tahun, atau penanggung jawab..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <select
                            v-model="statusFilter"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="all">Semua Status</option>
                            <option value="persiapan">Persiapan</option>
                            <option value="berjalan">Berjalan</option>
                            <option value="evaluasi">Evaluasi</option>
                            <option value="ditutup">Ditutup</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Nama Siklus</th>
                                <th class="py-3.5 px-6">Tahun & Rentang</th>
                                <th class="py-3.5 px-6">Periode Terkait</th>
                                <th class="py-3.5 px-6">Penanggung Jawab</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="s in filteredSikluses"
                                :key="s.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <div class="flex items-center gap-2">
                                        <a :href="`/siklus-spmi/${s.id}`" class="hover:text-indigo-600 transition">
                                            {{ s.nama }}
                                        </a>
                                        <span v-if="s.is_aktif" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Aktif
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-normal mt-0.5 truncate max-w-xs">{{ s.deskripsi || '-' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800">{{ s.tahun_siklus }}</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ s.tanggal_mulai }} s/d {{ s.tanggal_selesai || 'Sekarang' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1">
                                        <span
                                            v-for="p in s.periodes"
                                            :key="p.id"
                                            class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-medium"
                                        >
                                            {{ p.nama }}
                                        </span>
                                        <span v-if="!s.periodes || s.periodes.length === 0" class="text-slate-400 text-[11px]">
                                            Belum terhubung
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ s.penanggung_jawab?.name || '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getStatusBadgeClass(s.status)"
                                    >
                                        {{ s.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            :href="`/siklus-spmi/${s.id}`"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                            title="Lihat Detail Siklus"
                                        >
                                            Rincian
                                        </a>
                                        <a
                                            :href="`/siklus-spmi/${s.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit Siklus"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            v-if="s.status === 'persiapan'"
                                            @click="deleteSiklus(s.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Siklus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredSikluses.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data siklus mutu yang cocok dengan filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
