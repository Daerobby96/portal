<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pegawais: Object,
    stats: Object,
    unitKerjas: Array,
    jabatans: Array,
    roles: Array,
    permissions: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const jenis = ref(props.filters?.jenis || '');
const unitKerjaId = ref(props.filters?.unit_kerja_id || '');
const jabatanId = ref(props.filters?.jabatan_id || '');
const status = ref(props.filters?.status || '');

const handleFilter = () => {
    router.get('/sdm/pegawai', {
        search: search.value,
        jenis: jenis.value,
        unit_kerja_id: unitKerjaId.value,
        jabatan_id: jabatanId.value,
        status: status.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const toggleStatus = (p) => {
    router.post(`/sdm/pegawai/${p.id}/toggle-status`, {}, {
        preserveScroll: true,
    });
};

const deletePegawai = (p) => {
    if (confirm(`Hapus data pegawai "${p.nama}"? Tindakan ini tidak dapat dibatalkan.`)) {
        router.delete(`/sdm/pegawai/${p.id}`);
    }
};

// Modal Buat Akun User
const userModalOpen = ref(false);
const selectedPegawai = ref(null);
const userForm = useForm({
    password: '',
    password_confirmation: '',
    roles: ['dosen'],
});

const openUserModal = (p) => {
    selectedPegawai.value = p;
    userForm.roles = p.jenis_pegawai === 'Dosen' ? ['dosen'] : ['staff'];
    userModalOpen.value = true;
};

const submitUser = () => {
    userForm.post(`/sdm/pegawai/${selectedPegawai.value.id}/create-user`, {
        onSuccess: () => {
            userModalOpen.value = false;
            userForm.reset();
        }
    });
};

// Modal Import Excel
const importModalOpen = ref(false);
const importForm = useForm({
    file: null,
});

const submitImport = () => {
    importForm.post('/sdm/pegawai/import', {
        onSuccess: () => {
            importModalOpen.value = false;
            importForm.reset();
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Master Pegawai, Dosen & Tendik" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-person-vcard"></i>
                        <span>Modul SDM & Kepegawaian</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Master Pegawai, Dosen & Tendik
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola direktori data induk dosen tridharma, tenaga kependidikan, nomor registrasi NIP/NIDN, dan integrasi akun sistem ERP.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <button
                        @click="importModalOpen = true"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold transition flex items-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        <span>Import Excel</span>
                    </button>
                    <Link
                        href="/sdm/pegawai/create"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Tambah Pegawai</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pegawai</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dosen Tridharma</p>
                        <p class="text-xl font-black text-purple-600 leading-tight">{{ stats?.dosen || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tenaga Kependidikan</p>
                        <p class="text-xl font-black text-blue-600 leading-tight">{{ stats?.tendik || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Aktif</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="w-full md:w-80 relative">
                        <input
                            v-model="search"
                            @input="handleFilter"
                            type="text"
                            placeholder="Cari nama, NIP, email, atau jabatan..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    </div>

                    <div class="w-full md:w-auto flex items-center gap-2.5 flex-wrap">
                        <select
                            v-model="jenis"
                            @change="handleFilter"
                            class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Jenis</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Tenaga Kependidikan">Tenaga Kependidikan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>

                        <select
                            v-model="unitKerjaId"
                            @change="handleFilter"
                            class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 max-w-[200px]"
                        >
                            <option value="">Semua Unit Kerja</option>
                            <option v-for="u in unitKerjas" :key="u.id" :value="u.id">{{ u.nama }}</option>
                        </select>

                        <select
                            v-model="jabatanId"
                            @change="handleFilter"
                            class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500 max-w-[200px]"
                        >
                            <option value="">Semua Jabatan</option>
                            <option v-for="j in jabatans" :key="j.id" :value="j.id">{{ j.nama }}</option>
                        </select>

                        <select
                            v-model="status"
                            @change="handleFilter"
                            class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Nama & NIP</th>
                                <th class="py-3.5 px-4">Jabatan & Unit</th>
                                <th class="py-3.5 px-4 text-center">Jenis Pegawai</th>
                                <th class="py-3.5 px-4 text-center">Akun Sistem</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="p in pegawais.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <Link :href="`/sdm/pegawai/${p.id}`" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                        {{ p.nama }}
                                    </Link>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                        {{ p.nip || 'Belum ada NIP' }}
                                        <span v-if="p.email" class="text-slate-300 mx-1">|</span>
                                        <span v-if="p.email" class="text-slate-400 font-sans">{{ p.email }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-slate-800">{{ p.nama_jabatan || p.jabatan || '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ p.nama_unit_kerja || p.unit_kerja || '-' }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                                        :class="p.jenis_pegawai === 'Dosen' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200'"
                                    >
                                        {{ p.jenis_pegawai }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        v-if="p.user_id"
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                        title="Sudah terhubung ke akun User"
                                    >
                                        <i class="bi bi-shield-check"></i>
                                        Tersinkron
                                    </span>
                                    <button
                                        v-else
                                        @click="openUserModal(p)"
                                        class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition cursor-pointer border border-indigo-200"
                                        title="Buat akun login sistem"
                                    >
                                        + Buat Akun
                                    </button>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button
                                        @click="toggleStatus(p)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold transition cursor-pointer"
                                        :class="p.is_aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500'"
                                        :title="p.is_aktif ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" :class="p.is_aktif ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        {{ p.is_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/sdm/pegawai/${p.id}`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Detail Profil"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <Link
                                            :href="`/sdm/pegawai/${p.id}/edit`"
                                            class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit Data"
                                        >
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deletePegawai(p)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Pegawai"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!pegawais.data || pegawais.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Belum ada data pegawai yang terdaftar sesuai filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="pegawais.links && pegawais.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ pegawais.from }} - {{ pegawais.to }} dari total {{ pegawais.total }} data
                    </span>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in pegawais.links"
                            :key="i"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                            :class="link.active ? 'bg-indigo-600 text-white shadow-xs' : link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none'"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Buat Akun User -->
            <div v-if="userModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="bi bi-person-lock"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Buat Akun Sistem</h3>
                                <p class="text-[11px] text-slate-400 truncate">{{ selectedPegawai?.nama }}</p>
                            </div>
                        </div>
                        <button @click="userModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitUser" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Pegawai</label>
                            <input :value="selectedPegawai?.email" disabled class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-100 border border-slate-200 text-slate-500 font-semibold" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru <span class="text-rose-500">*</span></label>
                            <input v-model="userForm.password" type="password" required placeholder="Minimal 8 karakter" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                            <input v-model="userForm.password_confirmation" type="password" required placeholder="Ulangi password" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="userModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="userForm.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30">
                                {{ userForm.processing ? 'Membuat...' : 'Buat Akun' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Import Excel -->
            <div v-if="importModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Import Data Pegawai</h3>
                                <p class="text-[11px] text-slate-400">Format Excel (.xlsx / .csv)</p>
                            </div>
                        </div>
                        <button @click="importModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitImport" class="space-y-4">
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 text-xs text-indigo-900">
                            <p class="font-bold mb-1">Gunakan Template Standar</p>
                            <p class="text-[11px] text-indigo-700 leading-relaxed mb-2">Pastikan kolom NIP, Nama, Email, Jenis Pegawai, dan Unit Kerja terisi sesuai format.</p>
                            <a href="/sdm/pegawai/download-template" class="inline-flex items-center gap-1.5 font-bold text-indigo-600 hover:underline">
                                <i class="bi bi-download"></i> Unduh Format Excel
                            </a>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Pilih Berkas Excel</label>
                            <input
                                type="file"
                                @change="e => importForm.file = e.target.files[0]"
                                accept=".xlsx,.xls,.csv"
                                required
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            />
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="importModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="importForm.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30">
                                {{ importForm.processing ? 'Mengunggah...' : 'Upload & Import' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
