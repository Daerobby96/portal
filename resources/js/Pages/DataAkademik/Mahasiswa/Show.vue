<template>
    <AuthenticatedLayout :title="`Profil Mahasiswa - ${mahasiswa.nama}`">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-700 text-white flex items-center justify-center font-black text-lg shadow-sm">
                        {{ mahasiswa.nama ? mahasiswa.nama.charAt(0).toUpperCase() : 'M' }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl font-black text-slate-900 tracking-tight">{{ mahasiswa.nama }}</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(mahasiswa.status)">
                                {{ mahasiswa.status }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 font-mono">NIM: {{ mahasiswa.nim }} · {{ mahasiswa.prodi_nama || '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        :href="`/mahasiswa/${mahasiswa.id}/edit`"
                        class="inline-flex items-center gap-2 px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit Profil</span>
                    </Link>
                    <Link
                        href="/mahasiswa"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition"
                    >
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </Link>
                </div>
            </div>

            <!-- Profile Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Academic Summary Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4">
                    <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-2">
                        <i class="bi bi-mortarboard text-sky-600"></i>
                        <span>Ringkasan Akademik</span>
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 rounded-2xl bg-sky-50/70 border border-sky-100 flex items-center justify-between">
                            <span class="text-sky-900 font-bold">Indeks Prestasi (IPK)</span>
                            <span class="text-xl font-black text-sky-700 font-mono">{{ mahasiswa.ipk !== null ? Number(mahasiswa.ipk).toFixed(2) : '-' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 text-[11px] block">Program Studi</span>
                            <strong class="text-slate-800">{{ mahasiswa.prodi_nama || '-' }}</strong>
                        </div>

                        <div>
                            <span class="text-slate-400 text-[11px] block">Angkatan & Semester</span>
                            <span class="text-slate-800 font-semibold font-mono">Angkatan {{ mahasiswa.angkatan || '-' }} (Semester {{ mahasiswa.semester_berjalan || '-' }})</span>
                        </div>

                        <div>
                            <span class="text-slate-400 text-[11px] block">Jalur Masuk</span>
                            <span class="text-slate-800">{{ mahasiswa.jalur_masuk || '-' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 text-[11px] block">Periode Masuk</span>
                            <span class="text-slate-800">{{ mahasiswa.periode_nama || '-' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 text-[11px] block">Tanggal Masuk</span>
                            <span class="text-slate-800 font-mono">{{ mahasiswa.tanggal_masuk || '-' }}</span>
                        </div>

                        <div v-if="mahasiswa.tanggal_lulus">
                            <span class="text-slate-400 text-[11px] block">Tanggal Kelulusan</span>
                            <span class="text-slate-800 font-mono">{{ mahasiswa.tanggal_lulus }} ({{ mahasiswa.masa_studi_bulan }} bln)</span>
                        </div>
                    </div>
                </div>

                <!-- Personal Info Card -->
                <div class="md:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-5">
                    <div>
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-2 mb-3">
                            <i class="bi bi-person-lines-fill text-sky-600"></i>
                            <span>Biodata Pribadi</span>
                        </h3>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 text-[11px] block">Jenis Kelamin</span>
                                <strong class="text-slate-800">{{ mahasiswa.jenis_kelamin === 'L' ? 'Laki-laki' : (mahasiswa.jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</strong>
                            </div>

                            <div>
                                <span class="text-slate-400 text-[11px] block">NIK</span>
                                <strong class="text-slate-800 font-mono">{{ mahasiswa.nik || '-' }}</strong>
                            </div>

                            <div>
                                <span class="text-slate-400 text-[11px] block">Tempat, Tanggal Lahir</span>
                                <span class="text-slate-800">{{ mahasiswa.tempat_lahir || '-' }}, {{ mahasiswa.tanggal_lahir || '-' }}</span>
                            </div>

                            <div>
                                <span class="text-slate-400 text-[11px] block">Nomor HP / WhatsApp</span>
                                <span class="text-slate-800 font-mono">{{ mahasiswa.no_hp || '-' }}</span>
                            </div>

                            <div>
                                <span class="text-slate-400 text-[11px] block">Email Aktif</span>
                                <span class="text-slate-800">{{ mahasiswa.email || '-' }}</span>
                            </div>

                            <div class="col-span-2">
                                <span class="text-slate-400 text-[11px] block">Alamat Tinggal</span>
                                <span class="text-slate-800">{{ mahasiswa.alamat || '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100" v-if="mahasiswa.nama_ayah || mahasiswa.nama_ibu">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider flex items-center gap-2 mb-3">
                            <i class="bi bi-people text-sky-600"></i>
                            <span>Data Orang Tua / Wali</span>
                        </h3>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 text-[11px] block">Nama Ayah</span>
                                <strong class="text-slate-800">{{ mahasiswa.nama_ayah || '-' }}</strong>
                                <span class="text-[10px] text-slate-400 block">{{ mahasiswa.pekerjaan_ayah }}</span>
                            </div>

                            <div>
                                <span class="text-slate-400 text-[11px] block">Nama Ibu</span>
                                <strong class="text-slate-800">{{ mahasiswa.nama_ibu || '-' }}</strong>
                                <span class="text-[10px] text-slate-400 block">{{ mahasiswa.pekerjaan_ibu }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    mahasiswa: Object,
});

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
