<template>
    <AuthenticatedLayout title="Inventaris Aset & Sarpras">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Aset & Sarana Prasarana</h1>
                    <p class="text-xs text-slate-500 mt-1">Pengelolaan inventaris barang milik institusi, monitoring kondisi, dan riwayat pemeliharaan.</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <Link
                        href="/pemeliharaan"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition shadow-xs"
                    >
                        <i class="bi bi-tools text-amber-500"></i>
                        <span>Servis / Pemeliharaan</span>
                    </Link>

                    <Link
                        href="/peminjaman"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition shadow-xs"
                    >
                        <i class="bi bi-arrow-left-right text-blue-500"></i>
                        <span>Peminjaman Aset</span>
                    </Link>

                    <Link
                        href="/aset/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Aset Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Aset Terdata</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Aktif</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.aktif }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Siap Digunakan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-tools"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Perbaikan</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.dalam_perbaikan }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Sedang Diservis</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                            <i class="bi bi-exclamation-octagon-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-rose-600">Rusak</span>
                    </div>
                    <div class="text-2xl font-black text-rose-700">{{ stats.rusak }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Rusak Ringan / Berat</div>
                </div>
            </div>

            <!-- Filters Toolbar -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                    <!-- Search Input -->
                    <div class="sm:col-span-2 md:col-span-1">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari nama / kode aset..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition bg-white"
                            />
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <select
                            v-model="filters.kategori_id"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Kategori</option>
                            <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                        </select>
                    </div>

                    <!-- Prodi Pengguna -->
                    <div>
                        <select
                            v-model="filters.prodi_id"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Unit / Prodi</option>
                            <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }}</option>
                        </select>
                    </div>

                    <!-- Kondisi -->
                    <div>
                        <select
                            v-model="filters.kondisi"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="non_aktif">Non Aktif</option>
                            <option value="dalam_perbaikan">Dalam Perbaikan</option>
                            <option value="dihapuskan">Dihapuskan</option>
                        </select>
                    </div>
                </div>

                <div v-if="hasActiveFilter" class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-500 text-[11px]">Filter aktif diterapkan</span>
                    <button
                        @click="resetFilters"
                        type="button"
                        class="text-[11px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1 cursor-pointer"
                    >
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Reset Semua Filter</span>
                    </button>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                <th class="px-5 py-3.5 text-left">Aset & Kode</th>
                                <th class="px-4 py-3.5 text-left">Kategori</th>
                                <th class="px-4 py-3.5 text-left">Lokasi & Ruangan</th>
                                <th class="px-4 py-3.5 text-left">Kondisi</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="asets.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada aset ditemukan</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Silakan sesuaikan kata kunci pencarian atau tambah aset baru.</p>
                                </td>
                            </tr>
                            <tr
                                v-for="a in asets.data"
                                :key="a.id"
                                class="hover:bg-slate-50/70 transition group"
                            >
                                <!-- Aset & Kode -->
                                <td class="px-5 py-3.5 max-w-xs">
                                    <div class="flex items-start gap-3">
                                        <div v-if="a.foto" class="w-10 h-10 rounded-xl overflow-hidden shrink-0 border border-slate-200 bg-slate-100">
                                            <img :src="a.foto" :alt="a.nama_aset" class="w-full h-full object-cover" />
                                        </div>
                                        <div v-else class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-lg shrink-0">
                                            <i class="bi bi-image"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <Link :href="`/aset/${a.id}`" class="font-black text-slate-900 hover:text-emerald-600 transition block truncate leading-snug">
                                                {{ a.nama_aset }}
                                            </Link>
                                            <div class="flex items-center gap-1.5 mt-0.5 text-[10px] text-slate-400 font-semibold">
                                                <span class="font-mono text-emerald-700 bg-emerald-50 px-1 py-0.2 rounded border border-emerald-200/60">{{ a.kode_aset }}</span>
                                                <span v-if="a.merk || a.tipe">• {{ [a.merk, a.tipe].filter(Boolean).join(' ') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span v-if="a.kategori_nama" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                        <i class="bi" :class="a.kategori_icon"></i>
                                        <span>{{ a.kategori_nama }}</span>
                                    </span>
                                    <span v-else class="text-slate-400">-</span>
                                </td>

                                <!-- Lokasi & Ruangan -->
                                <td class="px-4 py-3.5 max-w-xs text-slate-600">
                                    <div class="font-bold text-slate-800 truncate leading-snug flex items-center gap-1">
                                        <i class="bi bi-geo-alt-fill text-slate-400 text-xs"></i>
                                        <span>{{ a.lokasi }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        <span v-if="a.ruangan">Ruangan: {{ a.ruangan }}</span>
                                        <span v-if="a.prodi_nama" class="block text-slate-500 font-semibold">{{ a.prodi_nama }}</span>
                                    </div>
                                </td>

                                <!-- Kondisi -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="kondisiBadgeClass(a.kondisi)">
                                        {{ formatKondisi(a.kondisi) }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(a.status)">
                                        {{ formatStatus(a.status) }}
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/aset/${a.id}/pemeliharaan`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition"
                                            title="Catat Pemeliharaan / Servis"
                                        >
                                            <i class="bi bi-tools text-sm"></i>
                                        </Link>

                                        <Link
                                            :href="`/aset/${a.id}`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition"
                                            title="Detail Aset"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>

                                        <Link
                                            :href="`/aset/${a.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Aset"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>

                                        <button
                                            @click="confirmDelete(a)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Aset"
                                        >
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="asets.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ asets.from }}–{{ asets.to }} dari {{ asets.total }} aset</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in asets.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-emerald-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Data Aset?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data aset "<span class="font-bold text-slate-800">{{ deleteTarget.nama_aset }}</span>" ({{ deleteTarget.kode_aset }}) akan dihapus.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="deleteTarget = null"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm"
                            >
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    asets: Object,
    kategoris: Array,
    prodis: Array,
    stats: Object,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    kategori_id: props.filters?.kategori_id || '',
    prodi_id: props.filters?.prodi_id || '',
    kondisi: props.filters?.kondisi || '',
    status: props.filters?.status || '',
});

const hasActiveFilter = computed(() => {
    return !!(filters.search || filters.kategori_id || filters.prodi_id || filters.kondisi || filters.status);
});

function applyFilters() {
    router.get('/aset', filters, { preserveState: true, replace: true });
}

function resetFilters() {
    filters.search = '';
    filters.kategori_id = '';
    filters.prodi_id = '';
    filters.kondisi = '';
    filters.status = '';
    applyFilters();
}

const deleteTarget = ref(null);
function confirmDelete(a) {
    deleteTarget.value = a;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/aset/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function formatKondisi(kondisi) {
    const map = {
        'baik': 'Baik',
        'rusak_ringan': 'Rusak Ringan',
        'rusak_berat': 'Rusak Berat',
        'hilang': 'Hilang',
    };
    return map[kondisi] || kondisi;
}

function formatStatus(status) {
    const map = {
        'aktif': 'Aktif',
        'non_aktif': 'Non Aktif',
        'dalam_perbaikan': 'Perbaikan',
        'dihapuskan': 'Dihapuskan',
    };
    return map[status] || status;
}

function kondisiBadgeClass(kondisi) {
    const map = {
        'baik': 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
        'rusak_ringan': 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'rusak_berat': 'bg-rose-50 text-rose-700 border border-rose-200/60',
        'hilang': 'bg-slate-100 text-slate-600 border border-slate-200',
    };
    return map[kondisi] || 'bg-slate-100 text-slate-600';
}

function statusBadgeClass(status) {
    const map = {
        'aktif': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'non_aktif': 'bg-slate-100 text-slate-600 border border-slate-200',
        'dalam_perbaikan': 'bg-amber-50 text-amber-700 border border-amber-200',
        'dihapuskan': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
