<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    lemburs: Object,
    pegawais: Array,
    stats: Object,
    filters: Object,
});

const status = ref(props.filters?.status || '');
const pegawaiId = ref(props.filters?.pegawai_id || '');

const handleFilter = () => {
    router.get('/sdm/lembur', {
        status: status.value,
        pegawai_id: pegawaiId.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteLembur = (l) => {
    if (confirm(`Hapus pengajuan lembur ini?`)) {
        router.delete(`/sdm/lembur/${l.id}`);
    }
};

const approveLembur = (l) => {
    const catatan = prompt('Catatan approval lembur (opsional):', 'Disetujui');
    if (catatan !== null) {
        router.post(`/sdm/lembur/${l.id}/approve`, { catatan_approval: catatan });
    }
};

const rejectLembur = (l) => {
    const alasan = prompt('Alasan penolakan lembur:');
    if (alasan) {
        router.post(`/sdm/lembur/${l.id}/reject`, { alasan_penolakan: alasan });
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
        <Head title="Manajemen Lembur Pegawai" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-stopwatch"></i>
                        <span>Modul SDM & Kepegawaian</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Lembur & Kerja Tambahan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Penerbitan surat perintah lembur, pencatatan akumulasi jam kerja lembur, dan verifikasi atasan penilai.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <Link
                        href="/sdm/lembur/create"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Pengajuan Lembur</span>
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
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengajuan</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Approval</p>
                        <p class="text-xl font-black text-amber-600 leading-tight">{{ stats?.pending || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.approved || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jam Lembur Bulan Ini</p>
                        <p class="text-xl font-black text-purple-600 leading-tight">{{ stats?.total_jam_bulan_ini || 0 }} Jam</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 w-full md:w-auto flex-wrap">
                        <select
                            v-model="status"
                            @change="handleFilter"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
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
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-4">Pegawai</th>
                                <th class="py-3.5 px-4 text-center">Waktu Mulai - Selesai</th>
                                <th class="py-3.5 px-4 text-center">Jumlah Jam</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="l in lemburs.data"
                                :key="l.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-slate-900 text-xs">
                                    {{ formatDate(l.tanggal) }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900">{{ l.pegawai?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ l.pegawai?.unit_kerja || '-' }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-semibold text-slate-800">
                                    {{ l.jam_mulai }} - {{ l.jam_selesai }}
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-purple-700">
                                    {{ l.jumlah_jam }} Jam
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase"
                                        :class="l.status === 'approved' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : (l.status === 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-rose-100 text-rose-800 border border-rose-300')"
                                    >
                                        {{ l.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            v-if="l.status === 'pending'"
                                            @click="approveLembur(l)"
                                            class="p-2 rounded-xl text-emerald-600 hover:bg-emerald-50 transition cursor-pointer"
                                            title="Setujui Lembur"
                                        >
                                            <i class="bi bi-check-lg text-base"></i>
                                        </button>
                                        <button
                                            v-if="l.status === 'pending'"
                                            @click="rejectLembur(l)"
                                            class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Tolak Lembur"
                                        >
                                            <i class="bi bi-x-lg text-base"></i>
                                        </button>
                                        <Link
                                            :href="`/sdm/lembur/${l.id}`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Detail Lembur"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deleteLembur(l)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Pengajuan"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!lemburs.data || lemburs.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada pengajuan lembur yang terdaftar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="lemburs.links && lemburs.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ lemburs.from }} - {{ lemburs.to }} dari total {{ lemburs.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in lemburs.links"
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
