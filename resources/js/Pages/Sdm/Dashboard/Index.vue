<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    stats: Object,
    recentCutis: Array,
    recentLemburs: Array,
    recentSuratTugas: Array,
    presensiChart: Array,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard Kepegawaian & SDM" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-people-fill"></i>
                        <span>Sistem Informasi Sumber Daya Manusia (SDM)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Dashboard Kepegawaian & Dosen
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Monitoring terpadu profil dosen, tenaga kependidikan, rekapitulasi kehadiran, persetujuan cuti & lembur, serta surat tugas kedinasan.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <Link
                        href="/sdm/pegawai/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Tambah Pegawai</span>
                    </Link>
                    <Link
                        href="/sdm/cuti/create"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold transition flex items-center gap-2"
                    >
                        <i class="bi bi-calendar-plus"></i>
                        <span>Ajukan Cuti</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar (Grid 4) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-2xl shrink-0">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pegawai</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight">{{ stats?.total_pegawai || 0 }}</p>
                        <span class="text-[10px] text-slate-400 font-medium">Dosen: {{ stats?.dosen || 0 }} | Tendik: {{ stats?.tendik || 0 }}</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-2xl shrink-0">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kehadiran Hari Ini</p>
                        <p class="text-2xl font-black text-emerald-600 leading-tight">{{ stats?.hadir_hari_ini || 0 }}</p>
                        <span class="text-[10px] text-emerald-600/80 font-medium">Dari {{ stats?.presensi_hari_ini || 0 }} presensi masuk</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-2xl shrink-0">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Antrean Cuti / Izin</p>
                        <p class="text-2xl font-black text-amber-600 leading-tight">{{ stats?.cuti_pending || 0 }}</p>
                        <span class="text-[10px] text-slate-400 font-medium">Aktif: {{ stats?.cuti_aktif || 0 }} pegawai</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-2xl shrink-0">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Surat Tugas</p>
                        <p class="text-2xl font-black text-slate-900 leading-tight">{{ stats?.surat_tugas_aktif || 0 }}</p>
                        <span class="text-[10px] text-blue-600 font-medium">{{ stats?.surat_tugas_pending || 0 }} menunggu persetujuan</span>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation Menu Tiles -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <Link href="/sdm/pegawai" class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition text-center group">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Master Pegawai</span>
                    <span class="text-[10px] text-slate-400">Dosen & Tendik</span>
                </Link>

                <Link href="/sdm/presensi" class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition text-center group">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                        <i class="bi bi-fingerprint"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Presensi Harian</span>
                    <span class="text-[10px] text-slate-400">Kehadiran & Absen</span>
                </Link>

                <Link href="/sdm/cuti" class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition text-center group">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Manajemen Cuti</span>
                    <span class="text-[10px] text-slate-400">Izin & Approval</span>
                </Link>

                <Link href="/sdm/lembur" class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition text-center group">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                        <i class="bi bi-stopwatch"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Lembur Pegawai</span>
                    <span class="text-[10px] text-slate-400">SPK & Rekap Jam</span>
                </Link>

                <Link href="/sdm/penilaian-kinerja" class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition text-center group">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                        <i class="bi bi-award"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Penilaian Kinerja</span>
                    <span class="text-[10px] text-slate-400">SKP & Verifikasi</span>
                </Link>

                <Link href="/sdm/surat-tugas" class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-indigo-300 hover:shadow-md transition text-center group">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-2 group-hover:scale-110 transition">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Surat Tugas</span>
                    <span class="text-[10px] text-slate-400">Dinas & Pelatihan</span>
                </Link>
            </div>

            <!-- Activity Feeds Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Cuti Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-calendar-check text-amber-500"></i>
                            <span>Pengajuan Cuti Terbaru</span>
                        </h3>
                        <Link href="/sdm/cuti" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Semua</Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="c in recentCutis"
                            :key="c.id"
                            class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between"
                        >
                            <div class="min-w-0 pr-2">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ c.pegawai?.nama }}</p>
                                <p class="text-[10px] text-slate-500 capitalize">{{ c.jenis_cuti }} ({{ c.jumlah_hari }} hari)</p>
                            </div>
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase shrink-0"
                                :class="c.status === 'approved' ? 'bg-emerald-100 text-emerald-700' : (c.status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')"
                            >
                                {{ c.status }}
                            </span>
                        </div>

                        <div v-if="!recentCutis || recentCutis.length === 0" class="py-6 text-center text-slate-400 text-xs">
                            Belum ada riwayat pengajuan cuti.
                        </div>
                    </div>
                </div>

                <!-- Recent Lembur Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-stopwatch text-purple-500"></i>
                            <span>Lembur Kerja Terbaru</span>
                        </h3>
                        <Link href="/sdm/lembur" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Semua</Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="l in recentLemburs"
                            :key="l.id"
                            class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between"
                        >
                            <div class="min-w-0 pr-2">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ l.pegawai?.nama }}</p>
                                <p class="text-[10px] text-slate-500">{{ l.tanggal }} ({{ l.jumlah_jam }} jam)</p>
                            </div>
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase shrink-0"
                                :class="l.status === 'approved' ? 'bg-emerald-100 text-emerald-700' : (l.status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')"
                            >
                                {{ l.status }}
                            </span>
                        </div>

                        <div v-if="!recentLemburs || recentLemburs.length === 0" class="py-6 text-center text-slate-400 text-xs">
                            Belum ada pengajuan lembur terbaru.
                        </div>
                    </div>
                </div>

                <!-- Recent Surat Tugas Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-file-earmark-ruled text-blue-500"></i>
                            <span>Surat Tugas Dinas</span>
                        </h3>
                        <Link href="/sdm/surat-tugas" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Semua</Link>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="st in recentSuratTugas"
                            :key="st.id"
                            class="p-3 rounded-2xl bg-slate-50 border border-slate-100"
                        >
                            <p class="text-xs font-bold text-slate-900 truncate">{{ st.perihal }}</p>
                            <div class="flex items-center justify-between mt-1 text-[10px] text-slate-500">
                                <span class="font-mono">{{ st.nomor_surat }}</span>
                                <span
                                    class="px-2 py-0.5 rounded-full font-bold uppercase"
                                    :class="st.status === 'approved' || st.status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
                                >
                                    {{ st.status }}
                                </span>
                            </div>
                        </div>

                        <div v-if="!recentSuratTugas || recentSuratTugas.length === 0" class="py-6 text-center text-slate-400 text-xs">
                            Belum ada surat tugas kedinasan terbaru.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
