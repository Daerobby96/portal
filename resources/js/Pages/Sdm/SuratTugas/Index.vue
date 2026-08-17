<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    suratTugases: Object,
    stats: Object,
    filters: Object,
});

const status = ref(props.filters?.status || '');
const jenis = ref(props.filters?.jenis || '');

const handleFilter = () => {
    router.get('/sdm/surat-tugas', {
        status: status.value,
        jenis: jenis.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteSurat = (s) => {
    if (confirm(`Hapus surat tugas nomor "${s.nomor_surat}"?`)) {
        router.delete(`/sdm/surat-tugas/${s.id}`);
    }
};

const approveSurat = (s) => {
    const catatan = prompt('Catatan approval (opsional):', 'Disetujui');
    if (catatan !== null) {
        router.post(`/sdm/surat-tugas/${s.id}/approve`, { catatan_approval: catatan });
    }
};

const rejectSurat = (s) => {
    const alasan = prompt('Alasan penolakan:');
    if (alasan) {
        router.post(`/sdm/surat-tugas/${s.id}/reject`, { alasan_penolakan: alasan });
    }
};

const getStatusBadge = (st) => {
    const map = {
        'approved': 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'completed': 'bg-blue-50 text-blue-700 border-blue-200',
        'pending': 'bg-amber-50 text-amber-700 border-amber-200',
        'rejected': 'bg-rose-50 text-rose-700 border-rose-200',
    };
    return map[st] || 'bg-slate-50 text-slate-700 border-slate-200';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Surat Tugas Kedinasan Pegawai" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-journal-text"></i>
                        <span>Modul SDM & Kepegawaian</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Surat Tugas Kedinasan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Penerbitan surat tugas dinas luar kampus, perjalanan dinas, seminar, pelatihan, dan pelaporan hasil tugas kedinasan.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <Link
                        href="/sdm/surat-tugas/create"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat Surat Tugas Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-files"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Surat Tugas</p>
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
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-airplane-engines-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sedang Bertugas</p>
                        <p class="text-xl font-black text-blue-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3 flex-wrap">
                    <select
                        v-model="status"
                        @change="handleFilter"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <select
                        v-model="jenis"
                        @change="handleFilter"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Jenis Penugasan</option>
                        <option value="dinas_luar">Dinas Luar</option>
                        <option value="perjalanan_dinas">Perjalanan Dinas</option>
                        <option value="pelatihan">Pelatihan</option>
                        <option value="seminar">Seminar / Konferensi</option>
                        <option value="tugas_khusus">Tugas Khusus</option>
                    </select>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Nomor & Perihal</th>
                                <th class="py-3.5 px-4">Pegawai Ditugaskan</th>
                                <th class="py-3.5 px-4">Tempat Tujuan</th>
                                <th class="py-3.5 px-4">Rentang Waktu</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="s in suratTugases.data"
                                :key="s.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ s.perihal }}</div>
                                    <div class="text-[11px] text-indigo-600 font-mono mt-0.5">{{ s.nomor_surat }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-slate-800">
                                        {{ s.pegawais?.map(p => p.nama).join(', ') || '-' }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 capitalize">{{ s.jenis?.replace('_', ' ') }}</span>
                                </td>
                                <td class="py-4 px-4 text-slate-700 font-semibold">
                                    {{ s.tempat_tujuan }}
                                </td>
                                <td class="py-4 px-4 text-slate-600 text-[11px]">
                                    {{ s.tanggal_mulai }} s.d. {{ s.tanggal_selesai }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border"
                                        :class="getStatusBadge(s.status)"
                                    >
                                        {{ s.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            v-if="s.status === 'pending'"
                                            @click="approveSurat(s)"
                                            class="p-2 rounded-xl text-emerald-600 hover:bg-emerald-50 transition cursor-pointer"
                                            title="Setujui Surat Tugas"
                                        >
                                            <i class="bi bi-check-lg text-base"></i>
                                        </button>
                                        <button
                                            v-if="s.status === 'pending'"
                                            @click="rejectSurat(s)"
                                            class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Tolak Surat Tugas"
                                        >
                                            <i class="bi bi-x-lg text-base"></i>
                                        </button>
                                        <Link
                                            :href="`/sdm/surat-tugas/${s.id}`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Detail Surat Tugas"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deleteSurat(s)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Surat Tugas"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!suratTugases.data || suratTugases.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada surat tugas kedinasan pada filter ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="suratTugases.links && suratTugases.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ suratTugases.from }} - {{ suratTugases.to }} dari total {{ suratTugases.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in suratTugases.links"
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
