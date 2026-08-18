<template>
    <AuthenticatedLayout title="Prestasi Mahasiswa">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Prestasi & Kompetisi Mahasiswa</h1>
                    <p class="text-xs text-slate-500 mt-1">Pencatatan capaian kejuaraan akademik, non-akademik, dan sertifikat prestasi mahasiswa.</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        href="/prestasi/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-trophy-fill"></i>
                        <span>Tambah Prestasi</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Total Prestasi Dicatat</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-book-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Akademik</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.akademik }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Lomba & Riset Ilmiah</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="bi bi-controller"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-purple-600">Non-Akademik</span>
                    </div>
                    <div class="text-2xl font-black text-purple-700">{{ stats.non_akademik }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Seni, Olahraga, Minat</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Internasional/Nas</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.internasional + stats.nasional }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">{{ stats.internasional }} Internasional · {{ stats.nasional }} Nasional</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari kegiatan, penyelenggara, mahasiswa..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.jenis_prestasi"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-amber-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Jenis Prestasi</option>
                            <option v-for="j in jenisOptions" :key="j" :value="j">{{ j }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tingkat"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-amber-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tingkat Wilayah</option>
                            <option v-for="t in tingkatOptions" :key="t" :value="t">{{ t }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tahun"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-amber-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tahun</option>
                            <option v-for="th in tahunList" :key="th" :value="th">Tahun {{ th }}</option>
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
                                <th class="px-5 py-3.5 text-left">Nama Kegiatan & Mahasiswa</th>
                                <th class="px-4 py-3.5 text-left">Jenis & Tingkat</th>
                                <th class="px-4 py-3.5 text-left">Penyelenggara / Peringkat</th>
                                <th class="px-4 py-3.5 text-left">Tahun</th>
                                <th class="px-4 py-3.5 text-left">Sertifikat</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="prestasis.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-trophy"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Belum ada data prestasi mahasiswa</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Klik Tambah Prestasi untuk mencatat prestasi baru</p>
                                </td>
                            </tr>
                            <tr
                                v-for="p in prestasis.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5">
                                    <span class="font-black text-slate-900 block">{{ p.nama_kegiatan }}</span>
                                    <span class="text-[11px] text-slate-600 block mt-0.5">
                                        <i class="bi bi-person-fill text-slate-400 me-1"></i>
                                        <span class="font-bold">{{ p.mahasiswa_nama }}</span>
                                        <span class="font-mono text-slate-400"> ({{ p.mahasiswa_nim }})</span>
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold block w-fit mb-1" :class="p.jenis_prestasi === 'Akademik' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200'">
                                        {{ p.jenis_prestasi }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider" :class="tingkatBadgeClass(p.tingkat)">
                                        {{ p.tingkat }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="font-bold text-slate-800 block">{{ p.penyelenggara || '-' }}</span>
                                    <span v-if="p.peringkat" class="text-[10px] font-semibold text-amber-700 block">
                                        <i class="bi bi-award-fill text-amber-500 me-0.5"></i> {{ p.peringkat }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 font-mono">{{ p.tahun }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <a
                                        v-if="p.sertifikat_url"
                                        :href="p.sertifikat_url"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition"
                                    >
                                        <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i>
                                        <span>Lihat PDF</span>
                                    </a>
                                    <span v-else class="text-slate-400 text-[11px]">-</span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/prestasi/${p.id}/edit`"
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

                <div v-if="prestasis.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ prestasis.from }}–{{ prestasis.to }} dari {{ prestasis.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in prestasis.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-amber-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
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
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Data Prestasi?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data prestasi "<span class="font-bold text-slate-800">{{ deleteTarget.nama_kegiatan }}</span>" akan dihapus permanen.
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
    prestasis: Object,
    stats: Object,
    jenisOptions: Array,
    tingkatOptions: Array,
    tahunList: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    jenis_prestasi: props.filters?.jenis_prestasi || '',
    tingkat: props.filters?.tingkat || '',
    tahun: props.filters?.tahun || '',
});

function applyFilters() {
    router.get('/prestasi', filters, { preserveState: true, replace: true });
}

// Delete Confirmation
const deleteTarget = ref(null);
function confirmDelete(p) {
    deleteTarget.value = p;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/prestasi/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function tingkatBadgeClass(tingkat) {
    switch (tingkat) {
        case 'Internasional':
            return 'bg-amber-100 text-amber-800 border border-amber-300';
        case 'Nasional':
            return 'bg-blue-100 text-blue-800 border border-blue-300';
        case 'Lokal':
            return 'bg-slate-100 text-slate-700 border border-slate-200';
        default:
            return 'bg-slate-100 text-slate-700';
    }
}
</script>
