<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    presensis: Object,
    pegawais: Array,
    stats: Object,
    filters: Object,
});

const tanggal = ref(props.filters?.tanggal || '');
const pegawaiId = ref(props.filters?.pegawai_id || '');
const status = ref(props.filters?.status || '');

const handleFilter = () => {
    router.get('/sdm/presensi', {
        tanggal: tanggal.value,
        pegawai_id: pegawaiId.value,
        status: status.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deletePresensi = (p) => {
    if (confirm(`Hapus catatan presensi ini?`)) {
        router.delete(`/sdm/presensi/${p.id}`);
    }
};

const getStatusBadge = (st) => {
    const map = {
        'hadir': 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'izin': 'bg-amber-50 text-amber-700 border-amber-200',
        'sakit': 'bg-blue-50 text-blue-700 border-blue-200',
        'cuti': 'bg-purple-50 text-purple-700 border-purple-200',
        'dinas_luar': 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'alpa': 'bg-rose-50 text-rose-700 border-rose-200',
    };
    return map[st] || 'bg-slate-50 text-slate-700 border-slate-200';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Presensi & Kehadiran Pegawai" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-fingerprint"></i>
                        <span>Modul SDM & Kepegawaian</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Presensi & Kehadiran Pegawai
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Pencatatan jam masuk, jam kepulangan, rekapitulasi izin/sakit, dan status kedinasan dosen serta staf.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <Link
                        href="/sdm/presensi/rekap"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold transition flex items-center gap-2"
                    >
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Rekap Bulanan</span>
                    </Link>
                    <Link
                        href="/sdm/presensi/create"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Input Presensi</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Hari Ini</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total_hari_ini || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hadir Hari Ini</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.hadir_hari_ini || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Izin / Sakit</p>
                        <p class="text-xl font-black text-amber-600 leading-tight">{{ stats?.izin_hari_ini || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alpa</p>
                        <p class="text-xl font-black text-rose-600 leading-tight">{{ stats?.alpa_hari_ini || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 w-full md:w-auto flex-wrap">
                        <input
                            v-model="tanggal"
                            @change="handleFilter"
                            type="date"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        />

                        <select
                            v-model="pegawaiId"
                            @change="handleFilter"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 max-w-[200px]"
                        >
                            <option value="">Semua Pegawai</option>
                            <option v-for="p in pegawais" :key="p.id" :value="p.id">{{ p.nama }}</option>
                        </select>

                        <select
                            v-model="status"
                            @change="handleFilter"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="cuti">Cuti</option>
                            <option value="dinas_luar">Dinas Luar</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-4">Pegawai</th>
                                <th class="py-3.5 px-4 text-center">Jam Masuk</th>
                                <th class="py-3.5 px-4 text-center">Jam Keluar</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4">Keterangan</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="p in presensis.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                    {{ p.tanggal }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900">{{ p.pegawai?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ p.pegawai?.unit_kerja || '-' }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-semibold text-slate-800">
                                    {{ p.jam_masuk || '-' }}
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-semibold text-slate-800">
                                    {{ p.jam_keluar || '-' }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border"
                                        :class="getStatusBadge(p.status)"
                                    >
                                        {{ p.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-slate-500 text-[11px]">
                                    {{ p.keterangan || '-' }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button
                                        @click="deletePresensi(p)"
                                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                        title="Hapus Presensi"
                                    >
                                        <i class="bi bi-trash3 text-sm"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="!presensis.data || presensis.data.length === 0">
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Belum ada catatan presensi pada filter ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="presensis.links && presensis.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ presensis.from }} - {{ presensis.to }} dari total {{ presensis.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in presensis.links"
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
