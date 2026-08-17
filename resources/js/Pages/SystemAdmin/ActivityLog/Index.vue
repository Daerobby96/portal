<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    logs: Object,
    users: Array,
    actions: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const user_id = ref(props.filters?.user_id || '');
const action = ref(props.filters?.action || '');
const selectedLog = ref(null);
const detailModalOpen = ref(false);

let searchTimeout = null;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 400);
};

const applyFilters = () => {
    router.get('/activity-log', {
        search: search.value,
        user_id: user_id.value,
        action: action.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const openDetail = (log) => {
    selectedLog.value = log;
    detailModalOpen.value = true;
};

const formatDateTime = (dateStr) => {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
};

const getActionBadge = (actionName) => {
    const act = String(actionName || '').toLowerCase();
    if (act.includes('create') || act.includes('store') || act.includes('tambah')) {
        return 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
    }
    if (act.includes('update') || act.includes('edit') || act.includes('ubah')) {
        return 'bg-blue-50 text-blue-700 border-blue-200/60';
    }
    if (act.includes('delete') || act.includes('destroy') || act.includes('hapus')) {
        return 'bg-rose-50 text-rose-700 border-rose-200/60';
    }
    if (act.includes('login') || act.includes('auth')) {
        return 'bg-purple-50 text-purple-700 border-purple-200/60';
    }
    return 'bg-slate-100 text-slate-700 border-slate-200';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Log Aktivitas & Audit Trail" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-indigo-200 border border-white/20 uppercase tracking-wider">
                            System Admin
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            Audit Trail
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Log Aktivitas & Riwayat Sistem
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Rekam jejak setiap aksi pengguna, pembuatan dokumen, perubahan data, approval, dan aktivitas keamanan di ERP-POLKA.
                    </p>
                </div>
            </div>

            <!-- Filter & Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3 flex-wrap">
                    <div class="relative flex-1 min-w-[240px]">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input
                            v-model="search"
                            @input="handleSearch"
                            type="text"
                            placeholder="Cari deskripsi log, aksi, IP, atau user..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                    </div>

                    <select
                        v-model="user_id"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Pengguna</option>
                        <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                    </select>

                    <select
                        v-model="action"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Aksi</option>
                        <option v-for="a in actions" :key="a" :value="a">{{ a }}</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-6">Waktu Kejadian</th>
                                <th class="py-3.5 px-4">Pengguna (User)</th>
                                <th class="py-3.5 px-4">Aksi / Event</th>
                                <th class="py-3.5 px-6">Uraian Aktivitas</th>
                                <th class="py-3.5 px-4 text-center">IP Address</th>
                                <th class="py-3.5 px-6 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="l in logs.data"
                                :key="l.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                    {{ formatDateTime(l.created_at) }}
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-900">
                                    <div v-if="l.user">
                                        <span>{{ l.user.name }}</span>
                                        <span class="block text-[10px] text-slate-400 font-mono font-normal">{{ l.user.email }}</span>
                                    </div>
                                    <span v-else class="text-slate-400 italic font-normal">Sistem / Tamu</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border font-mono"
                                        :class="getActionBadge(l.action)"
                                    >
                                        {{ l.action || 'activity' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-800 font-medium max-w-md">
                                    {{ l.description }}
                                </td>
                                <td class="py-4 px-4 text-center font-mono text-[11px] text-slate-500">
                                    {{ l.ip_address || '-' }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button
                                        @click="openDetail(l)"
                                        class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                        title="Rincian Log"
                                    >
                                        <i class="bi bi-eye text-sm"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="!logs.data || logs.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada catatan log aktivitas yang sesuai filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="logs.links && logs.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ logs.from || 0 }} - {{ logs.to || 0 }} dari {{ logs.total }} catatan log
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in logs.links"
                            :key="i"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition"
                            :class="link.active ? 'bg-indigo-600 text-white font-bold' : (link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none')"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Detail Log -->
            <div v-if="detailModalOpen && selectedLog" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl border border-slate-100 space-y-5 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Rincian Log Aktivitas</h3>
                                <p class="text-[11px] text-slate-400">{{ formatDateTime(selectedLog.created_at) }}</p>
                            </div>
                        </div>
                        <button @click="detailModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <span class="text-slate-400 font-bold uppercase text-[10px]">Deskripsi Aktivitas</span>
                            <p class="font-bold text-slate-900">{{ selectedLog.description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-bold uppercase text-[10px]">Pelaku (User)</span>
                                <p class="font-semibold text-slate-800">{{ selectedLog.user?.name || 'Sistem' }}</p>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-bold uppercase text-[10px]">IP Address</span>
                                <p class="font-mono text-slate-800">{{ selectedLog.ip_address || '-' }}</p>
                            </div>
                        </div>

                        <div v-if="selectedLog.properties" class="space-y-1.5">
                            <span class="text-slate-400 font-bold uppercase text-[10px]">Data Payload (Properties)</span>
                            <pre class="p-4 rounded-2xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto leading-relaxed">{{ JSON.stringify(selectedLog.properties, null, 2) }}</pre>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-3 border-t border-slate-100">
                        <button @click="detailModalOpen = false" class="px-5 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-800">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
