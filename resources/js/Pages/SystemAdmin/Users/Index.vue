<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    users: Object,
    roles: Array,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const role = ref(props.filters?.role || '');
const status = ref(props.filters?.status || '');
const importModalOpen = ref(false);

const importForm = useForm({
    file: null,
});

let searchTimeout = null;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 400);
};

const applyFilters = () => {
    router.get('/users', {
        search: search.value,
        role: role.value,
        status: status.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const toggleStatus = (user) => {
    router.patch(`/users/${user.id}/toggle-status`, {}, {
        preserveScroll: true,
    });
};

const deleteUser = (user) => {
    if (confirm(`Apakah Anda yakin ingin menghapus akun pengguna "${user.name}"?`)) {
        router.delete(`/users/${user.id}`, {
            preserveScroll: true,
        });
    }
};

const submitImport = () => {
    importForm.post('/users/import', {
        onSuccess: () => {
            importModalOpen.value = false;
            importForm.reset();
        }
    });
};

const getRoleBadge = (roleName) => {
    const map = {
        'super_admin': 'bg-rose-50 text-rose-700 border-rose-200/60',
        'pimpinan': 'bg-amber-50 text-amber-700 border-amber-200/60',
        'auditor': 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
        'auditee': 'bg-blue-50 text-blue-700 border-blue-200/60',
        'staff': 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
    };
    return map[roleName] || 'bg-slate-50 text-slate-700 border-slate-200/60';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Manajemen Pengguna & Akun" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-indigo-200 border border-white/20 uppercase tracking-wider">
                            System Admin
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            Akses & Autentikasi
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Manajemen Pengguna & Akun
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola akun pengguna, penugasan role hak akses Spatie, data NIP/Unit Kerja, serta aktivasi status akun di PINTAR.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <button
                        @click="importModalOpen = true"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/20 cursor-pointer backdrop-blur-sm"
                    >
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                        <span>Import Excel</span>
                    </button>

                    <Link
                        href="/users/create"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Tambah Pengguna</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Akun Aktif</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nonaktif</p>
                        <p class="text-xl font-black text-rose-600 leading-tight">{{ stats?.nonaktif || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Role Terdaftar</p>
                        <p class="text-xl font-black text-purple-600 leading-tight">{{ stats?.roles_count || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3 flex-wrap">
                    <div class="relative flex-1 min-w-[240px]">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input
                            v-model="search"
                            @input="handleSearch"
                            type="text"
                            placeholder="Cari nama, NIP, email, jabatan, atau unit kerja..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                    </div>

                    <select
                        v-model="role"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Role</option>
                        <option v-for="r in roles" :key="r.id" :value="r.name">{{ r.name }}</option>
                    </select>

                    <select
                        v-model="status"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-6">Pengguna & Akun</th>
                                <th class="py-3.5 px-4">NIP / Kontak</th>
                                <th class="py-3.5 px-4">Unit Kerja & Jabatan</th>
                                <th class="py-3.5 px-4">Peran (Roles)</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="u in users.data"
                                :key="u.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div v-if="u.foto" class="w-9 h-9 rounded-xl overflow-hidden shrink-0 border border-slate-200">
                                            <img :src="`/storage/${u.foto}`" :alt="u.name" class="w-full h-full object-cover" />
                                        </div>
                                        <div v-else class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-xs shrink-0">
                                            {{ u.name?.charAt(0) }}
                                        </div>
                                        <div>
                                            <Link :href="`/users/${u.id}`" class="font-bold text-slate-900 hover:text-indigo-600 transition block">
                                                {{ u.name }}
                                            </Link>
                                            <span class="text-[11px] text-slate-400 font-mono">{{ u.email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-mono text-slate-800 font-semibold">{{ u.nip || '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ u.no_hp || '-' }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-slate-800">{{ u.unit_kerja || '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ u.jabatan || '-' }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1 flex-wrap">
                                        <span
                                            v-for="r in u.roles"
                                            :key="r.id"
                                            class="px-2 py-0.5 rounded-md text-[10px] font-bold border uppercase"
                                            :class="getRoleBadge(r.name)"
                                        >
                                            {{ r.name }}
                                        </span>
                                        <span v-if="!u.roles || u.roles.length === 0" class="text-slate-400 italic text-[11px]">
                                            Tanpa Role
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button
                                        @click="toggleStatus(u)"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border transition cursor-pointer"
                                        :class="u.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-200/60 hover:bg-rose-100'"
                                        title="Klik untuk ubah status akun"
                                    >
                                        <i class="bi" :class="u.is_active ? 'bi-check-circle-fill mr-1' : 'bi-x-circle-fill mr-1'"></i>
                                        {{ u.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/users/${u.id}`"
                                            class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Lihat Profil"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>
                                        <Link
                                            :href="`/users/${u.id}/edit`"
                                            class="p-2 rounded-xl text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition"
                                            title="Edit Pengguna"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>
                                        <button
                                            @click="deleteUser(u)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Pengguna"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!users.data || users.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada data pengguna yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.links && users.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ users.from || 0 }} - {{ users.to || 0 }} dari {{ users.total }} pengguna
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in users.links"
                            :key="i"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition"
                            :class="link.active ? 'bg-indigo-600 text-white font-bold' : (link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none')"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Import Users -->
            <div v-if="importModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Import Pengguna Massal</h3>
                                <p class="text-[11px] text-slate-400">Format file: Excel (.xlsx, .xls) / CSV</p>
                            </div>
                        </div>
                        <button @click="importModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitImport" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                                Pilih Berkas Excel <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="file"
                                accept=".xlsx,.xls,.csv"
                                required
                                @input="importForm.file = $event.target.files[0]"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl p-2"
                            />
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 flex items-center justify-between">
                            <span>Belum punya template Excel?</span>
                            <a href="/users/template" class="font-bold text-indigo-600 hover:underline flex items-center gap-1">
                                <i class="bi bi-download"></i>
                                Unduh Template
                            </a>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="importModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="importForm.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30">
                                {{ importForm.processing ? 'Mengunggah...' : 'Mulai Import' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
