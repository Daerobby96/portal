<template>
    <AuthenticatedLayout title="Booking & Peminjaman Ruangan">
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
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Booking Ruangan & Fasilitas</h1>
                        <p class="text-xs text-slate-500 mt-1">Jadwal reservasi ruang kelas, laboratorium, auditorium, dan ruang rapat kampus.</p>
                    </div>
                </div>

                <Link
                    href="/booking-ruangan/create"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Ajukan Booking Ruangan</span>
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
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Menunggu Verifikasi</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Disetujui</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.disetujui }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Jadwal Terkonfirmasi</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-purple-600">Hari Ini</span>
                    </div>
                    <div class="text-2xl font-black text-purple-700">{{ stats.hari_ini }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Agenda Ruangan Hari Ini</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <input
                            v-model="filters.tanggal"
                            @change="applyFilters"
                            type="date"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none"
                        />
                    </div>

                    <div>
                        <select
                            v-model="filters.ruangan_id"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-purple-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Ruangan</option>
                            <option v-for="r in ruangans" :key="r.id" :value="r.id">{{ r.nama_ruangan }} ({{ r.kode_ruangan }})</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-purple-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="dibatalkan">Dibatalkan</option>
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
                                <th class="px-5 py-3.5 text-left">Ruangan</th>
                                <th class="px-4 py-3.5 text-left">Pemohon & Keperluan</th>
                                <th class="px-4 py-3.5 text-left">Tanggal & Jam Penggunaan</th>
                                <th class="px-4 py-3.5 text-left">Peserta</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="bookings.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-door-open"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada agenda booking ruangan</p>
                                </td>
                            </tr>
                            <tr
                                v-for="b in bookings.data"
                                :key="b.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-black text-slate-900 block">{{ b.ruangan_nama }}</span>
                                    <span class="text-[10px] text-slate-400">{{ b.ruangan_gedung }} {{ b.ruangan_lantai ? `Lt. ${b.ruangan_lantai}` : '' }} ({{ b.ruangan_kode }})</span>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <p class="font-bold text-slate-800 truncate" :title="b.keperluan">{{ b.keperluan }}</p>
                                    <span class="text-[10px] text-slate-400">Oleh: {{ b.pemohon_nama }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap text-slate-600">
                                    <span class="font-bold text-slate-800 block">{{ b.tanggal }}</span>
                                    <span class="text-[10px] text-purple-700 font-semibold bg-purple-50 px-1.5 py-0.2 rounded border border-purple-200/60 inline-block mt-0.5">
                                        <i class="bi bi-clock me-1"></i>{{ b.jam_mulai }} - {{ b.jam_selesai }} WIB
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-semibold text-slate-700">{{ b.jumlah_peserta ? `${b.jumlah_peserta} Orang` : '-' }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(b.status)">
                                        {{ b.status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <Link
                                        :href="`/booking-ruangan/${b.id}`"
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

                <div v-if="bookings.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ bookings.from }}–{{ bookings.to }} dari {{ bookings.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in bookings.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-purple-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
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
    bookings: Object,
    ruangans: Array,
    stats: Object,
    filters: Object,
    canApprove: Boolean,
});

const filters = reactive({
    tanggal: props.filters?.tanggal || '',
    ruangan_id: props.filters?.ruangan_id || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get('/booking-ruangan', filters, { preserveState: true, replace: true });
}

function statusBadgeClass(status) {
    const map = {
        'pending': 'bg-amber-50 text-amber-700 border border-amber-200',
        'disetujui': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'ditolak': 'bg-rose-50 text-rose-700 border border-rose-200',
        'dibatalkan': 'bg-slate-100 text-slate-600 border border-slate-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
