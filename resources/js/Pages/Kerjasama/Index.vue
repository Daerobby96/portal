<template>
    <AuthenticatedLayout title="Kerjasama & Mitra">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Kerja Sama & Mitra</h1>
                    <p class="text-xs text-slate-500 mt-1">Pendataan MoU, MoA, Implementation Agreement (IA) dan evaluasi kemitraan institusi.</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <button
                        @click="showImportModal = true"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-file-earmark-spreadsheet text-emerald-600 text-sm"></i>
                        <span>Import Excel</span>
                    </button>

                    <Link
                        href="/kerjasama/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Kerjasama</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Mitra & MoU</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Aktif</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.aktif }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Kerjasama Berjalan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Internasional</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.internasional }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Mitra Luar Negeri</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-flag-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Nasional</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.nasional }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Mitra Dalam Negeri</div>
                </div>
            </div>

            <!-- Filters & Search Toolbar -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                    <!-- Search Input -->
                    <div class="sm:col-span-2 md:col-span-2">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari nama mitra atau judul kegiatan..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition bg-white"
                            />
                        </div>
                    </div>

                    <!-- Tingkat -->
                    <div>
                        <select
                            v-model="filters.tingkat"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Tingkat</option>
                            <option v-for="t in tingkatList" :key="t" :value="t">{{ t }}</option>
                        </select>
                    </div>

                    <!-- Jenis Dokumen -->
                    <div>
                        <select
                            v-model="filters.jenis_dokumen"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Dokumen</option>
                            <option v-for="jd in jenisDokumen" :key="jd" :value="jd">{{ jd }}</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option v-for="st in statusList" :key="st" :value="st">{{ st }}</option>
                        </select>
                    </div>
                </div>

                <div v-if="hasActiveFilter" class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-500 text-[11px]">Filter aktif diterapkan</span>
                    <button
                        @click="resetFilters"
                        type="button"
                        class="text-[11px] font-bold text-pink-600 hover:text-pink-800 flex items-center gap-1 cursor-pointer"
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
                                <th class="px-5 py-3.5 text-left">Mitra & Lembaga</th>
                                <th class="px-4 py-3.5 text-left">Judul Kerjasama & Dokumen</th>
                                <th class="px-4 py-3.5 text-left">Tingkat</th>
                                <th class="px-4 py-3.5 text-left">Masa Berlaku</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="kerjasamas.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-folder-x"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada data kerjasama ditemukan</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Silakan sesuaikan filter atau tambahkan data kerjasama baru.</p>
                                </td>
                            </tr>
                            <tr
                                v-for="k in kerjasamas.data"
                                :key="k.id"
                                class="hover:bg-slate-50/70 transition group"
                            >
                                <!-- Nama Mitra & Jenis -->
                                <td class="px-5 py-3.5 max-w-xs">
                                    <div class="flex items-start gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-base shrink-0 mt-0.5">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <Link :href="`/kerjasama/${k.id}`" class="font-black text-slate-900 hover:text-pink-600 transition block truncate leading-snug">
                                                {{ k.nama_mitra }}
                                            </Link>
                                            <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">
                                                {{ k.jenis_mitra }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Judul Kegiatan & Dokumen -->
                                <td class="px-4 py-3.5 max-w-xs">
                                    <p class="font-bold text-slate-800 truncate leading-snug" :title="k.judul_kerjasama">
                                        {{ k.judul_kerjasama }}
                                    </p>
                                    <div class="flex items-center gap-1.5 flex-wrap mt-1">
                                        <span v-if="k.jenis_dokumen" class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-blue-50 text-blue-700 border border-blue-200/60">
                                            {{ k.jenis_dokumen }}
                                        </span>
                                        <span v-if="k.prodi_nama" class="px-1.5 py-0.2 rounded text-[9px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Prodi: {{ k.prodi_nama }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Tingkat -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="tingkatBadgeClass(k.tingkat)">
                                        {{ k.tingkat }}
                                    </span>
                                </td>

                                <!-- Masa Berlaku -->
                                <td class="px-4 py-3.5 whitespace-nowrap text-slate-600">
                                    <div class="flex items-center gap-1.5 font-semibold text-[11px]">
                                        <i class="bi bi-play-circle text-teal-600 text-xs"></i>
                                        <span>{{ k.tanggal_mulai }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5" :class="k.is_expiring ? 'text-rose-600 font-bold' : 'text-slate-400 text-[10px]'">
                                        <i class="bi bi-stop-circle text-xs"></i>
                                        <span>{{ k.tanggal_selesai }}</span>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(k.status)">
                                        {{ k.status }}
                                    </span>
                                    <div v-if="k.is_expiring" class="flex items-center gap-1 text-[9px] text-rose-600 font-bold mt-1">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        <span>Akan Kedaluwarsa</span>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a
                                            v-if="k.dokumen_mou"
                                            :href="k.dokumen_mou"
                                            target="_blank"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                            title="Lihat Dokumen MoU (PDF)"
                                        >
                                            <i class="bi bi-file-earmark-pdf text-sm"></i>
                                        </a>

                                        <Link
                                            :href="`/kerjasama/${k.id}`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-pink-600 hover:bg-pink-50 transition"
                                            title="Detail & Evaluasi Mitra"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>

                                        <Link
                                            :href="`/kerjasama/${k.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Data"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>

                                        <button
                                            @click="confirmDelete(k)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Data"
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
                <div v-if="kerjasamas.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ kerjasamas.from }}–{{ kerjasamas.to }} dari {{ kerjasamas.total }} kerjasama</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in kerjasamas.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-pink-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Import Modal -->
            <Teleport to="body">
                <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-file-earmark-spreadsheet text-emerald-600 text-lg"></i>
                                <h3 class="font-black text-slate-900 text-sm">Import Data Kerjasama</h3>
                            </div>
                            <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-600 leading-relaxed">
                            Gunakan format Excel standar untuk mengimpor data secara massal.
                            <a href="/kerjasama/template" class="font-bold text-pink-600 hover:underline block mt-1">
                                <i class="bi bi-download mr-1"></i>Unduh Template Excel (.xlsx)
                            </a>
                        </div>

                        <form @submit.prevent="submitImport" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Berkas Excel / CSV</label>
                                <input
                                    type="file"
                                    accept=".xlsx,.xls,.csv"
                                    required
                                    @change="e => importFile = e.target.files[0]"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none"
                                />
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showImportModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="importing || !importFile"
                                    class="px-5 py-2 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5"
                                >
                                    <i v-if="importing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Mulai Import</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Kerjasama?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data kerjasama dengan "<span class="font-bold text-slate-800">{{ deleteTarget.nama_mitra }}</span>" dan riwayat evaluasinya akan dihapus.
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
    kerjasamas: Object,
    stats: Object,
    filters: Object,
    jenisMitra: Array,
    tingkatList: Array,
    jenisDokumen: Array,
    statusList: Array,
});

