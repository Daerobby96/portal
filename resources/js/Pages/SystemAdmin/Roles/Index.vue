<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    roles: Array,
    groupedPermissions: Object,
    allPermissions: Array,
});

const createModalOpen = ref(false);
const editModalOpen = ref(false);
const selectedRole = ref(null);

const createForm = useForm({
    name: '',
    display_name: '',
    description: '',
    permissions: [],
});

const editForm = useForm({
    display_name: '',
    description: '',
    permissions: [],
});

const openEditModal = (role) => {
    selectedRole.value = role;
    editForm.display_name = role.display_name || role.name;
    editForm.description = role.description || '';
    editForm.permissions = role.permissions ? role.permissions.map(p => p.name) : [];
    editModalOpen.value = true;
};

const togglePermission = (formTarget, permName) => {
    const list = formTarget.permissions;
    const idx = list.indexOf(permName);
    if (idx > -1) {
        list.splice(idx, 1);
    } else {
        list.push(permName);
    }
};

const toggleGroup = (formTarget, groupPerms) => {
    const groupNames = groupPerms.map(p => p.name);
    const allSelected = groupNames.every(name => formTarget.permissions.includes(name));
    
    if (allSelected) {
        formTarget.permissions = formTarget.permissions.filter(name => !groupNames.includes(name));
    } else {
        const newSet = new Set([...formTarget.permissions, ...groupNames]);
        formTarget.permissions = Array.from(newSet);
    }
};

const submitCreate = () => {
    createForm.post('/roles', {
        onSuccess: () => {
            createModalOpen.value = false;
            createForm.reset();
        }
    });
};

const submitEdit = () => {
    if (!selectedRole.value) return;
    editForm.put(`/roles/${selectedRole.value.id}`, {
        onSuccess: () => {
            editModalOpen.value = false;
            selectedRole.value = null;
        }
    });
};

const deleteRole = (role) => {
    if (confirm(`Apakah Anda yakin ingin menghapus role "${role.name}"?`)) {
        router.delete(`/roles/${role.id}`);
    }
};

const isCoreRole = (name) => {
    return ['super_admin', 'auditor', 'auditee', 'pimpinan', 'staff'].includes(name);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Manajemen Role & Hak Akses" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-indigo-200 border border-white/20 uppercase tracking-wider">
                            RBAC Security
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                            Spatie Permissions
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Manajemen Peran & Hak Akses (Roles)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola matriks perizinan (*Permission Matrix*), pembatasan akses modul, serta pembuatan peran baru untuk sivitas akademika.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        @click="createModalOpen = true"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Tambah Role Baru</span>
                    </button>
                </div>
            </div>

            <!-- Role Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="r in roles"
                    :key="r.id"
                    class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4 relative overflow-hidden group hover:border-indigo-300 transition"
                >
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                {{ r.name }}
                            </span>
                            <span
                                v-if="isCoreRole(r.name)"
                                class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200/60"
                            >
                                Core Role
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-black text-slate-900 group-hover:text-indigo-600 transition">
                                {{ r.display_name || r.name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed min-h-[36px]">
                                {{ r.description || 'Tidak ada deskripsi peran.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-xs">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2">
                                <i class="bi bi-people text-indigo-600 text-sm"></i>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Pengguna</p>
                                    <p class="font-black text-slate-800">{{ r.users_count || 0 }} User</p>
                                </div>
                            </div>

                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2">
                                <i class="bi bi-shield-check text-purple-600 text-sm"></i>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Izin Akses</p>
                                    <p class="font-black text-slate-800">{{ r.permissions?.length || 0 }} Izin</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <button
                            @click="openEditModal(r)"
                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                        >
                            <i class="bi bi-gear-fill"></i>
                            <span>Atur Hak Akses</span>
                        </button>

                        <button
                            v-if="!isCoreRole(r.name)"
                            @click="deleteRole(r)"
                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                            title="Hapus Role"
                        >
                            <i class="bi bi-trash3 text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Role Baru -->
            <div v-if="createModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl border border-slate-100 space-y-5 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="bi bi-shield-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Buat Peran / Role Baru</h3>
                                <p class="text-[11px] text-slate-400">Tentukan nama identifier role dan izin aksesnya</p>
                            </div>
                        </div>
                        <button @click="createModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitCreate" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Identifier Role <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="createForm.name"
                                type="text"
                                required
                                placeholder="Contoh: kaprodi / dekan / kabag_sdm"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono"
                            />
                            <p class="text-[10px] text-slate-400 mt-1">Gunakan huruf kecil tanpa spasi (misal: <code>dekan</code> atau <code>staf_keuangan</code>).</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Nama Tampilan (Display Name)
                            </label>
                            <input
                                v-model="createForm.display_name"
                                type="text"
                                placeholder="Contoh: Dekan Fakultas"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Deskripsi Peran
                            </label>
                            <textarea
                                v-model="createForm.description"
                                rows="2"
                                placeholder="Jelaskan ruang lingkup hak akses peran ini..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="createForm.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30">
                                {{ createForm.processing ? 'Menyimpan...' : 'Simpan Role' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Role Permissions Matrix -->
            <div v-if="editModalOpen && selectedRole" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-3xl w-full shadow-2xl border border-slate-100 space-y-5 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                                <i class="bi bi-sliders"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Atur Hak Akses: {{ selectedRole.display_name || selectedRole.name }}</h3>
                                <p class="text-[11px] text-slate-400">Centang izin yang diperbolehkan untuk peran ini</p>
                            </div>
                        </div>
                        <button @click="editModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitEdit" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nama Tampilan</label>
                                <input v-model="editForm.display_name" type="text" class="w-full px-4 py-2 text-xs rounded-xl border border-slate-200" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Deskripsi</label>
                                <input v-model="editForm.description" type="text" class="w-full px-4 py-2 text-xs rounded-xl border border-slate-200" />
                            </div>
                        </div>

                        <!-- Permissions Matrix by Group -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Matriks Perizinan Modul</h4>
                            
                            <div
                                v-for="(perms, groupName) in groupedPermissions"
                                :key="groupName"
                                class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-3"
                            >
                                <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                    <span class="font-bold text-xs text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                        <i class="bi bi-folder-fill text-indigo-500"></i>
                                        Modul {{ groupName }}
                                    </span>
                                    <button
                                        type="button"
                                        @click="toggleGroup(editForm, perms)"
                                        class="text-[11px] font-bold text-indigo-600 hover:underline cursor-pointer"
                                    >
                                        Pilih Semua / Batal
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                    <label
                                        v-for="p in perms"
                                        :key="p.id"
                                        class="p-2.5 rounded-xl border flex items-center gap-2 cursor-pointer transition text-xs"
                                        :class="editForm.permissions.includes(p.name) ? 'border-indigo-600 bg-indigo-50/60 text-indigo-950 font-bold' : 'border-slate-200/80 bg-white text-slate-700'"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="p.name"
                                            :checked="editForm.permissions.includes(p.name)"
                                            @change="togglePermission(editForm, p.name)"
                                            class="rounded text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="font-mono text-[11px] truncate" :title="p.name">{{ p.name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="editForm.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30">
                                {{ editForm.processing ? 'Menyimpan...' : 'Simpan Hak Akses' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
