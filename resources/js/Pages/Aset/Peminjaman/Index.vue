<template>
    <AuthenticatedLayout title="Peminjaman Aset">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/aset"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Peminjaman Aset & Fasilitas</h1>
                        <p class="text-xs text-slate-500 mt-1">Layanan peminjaman peralatan, laptop, proyektor, dan sarpras penunjang kegiatan.</p>
                    </div>
                </div>

                <Link
                    href="/peminjaman/create"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajukan Peminjaman Aset</span>
                </Link>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Pending</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.pending }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Menunggu Approval</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Sedang Dipinjam</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.dipinjam }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Aset di Luar Inventaris</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-rose-600">Terlambat</span>
                    </div>
                    <div class="text-2xl font-black text-rose-700">{{ stats.terlambat }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Melewati Batas Kembali</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari nama aset..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Status Peminjaman</option>
                            <option value="pending">Pending (Menunggu Persetujuan)</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="dipinjam">Sedang Dipinjam</option>
                            <option value="dikembalikan">Sudah Dikembalikan</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                <th class="px-5 py-3.5 text-left">Peminjam & Aset</th>
                                <th class="px-4 py-3.5 text-left">Keperluan</th>
                                <th class="px-4 py-3.5 text-left">Jadwal Pinjam & Rencana Kembali</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="peminjamans.data.length === 0">
                                <td colspan="5" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada pengajuan peminjaman</p>
                                </td>
                            </tr>
                            <tr
                                v-for="pm in peminjamans.data"
                                :key="pm.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 max-w-xs">
                                    <span class="font-black text-slate-900 block">{{ pm.peminjam_nama }}</span>
                                    <Link :href="`/aset/${pm.aset_id}`" class="text-blue-700 font-bold hover:underline flex items-center gap-1 mt-0.5 truncate">
                                        <i class="bi bi-box-seam text-xs"></i>
                                        <span>{{ pm.aset_nama }}</span>
                                    </Link>
                                    <span class="font-mono text-[10px] text-slate-400">{{ pm.aset_kode }}</span>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <p class="font-semibold text-slate-800 truncate" :title="pm.keperluan">{{ pm.keperluan }}</p>
                                    <span class="text-[10px] text-slate-400">{{ pm.created_at }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap text-slate-600">
                                    <div class="font-semibold text-[11px] flex items-center gap-1.5">
                                        <i class="bi bi-play-circle text-emerald-600 text-xs"></i>
                                        <span>{{ pm.tanggal_pinjam }}</span>
                                    </div>
                                    <div class="text-[10px] flex items-center gap-1.5 mt-0.5" :class="pm.is_terlambat ? 'text-rose-600 font-bold' : 'text-slate-400'">
                                        <i class="bi bi-stop-circle text-xs"></i>
                                        <span>{{ pm.tanggal_kembali_rencana }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(pm.status)">
                                        {{ pm.status }}
                                    </span>
                                    <div v-if="pm.is_terlambat" class="text-[9px] font-bold text-rose-600 mt-1">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Terlambat Kembali
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <Link
                                        :href="`/peminjaman/${pm.id}`"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition"
                                    >
                                        <span>Detail</span>
                                        <i class="bi bi-arrow-right text-[10px]"></i>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="peminjamans.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ peminjamans.from }}–{{ peminjamans.to }} dari {{ peminjamans.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in peminjamans.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    peminjamans: Object,
    stats: Object,
    filters: Object,
    canApprove: Boolean,
});

const filters = reactive({
    search: props.filters?.search || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get('/peminjaman', filters, { preserveState: true, replace: true });
}

function statusBadgeClass(status) {
    const map = {
        'pending': 'bg-amber-50 text-amber-700 border border-amber-200',
        'disetujui': 'bg-blue-50 text-blue-700 border border-blue-200',
        'dipinjam': 'bg-indigo-50 text-indigo-700 border border-indigo-200',
        'dikembalikan': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'ditolak': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
