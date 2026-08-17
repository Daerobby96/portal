<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object,
    roles: Array,
    permissions: Array,
    userRoles: Array,
    userPermissions: Array,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name || '',
    nip: props.user.nip || '',
    email: props.user.email || '',
    unit_kerja: props.user.unit_kerja || '',
    jabatan: props.user.jabatan || '',
    no_hp: props.user.no_hp || '',
    password: '',
    password_confirmation: '',
    roles: [...(props.userRoles || [])],
    permissions: [...(props.userPermissions || [])],
    foto: null,
});

const fotoPreview = ref(props.user.foto ? `/storage/${props.user.foto}` : null);

const handleFotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.foto = file;
        const reader = new FileReader();
        reader.onload = (event) => {
            fotoPreview.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const toggleRole = (roleName) => {
    const idx = form.roles.indexOf(roleName);
    if (idx > -1) {
        form.roles.splice(idx, 1);
    } else {
        form.roles.push(roleName);
    }
};

const submitForm = () => {
    form.post(`/users/${props.user.id}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Pengguna: ${user.name}`" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20 border border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <Link href="/users" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Manajemen Pengguna
                    </Link>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                        Edit Akun: {{ user.name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl font-mono">
                        {{ user.email }} | {{ user.nip || 'Tanpa NIP' }}
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    <!-- Foto & Identitas Utama -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-100 pb-6">
                        <div class="flex flex-col items-center gap-2.5 shrink-0">
                            <div class="w-24 h-24 rounded-3xl bg-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                                <img v-if="fotoPreview" :src="fotoPreview" alt="Preview Foto" class="w-full h-full object-cover" />
                                <i v-else class="bi bi-person text-3xl text-slate-400"></i>
                            </div>
                            <label class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold cursor-pointer transition">
                                <span>Ganti Foto</span>
                                <input type="file" accept="image/png,image/jpeg" class="hidden" @change="handleFotoChange" />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1 w-full">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-bold text-slate-900"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Email Login Institusi <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-slate-800"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nomor Induk Pegawai (NIP / NIDN)
                                </label>
                                <input
                                    v-model="form.nip"
                                    type="text"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono text-slate-800"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pekerjaan & Kontak -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 pb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Unit Kerja / Fakultas / Prodi
                            </label>
                            <input
                                v-model="form.unit_kerja"
                                type="text"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-slate-800"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Jabatan Struktural / Fungsional
                            </label>
                            <input
                                v-model="form.jabatan"
                                type="text"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-slate-800"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Nomor WhatsApp / Telepon
                            </label>
                            <input
                                v-model="form.no_hp"
                                type="text"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-slate-800"
                            />
                        </div>
                    </div>

                    <!-- Reset Password (Opsional) -->
                    <div class="p-4.5 rounded-2xl bg-amber-50/50 border border-amber-200/60 space-y-4">
                        <div class="flex items-center gap-2 text-amber-800 font-bold text-xs">
                            <i class="bi bi-key-fill"></i>
                            <span>Ganti Password Akun (Biarkan kosong jika tidak ingin mengubah password)</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Password Baru
                                </label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    minlength="8"
                                    placeholder="Kosongkan jika tetap"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 bg-white"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Konfirmasi Password Baru
                                </label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    minlength="8"
                                    placeholder="Ulangi password baru"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 bg-white"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Penugasan Peran (Roles) -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Penugasan Peran & Hak Akses (Spatie Roles)
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                            <div
                                v-for="r in roles"
                                :key="r.id"
                                @click="toggleRole(r.name)"
                                class="p-3.5 rounded-2xl border transition cursor-pointer flex flex-col justify-between"
                                :class="form.roles.includes(r.name) ? 'border-indigo-600 bg-indigo-50/50 text-indigo-950 font-bold shadow-xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
                            >
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs capitalize">{{ r.name }}</span>
                                    <i class="bi" :class="form.roles.includes(r.name) ? 'bi-check-circle-fill text-indigo-600' : 'bi-circle text-slate-300'"></i>
                                </div>
                                <span class="text-[10px] text-slate-400 font-normal truncate">{{ r.display_name || r.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3">
                    <Link href="/users" class="px-5 py-2.5 rounded-2xl text-xs font-semibold text-slate-600 hover:bg-slate-200/60 transition">
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-floppy-fill"></i>
                        <span>{{ form.processing ? 'Menyimpan...' : 'Perbarui Pengguna' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