const filters = reactive({
    search: props.filters?.search || '',
    tingkat: props.filters?.tingkat || '',
    jenis_dokumen: props.filters?.jenis_dokumen || '',
    status: props.filters?.status || '',
});

const hasActiveFilter = computed(() => {
    return !!(filters.search || filters.tingkat || filters.jenis_dokumen || filters.status);
});

function applyFilters() {
    router.get('/kerjasama', filters, { preserveState: true, replace: true });
}

function resetFilters() {
    filters.search = '';
    filters.tingkat = '';
    filters.jenis_dokumen = '';
    filters.status = '';
    applyFilters();
}

// Delete modal
const deleteTarget = ref(null);
function confirmDelete(k) {
    deleteTarget.value = k;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/kerjasama/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

// Import modal
const showImportModal = ref(false);
const importFile = ref(null);
const importing = ref(false);

function submitImport() {
    if (!importFile.value) return;
    importing.value = true;
    const data = new FormData();
    data.append('file', importFile.value);

    router.post('/kerjasama/import', data, {
        onSuccess: () => {
            showImportModal.value = false;
            importFile.value = null;
        },
        onFinish: () => {
            importing.value = false;
        },
    });
}

function tingkatBadgeClass(tingkat) {
    const map = {
        'Internasional': 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'Nasional': 'bg-blue-50 text-blue-700 border border-blue-200/60',
        'Lokal': 'bg-slate-100 text-slate-600 border border-slate-200',
    };
    return map[tingkat] || 'bg-slate-100 text-slate-600';
}

function statusBadgeClass(status) {
    const map = {
        'Aktif': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'Draft': 'bg-slate-100 text-slate-600 border border-slate-200',
        'Selesai': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Kedaluwarsa': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
