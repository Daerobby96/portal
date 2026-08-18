<template>
    <AuthenticatedLayout title="Pengabdian Masyarakat (PkM)">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengabdian Kepada Masyarakat (PkM)</h1>
                    <p class="text-xs text-slate-500 mt-1">Implementasi ilmu pengetahuan & teknologi dosen untuk pemberdayaan masyarakat dan mitra kerjasama.</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        href="/pengabdian/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Kegiatan PkM</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total PkM</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Kegiatan Pengabdian</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-building-check"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Mitra Terlibat</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.mitra_count }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Mitra / Desa Binaan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-rose-600">Dana PkM</span>
                    </div>
                    <div class="text-xl font-black text-rose-700 truncate" :title="formatRupiah(stats.total_dana)">
                        {{ formatRupiah(stats.total_dana) }}
                    </div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Total Anggaran Terealisasi</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari judul PkM, mitra, lokasi, atau nama dosen..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.prodi"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Program Studi</option>
                            <option v-for="pr in prodis" :key="pr.id" :value="pr.id">{{ pr.nama }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tahun"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tahun</option>
                            <option v-for="t in tahuns" :key="t" :value="t">Tahun {{ t }}</option>
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
                                <th class="px-5 py-3.5 text-left">Judul PkM & Tahun</th>
                                <th class="px-4 py-3.5 text-left">Ketua Pelaksana & Anggota</th>
                                <th class="px-4 py-3.5 text-left">Mitra & Lokasi</th>
                                <th class="px-4 py-3.5 text-left">Pendanaan</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="pengabdians.data.length === 0">
                                <td colspan="5" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada data Pengabdian Masyarakat ditemukan</p>
                                </td>
                            </tr>
                            <tr
                                v-for="p in pengabdians.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 max-w-sm">
                                    <p class="font-black text-slate-900 leading-snug">{{ p.judul }}</p>
                                    <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-500">
                                        <span class="font-bold text-rose-700 bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200/60 font-mono">
                                            Tahun {{ p.tahun }}
                                        </span>
                                        <span v-if="p.prodi_nama">{{ p.prodi_nama }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <span class="font-bold text-slate-900 block">{{ p.pegawai_nama || '-' }}</span>
                                    <span v-if="p.pegawai_nip" class="font-mono text-[10px] text-slate-400 block">NIP: {{ p.pegawai_nip }}</span>
                                    <p v-if="p.anggota" class="text-[10px] text-slate-500 mt-0.5 truncate" :title="p.anggota">
                                        Anggota: {{ p.anggota }}
                                    </p>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <span class="font-bold text-slate-800 block">{{ p.mitra || '-' }}</span>
                                    <span v-if="p.lokasi" class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i class="bi bi-geo-alt-fill text-rose-500"></i>
                                        <span>{{ p.lokasi }}</span>
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block">{{ formatRupiah(p.jumlah_dana) }}</span>
                                    <span v-if="p.sumber_dana" class="text-[10px] text-slate-400 block">{{ p.sumber_dana }}</span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/pengabdian/${p.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>
                                        <button
                                            @click="confirmDelete(p)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="pengabdians.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ pengabdians.from }}–{{ pengabdians.to }} dari {{ pengabdians.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in pengabdians.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-rose-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
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
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Kegiatan PkM?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Kegiatan "<span class="font-bold text-slate-800">{{ deleteTarget.judul }}</span>" akan dihapus permanen.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="deleteTarget = null"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm cursor-pointer"
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
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pengabdians: Object,
    stats: Object,
    prodis: Array,
    tahuns: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    prodi: props.filters?.prodi || '',
    tahun: props.filters?.tahun || '',
});

function applyFilters() {
    router.get('/pengabdian', filters, { preserveState: true, replace: true });
}

const deleteTarget = ref(null);
function confirmDelete(p) {
    deleteTarget.value = p;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/pengabdian/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
}
</script>
