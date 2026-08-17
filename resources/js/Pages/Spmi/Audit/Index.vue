<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    audits: Object,
    periodes: Array,
    stats: Object,
});

const searchQuery = ref('');
const statusFilter = ref('');
const periodeFilter = ref('');

const search = () => {
    router.get('/audit', {
        search: searchQuery.value,
        status: statusFilter.value,
        periode_id: periodeFilter.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteAudit = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus pelaksanaan audit ini?')) {
        router.delete(`/audit/${id}`);
    }
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'selesai':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'aktif':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Audit Mutu Internal (AMI)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-clipboard2-check"></i>
                        <span>Evaluasi & Audit Lapangan</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Pelaksanaan Audit Mutu Internal (AMI)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Manajemen penugasan auditor, checklist instrumen mutu, evaluasi lapangan, BAPA digital, dan rekapitulasi temuan.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/audit/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat Audit Baru</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-clipboard2-data"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Audit</p>
                        <p class="text-xl font-bold text-slate-900">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-play-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-blue-600 uppercase">Audit Aktif</p>
                        <p class="text-xl font-bold text-blue-700">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase">Audit Selesai</p>
                        <p class="text-xl font-bold text-emerald-700">{{ stats?.selesai || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-file-earmark"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Draft Persiapan</p>
                        <p class="text-xl font-bold text-slate-800">{{ stats?.draft || 0 }}</p>
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
                            placeholder="Cari kode, nama audit, atau unit auditee..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <select
                            v-model="periodeFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Periode</option>
                            <option v-for="p in periodes" :key="p.id" :value="p.id">{{ p.nama }}</option>
                        </select>

                        <select
                            v-model="statusFilter"
                            @change="search"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode & Nama Audit</th>
                                <th class="py-3.5 px-6">Unit Auditee</th>
                                <th class="py-3.5 px-6">Ketua Auditor</th>
                                <th class="py-3.5 px-6">Periode</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="aud in audits.data"
                                :key="aud.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-indigo-600 block">{{ aud.kode_audit }}</span>
                                    <a :href="`/audit/${aud.id}`" class="font-bold text-slate-900 hover:text-indigo-600 transition">
                                        {{ aud.nama_audit || 'Audit Mutu Internal' }}
                                    </a>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-800">
                                    {{ aud.unit_yang_diaudit }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ aud.ketua_auditor?.name || '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ aud.periode?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                        :class="getStatusBadge(aud.status)"
                                    >
                                        {{ aud.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            :href="`/audit/${aud.id}`"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                        >
                                            Kertas Kerja
                                        </a>
                                        <a
                                            :href="`/audit/${aud.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit Audit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            @click="deleteAudit(aud.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!audits.data || audits.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data pelaksanaan audit yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="audits.links && audits.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Menampilkan {{ audits.from || 0 }} - {{ audits.to || 0 }} dari {{ audits.total }} data
                    </div>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in audits.links"
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
