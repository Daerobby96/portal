<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pegawai: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Profil Pegawai: ${pegawai.nama}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-gradient-to-tr from-indigo-500 to-violet-600 text-white font-black text-2xl sm:text-3xl flex items-center justify-center shadow-lg shadow-indigo-600/30 ring-4 ring-white/10 shrink-0">
                        {{ pegawai.nama.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                                {{ pegawai.jenis_pegawai }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase" :class="pegawai.is_aktif ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : 'bg-rose-500/30 text-rose-200 border border-rose-400/30'">
                                {{ pegawai.is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                            {{ pegawai.nama }}
                        </h1>
                        <p class="text-xs text-slate-300 font-mono mt-0.5">
                            NIP/NIDN: {{ pegawai.nip || 'Belum diisi' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <Link
                        :href="`/sdm/pegawai/${pegawai.id}/edit`"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit Pegawai</span>
                    </Link>
                    <a
                        href="/sdm/pegawai"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition"
                    >
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Identity Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3">
                            <i class="bi bi-person-lines-fill text-indigo-600"></i>
                            <span>Data Kepegawaian & Jabatan</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Jabatan Fungsional / Struktural</span>
                                <p class="font-bold text-slate-900 text-sm">{{ pegawai.jabatan || '-' }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Unit Kerja / Homebase</span>
                                <p class="font-bold text-slate-900 text-sm">{{ pegawai.unit_kerja || '-' }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Status Kepegawaian</span>
                                <p class="font-bold text-slate-900 text-sm">{{ pegawai.status_kepegawaian || 'Tetap Yayasan' }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Jenis Pegawai</span>
                                <p class="font-bold text-indigo-700 text-sm">{{ pegawai.jenis_pegawai }}</p>
                            </div>
                        </div>

                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-3 pt-2">
                            <i class="bi bi-telephone-fill text-indigo-600"></i>
                            <span>Kontak & Komunikasi</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Email Resmi</span>
                                <p class="font-bold text-slate-900 font-mono">{{ pegawai.email || '-' }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Nomor WhatsApp / HP</span>
                                <p class="font-bold text-slate-900 font-mono">{{ pegawai.no_hp || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Account Status Card -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Integrasi Akun ERP</h4>
                        <div v-if="pegawai.user" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs space-y-2">
                            <div class="flex items-center gap-2 text-emerald-800 font-bold">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Akun Aktif Terhubung</span>
                            </div>
                            <p class="text-slate-600"><strong>Username:</strong> {{ pegawai.user.name }}</p>
                            <p class="text-slate-600"><strong>Email Login:</strong> {{ pegawai.user.email }}</p>
                        </div>
                        <div v-else class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-2">
                            <div class="flex items-center gap-2 font-bold">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Belum Memiliki Akun Login</span>
                            </div>
                            <p class="text-[11px] text-amber-700 leading-relaxed">
                                Buat akun login melalui tabel master pegawai untuk memberikan akses modul sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
