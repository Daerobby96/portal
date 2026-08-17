<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    penilaians: Object,
    pegawais: Array,
    stats: Object,
    filters: Object,
});

const tahun = ref(props.filters?.tahun || '');
const periode = ref(props.filters?.periode || '');
const pegawaiId = ref(props.filters?.pegawai_id || '');

const handleFilter = () => {
    router.get('/sdm/penilaian-kinerja', {
        tahun: tahun.value,
        periode: periode.value,
        pegawai_id: pegawaiId.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deletePenilaian = (p) => {
    if (confirm(`Hapus data penilaian kinerja pegawai "${p.pegawai?.nama}"?`)) {
        router.delete(`/sdm/penilaian-kinerja/${p.id}`);
    }
};

const verifyPenilaian = (p) => {
    if (confirm(`Verifikasi resmi penilaian kinerja ini?`)) {
        router.post(`/sdm/penilaian-kinerja/${p.id}/verify`);
    }
};

const getPredikatBadge = (pred) => {
    const map = {
        'sangat_baik': 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'baik': 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'cukup': 'bg-amber-50 text-amber-700 border-amber-200',
        'kurang': 'bg-rose-50 text-rose-700 border-rose-200',
    };
    return map[pred] || 'bg-slate-50 text-slate-700 border-slate-200';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Penilaian Kinerja Pegawai (SKP)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-award"></i>
                        <span>Modul SDM & Kepegawaian</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Penilaian Kinerja Pegawai (SKP)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Evaluasi capaian kinerja berkala, disiplin, loyalitas, kreativitas, dan verifikasi Sasaran Kinerja Pegawai oleh atasan langsung.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <Link
                        href="/sdm/penilaian-kinerja/create"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Input Penilaian Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Penilaian</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tahun Berjalan</p>
                        <p class="text-xl font-black text-blue-600 leading-tight">{{ stats?.tahun_ini || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Predikat Sangat Baik</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.sangat_baik || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.avg_nilai || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 w-full md:w-auto flex-wrap">
                        <input
                            v-model="tahun"
                            @change="handleFilter"
                            type="number"
                            placeholder="Tahun..."
                            class="w-28 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold font-mono text-slate-900 focus:ring-2 focus:ring-indigo-500"
                        />

                        <select
                            v-model="periode"
                            @change="handleFilter"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Periode</option>
                            <option value="semester_1">Semester 1 (Ganjil)</option>
                            <option value="semester_2">Semester 2 (Genap)</option>
                            <option value="tahunan">Tahunan</option>
                        </select>

                        <select
                            v-model="pegawaiId"
                            @change="handleFilter"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 max-w-[200px]"
                        >
                            <option value="">Semua Pegawai</option>
                            <option v-for="p in pegawais" :key="p.id" :value="p.id">{{ p.nama }}</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Nama Pegawai</th>
                                <th class="py-3.5 px-4 text-center">Tahun / Periode</th>
                                <th class="py-3.5 px-4 text-center">Skor Total</th>
                                <th class="py-3.5 px-4 text-center">Predikat</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="p in penilaians.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ p.pegawai?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ p.pegawai?.nip || '-' }} | {{ p.pegawai?.unit_kerja }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold font-mono text-slate-900 block">{{ p.tahun }}</span>
                                    <span class="text-[10px] text-slate-400 capitalize">{{ p.periode?.replace('_', ' ') }}</span>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-black text-sm text-slate-900">
                                    {{ p.nilai_total }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border"
                                        :class="getPredikatBadge(p.predikat)"
                                    >
                                        {{ p.predikat?.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase"
                                        :class="p.status === 'verified' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'"
                                    >
                                        {{ p.status === 'verified' ? 'Terverifikasi' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            v-if="p.status !== 'verified'"
                                            @click="verifyPenilaian(p)"
                                            class="p-2 rounded-xl text-emerald-600 hover:bg-emerald-50 transition cursor-pointer"
                                            title="Verifikasi Resmi"
                                        >
                                            <i class="bi bi-patch-check text-base"></i>
                                        </button>
                                        <Link
                                            :href="`/sdm/penilaian-kinerja/${p.id}`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Detail Penilaian"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <Link
                                            v-if="p.status !== 'verified'"
                                            :href="`/sdm/penilaian-kinerja/${p.id}/edit`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Nilai"
                                        >
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deletePenilaian(p)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Penilaian"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!penilaians.data || penilaians.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada data penilaian kinerja pada filter ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="penilaians.links && penilaians.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ penilaians.from }} - {{ penilaians.to }} dari total {{ penilaians.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in penilaians.links"
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
