<template>
    <AuthenticatedLayout title="Data Penelitian Dosen">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Penelitian & Riset Dosen</h1>
                    <p class="text-xs text-slate-500 mt-1">Pencatatan rekam jejak riset, pendanaan, tingkat wilayah, dan capaian luaran penelitian.</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        href="/penelitian/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Penelitian</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Judul Riset</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Usulan</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.usulan }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Proposal Masuk</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Berjalan</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.berjalan }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Sedang Berlangsung</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Selesai</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.selesai }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Laporan Akhir Siap</div>
                </div>

                <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-rose-600">Dana Riset</span>
                    </div>
                    <div class="text-lg font-black text-rose-700 truncate" :title="formatRupiah(stats.total_dana)">
                        {{ formatRupiah(stats.total_dana) }}
                    </div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Total Anggaran</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari judul riset atau nama dosen..."
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
                            v-model="filters.tingkat"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tingkat Wilayah</option>
                            <option value="Lokal">Lokal</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="Usulan">Usulan</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Selesai">Selesai</option>
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
                                <th class="px-5 py-3.5 text-left">Judul Penelitian & Tahun</th>
                                <th class="px-4 py-3.5 text-left">Ketua Peneliti & Anggota</th>
                                <th class="px-4 py-3.5 text-left">Tingkat & Pendanaan</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="penelitians.data.length === 0">
                                <td colspan="5" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-journal-x"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada data penelitian ditemukan</p>
                                </td>
                            </tr>
                            <tr
                                v-for="p in penelitians.data"
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

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="tingkatBadgeClass(p.tingkat)">
                                        {{ p.tingkat }}
                                    </span>
                                    <div class="mt-1 text-slate-700 font-semibold text-[11px]">
                                        {{ formatRupiah(p.jumlah_dana) }}
                                    </div>
                                    <span v-if="p.sumber_dana" class="text-[10px] text-slate-400 block">{{ p.sumber_dana }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(p.status)">
                                        {{ p.status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/penelitian/${p.id}/edit`"
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

                <div v-if="penelitians.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ penelitians.from }}–{{ penelitians.to }} dari {{ penelitians.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in penelitians.links"
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
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Penelitian?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Judul riset "<span class="font-bold text-slate-800">{{ deleteTarget.judul }}</span>" akan dihapus permanen.
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
    penelitians: Object,
    stats: Object,
    prodis: Array,
    tahuns: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    prodi: props.filters?.prodi || '',
    tahun: props.filters?.tahun || '',
    tingkat: props.filters?.tingkat || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get('/penelitian', filters, { preserveState: true, replace: true });
}

const deleteTarget = ref(null);
function confirmDelete(p) {
    deleteTarget.value = p;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/penelitian/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
}

function tingkatBadgeClass(tingkat) {
    const map = {
        'Internasional': 'bg-purple-50 text-purple-700 border border-purple-200',
        'Nasional': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Lokal': 'bg-slate-100 text-slate-700 border border-slate-200',
    };
    return map[tingkat] || 'bg-slate-100 text-slate-600';
}

function statusBadgeClass(status) {
    const map = {
        'Usulan': 'bg-amber-50 text-amber-700 border border-amber-200',
        'Berjalan': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Selesai': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
