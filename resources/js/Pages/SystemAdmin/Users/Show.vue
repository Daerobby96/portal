<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object,
});

const toggleStatus = () => {
    router.patch(`/users/${props.user.id}/toggle-status`, {}, {
        preserveScroll: true,
    });
};

const deleteUser = () => {
    if (confirm(`Hapus akun pengguna "${props.user.name}" secara permanen?`)) {
        router.delete(`/users/${props.user.id}`);
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const cleanStr = String(dateStr).split('T')[0];
    const parts = cleanStr.split('-');
    if (parts.length === 3) {
        const year = parts[0];
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return `${day < 10 ? '0' + day : day} ${months[month - 1] || ''} ${year}`;
    }
    return dateStr;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Profil Pengguna: ${user.name}`" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <Link href="/users" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Pengguna
                    </Link>
                    <div class="flex items-center gap-2 mb-2">
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase"
                            :class="user.is_active ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : 'bg-rose-500/30 text-rose-200 border border-rose-400/30'"
                        >
                            {{ user.is_active ? 'Akun Aktif' : 'Nonaktif' }}
                        </span>
                        <span
                            v-for="r in user.roles"
                            :key="r.id"
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 uppercase"
                        >
                            {{ r.name }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                        {{ user.name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl font-mono">
                        {{ user.email }} | {{ user.nip || 'No NIP' }}
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <button
                        @click="toggleStatus"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 border cursor-pointer backdrop-blur-sm"
                        :class="user.is_active ? 'bg-rose-500/20 text-rose-200 border-rose-500/30 hover:bg-rose-500/30' : 'bg-emerald-500/20 text-emerald-200 border-emerald-500/30 hover:bg-emerald-500/30'"
                    >
                        <i class="bi" :class="user.is_active ? 'bi-person-x' : 'bi-person-check'"></i>
                        <span>{{ user.is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}</span>
                    </button>

                    <Link
                        :href="`/users/${user.id}/edit`"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-pencil-fill"></i>
                        <span>Edit Profil</span>
                    </Link>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="flex items-center gap-4 border-b border-slate-100 pb-6">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-2xl shrink-0 overflow-hidden border border-slate-200">
                                <img v-if="user.foto" :src="`/storage/${user.foto}`" :alt="user.name" class="w-full h-full object-cover" />
                                <span v-else>{{ user.name?.charAt(0) }}</span>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900">{{ user.name }}</h3>
                                <p class="text-xs text-slate-500">{{ user.jabatan || 'Tenaga Pendidik / Kependidikan' }}</p>
                                <span class="text-[11px] text-slate-400 font-mono">{{ user.unit_kerja || 'Politeknik Kampus Akademik' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Alamat Email</span>
                                <p class="font-bold text-slate-900 font-mono text-sm">{{ user.email }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Nomor Induk Pegawai (NIP)</span>
                                <p class="font-bold text-slate-900 font-mono text-sm">{{ user.nip || '-' }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Nomor WhatsApp / Telepon</span>
                                <p class="font-bold text-slate-900 text-sm">{{ user.no_hp || '-' }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Terdaftar Sejak</span>
                                <p class="font-bold text-slate-900 text-sm font-mono">{{ formatDate(user.created_at) }}</p>
                            </div>
                        </div>

                        <!-- Direct Permissions List if any -->
                        <div v-if="user.permissions && user.permissions.length > 0" class="space-y-3">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Izin Akses Langsung (Direct Permissions)</h4>
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="p in user.permissions"
                                    :key="p.id"
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200"
                                >
                                    {{ p.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Peran Pengguna (Roles)</h4>
                        <div class="space-y-2">
                            <div
                                v-for="r in user.roles"
                                :key="r.id"
                                class="p-3 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900 font-bold flex items-center justify-between"
                            >
                                <span class="capitalize">{{ r.name }}</span>
                                <i class="bi bi-shield-check text-indigo-600 text-base"></i>
                            </div>
                            <p v-if="!user.roles || user.roles.length === 0" class="text-xs text-slate-400 italic">
                                Belum ada role yang diberikan.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-rose-600 uppercase tracking-wider">Hapus Akun Pengguna</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Menghapus akun pengguna akan mencabut seluruh akses sistem secara permanen.
                        </p>
                        <button
                            @click="deleteUser"
                            class="w-full py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center justify-center gap-2 border border-rose-200 cursor-pointer"
                        >
                            <i class="bi bi-trash3"></i>
                            <span>Hapus Akun Permanen</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
