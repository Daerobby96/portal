<template>
    <AuthenticatedLayout title="Data Mahasiswa">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pangkalan Data Mahasiswa</h1>
                    <p class="text-xs text-slate-500 mt-1">Manajemen data induk mahasiswa, status studi, IPK, dan riwayat akademik.</p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">
                    <a
                        href="/mahasiswa/template"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-download text-slate-500"></i>
                        <span>Template Excel</span>
                    </a>
                    <button
                        @click="showImportModal = true"
                        type="button"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-sky-200 text-xs font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-cloud-arrow-up-fill text-sky-600"></i>
                        <span>Import Excel</span>
                    </button>
                    <Link
                        href="/mahasiswa/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Tambah Mahasiswa</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-base">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[10px] font-semibold text-slate-500 mt-0.5">Total Mahasiswa</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase text-emerald-600">Aktif</span>
                    </div>
                    <div class="text-xl font-black text-emerald-700">{{ stats.aktif }}</div>
                    <div class="text-[10px] font-semibold text-slate-500 mt-0.5">Mahasiswa Aktif</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase text-blue-600">Lulus</span>
                    </div>
                    <div class="text-xl font-black text-blue-700">{{ stats.lulus }}</div>
                    <div class="text-[10px] font-semibold text-slate-500 mt-0.5">Alumni Lulusan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                            <i class="bi bi-pause-circle-fill"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase text-amber-600">Cuti</span>
                    </div>
                    <div class="text-xl font-black text-amber-700">{{ stats.cuti }}</div>
                    <div class="text-[10px] font-semibold text-slate-500 mt-0.5">Status Cuti Studi</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase text-rose-600">DO / Undur</span>
                    </div>
                    <div class="text-xl font-black text-rose-700">{{ stats.do + stats.mengundurkan_diri }}</div>
                    <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ stats.do }} DO · {{ stats.mengundurkan_diri }} Undur</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
                            <i class="bi bi-stars"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase text-indigo-600">Rerata IPK</span>
                    </div>
                    <div class="text-xl font-black text-indigo-700">{{ stats.avg_ipk }}</div>
                    <div class="text-[10px] font-semibold text-slate-500 mt-0.5">Skala 4.00</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari nama atau NIM..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.prodi"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-sky-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Program Studi</option>
                            <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.angkatan"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-sky-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Angkatan</option>
                            <option v-for="a in angkatans" :key="a" :value="a">Angkatan {{ a }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-sky-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option v-for="(lbl, val) in statusOptions" :key="val" :value="val">{{ lbl }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.jenis_kelamin"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-sky-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Gender</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
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
                                <th class="px-5 py-3.5 text-left">NIM & Mahasiswa</th>
                                <th class="px-4 py-3.5 text-left">Program Studi</th>
                                <th class="px-4 py-3.5 text-left">Angkatan / Smstr</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-left">IPK</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="mahasiswas.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Belum ada data mahasiswa</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Silakan import data Excel atau tambah manual</p>
                                </td>
                            </tr>
                            <tr
                                v-for="m in mahasiswas.data"
                                :key="m.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-black text-xs shrink-0">
                                            {{ m.nama ? m.nama.charAt(0).toUpperCase() : 'M' }}
                                        </div>
                                        <div>
                                            <Link :href="`/mahasiswa/${m.id}`" class="font-black text-slate-900 hover:text-sky-600 transition block">
                                                {{ m.nama }}
                                            </Link>
                                            <span class="font-mono text-[10px] text-slate-400 block">NIM: {{ m.nim }} · {{ m.jenis_kelamin === 'L' ? 'Laki-laki' : (m.jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block">{{ m.prodi_nama || '-' }}</span>
                                    <span v-if="m.jalur_masuk" class="text-[10px] text-slate-400 block">Jalur: {{ m.jalur_masuk }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block font-mono">Angkatan {{ m.angkatan || '-' }}</span>
                                    <span class="text-[10px] text-slate-500 block">Semester {{ m.semester_berjalan || '-' }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(m.status)">
                                        {{ statusOptions[m.status] || m.status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-900 block font-mono">{{ m.ipk !== null ? Number(m.ipk).toFixed(2) : '-' }}</span>
                                    <span v-if="m.masa_studi_bulan" class="text-[10px] text-slate-400 block">{{ m.masa_studi_bulan }} bln</span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/mahasiswa/${m.id}`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition cursor-pointer"
                                            title="Lihat Detail Profil"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <Link
                                            :href="`/mahasiswa/${m.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                            title="Edit Data"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>
                                        <button
                                            @click="confirmDelete(m)"
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

                <div v-if="mahasiswas.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ mahasiswas.from }}–{{ mahasiswas.to }} dari {{ mahasiswas.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in mahasiswas.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-sky-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
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
                            <h3 class="font-black text-slate-900 text-sm">Import Data Mahasiswa</h3>
                            <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitImport" class="space-y-4">
                            <div class="border-2 border-dashed border-slate-200 hover:border-sky-400 rounded-2xl p-6 text-center transition bg-slate-50/50">
                                <i class="bi bi-file-earmark-spreadsheet-fill text-3xl text-sky-600 mb-2 block"></i>
                                <p class="text-xs font-bold text-slate-700">Pilih Berkas Excel Mahasiswa</p>
                                <p class="text-[10px] text-slate-400 mb-3">Format .xlsx, .xls, .csv (Maks 10MB)</p>
                                <input
                                    type="file"
                                    required
                                    accept=".xlsx,.xls,.csv"
                                    @change="handleImportFile"
                                    class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer"
                                />
                            </div>

                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-[11px] flex items-start gap-2">
                                <i class="bi bi-info-circle-fill text-blue-600 shrink-0 mt-0.5"></i>
                                <span>Gunakan format template yang disediakan untuk menghindari kesalahan pencocokan kolom.</span>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showImportModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="importing"
                                    class="px-5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <i v-if="importing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Unggah & Import</span>
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
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Data Mahasiswa?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data mahasiswa "<span class="font-bold text-slate-800">{{ deleteTarget.nama }}</span>" (NIM: {{ deleteTarget.nim }}) akan dihapus permanen.
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
    mahasiswas: Object,
    stats: Object,
    prodis: Array,
    angkatans: Array,
    statusOptions: Object,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    prodi: props.filters?.prodi || '',
    angkatan: props.filters?.angkatan || '',
    status: props.filters?.status || '',
    jenis_kelamin: props.filters?.jenis_kelamin || '',
});

function applyFilters() {
    router.get('/mahasiswa', filters, { preserveState: true, replace: true });
}

// Import Modal
const showImportModal = ref(false);
const importFile = ref(null);
const importing = ref(false);

function handleImportFile(e) {
    importFile.value = e.target.files[0];
}

function submitImport() {
    if (!importFile.value) return;
    importing.value = true;
    const formData = new FormData();
    formData.append('file', importFile.value);

    router.post('/mahasiswa/import', formData, {
        forceFormData: true,
        onSuccess: () => {
            showImportModal.value = false;
            importFile.value = null;
        },
        onFinish: () => {
            importing.value = false;
        },
    });
}

// Delete Confirmation
const deleteTarget = ref(null);
function confirmDelete(m) {
    deleteTarget.value = m;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/mahasiswa/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function statusBadgeClass(status) {
    switch (status) {
        case 'aktif':
            return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        case 'lulus':
            return 'bg-blue-50 text-blue-700 border border-blue-200';
        case 'cuti':
            return 'bg-amber-50 text-amber-700 border border-amber-200';
        case 'DO':
        case 'mengundurkan_diri':
            return 'bg-rose-50 text-rose-700 border border-rose-200';
        default:
            return 'bg-slate-100 text-slate-700';
    }
}
</script>
