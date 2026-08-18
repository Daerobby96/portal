<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    periode: Object,
    allPeriodes: {
        type: Array,
        default: () => [],
    },
    stats: Object,
    temuanPerKategori: Object,
    auditTerbaru: Array,
    temuanDeadline: Array,
    listDokumenKadaluarsa: Array,
    standarProgress: Array,
    ppeppStatus: Object,
    ppeppDetails: Object,
    executiveSummary: Object,
});

const activeTab = ref('audit');
const periodeModalOpen = ref(false);
const selectedPeriodeId = ref(props.periode?.id || '');

const changePeriode = () => {
    if (!selectedPeriodeId.value) return;
    router.post('/set-periode', {
        periode_id: selectedPeriodeId.value,
    }, {
        onSuccess: () => {
            periodeModalOpen.value = false;
        },
    });
};

const ppeppSteps = computed(() => [
    {
        key: 'penetapan',
        code: 'P1',
        title: 'Penetapan',
        desc: 'Standar & Dokumen SPMI',
        icon: 'bi-file-earmark-text',
        percent: props.ppeppDetails?.penetapan || 0,
        active: props.ppeppStatus?.penetapan,
        color: 'from-blue-600 to-indigo-600',
    },
    {
        key: 'pelaksanaan',
        code: 'P2',
        title: 'Pelaksanaan',
        desc: 'Implementasi & Kinerja',
        icon: 'bi-play-circle',
        percent: props.ppeppDetails?.pelaksanaan || 0,
        active: props.ppeppStatus?.pelaksanaan,
        color: 'from-sky-500 to-blue-600',
    },
    {
        key: 'evaluasi',
        code: 'P3',
        title: 'Evaluasi',
        desc: 'Audit Mutu Internal (AMI)',
        icon: 'bi-clipboard-check',
        percent: props.ppeppDetails?.evaluasi || 0,
        active: props.ppeppStatus?.evaluasi,
        color: 'from-amber-500 to-orange-600',
    },
    {
        key: 'pengendalian',
        code: 'P4',
        title: 'Pengendalian',
        desc: 'RTM & Tindak Lanjut',
        icon: 'bi-sliders2',
        percent: props.ppeppDetails?.pengendalian || 0,
        active: props.ppeppStatus?.pengendalian,
        color: 'from-purple-500 to-indigo-600',
    },
    {
        key: 'peningkatan',
        code: 'P5',
        title: 'Peningkatan',
        desc: 'Revisi & Kaizen Standar',
        icon: 'bi-arrow-up-right-circle',
        percent: props.ppeppDetails?.peningkatan || 0,
        active: props.ppeppStatus?.peningkatan,
        color: 'from-emerald-500 to-teal-600',
    },
]);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard SPMI" />

        <div class="space-y-6">
            <!-- Header Banner with Periode Info & Switcher -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
                <!-- Ambient Top Glow -->
                <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-calendar3"></i>
                        <span>Periode Mutu: {{ periode?.nama || 'Belum Ada Periode Aktif' }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Pusat Kendali Mutu Internal
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Monitoring berkelanjutan siklus penjaminan mutu perguruan tinggi (PPEPP) dan status kepatuhan standar.
                    </p>
                </div>

                <div class="relative z-10 flex items-center gap-2.5 shrink-0 flex-wrap">
                    <a
                        href="/cetak/rekap-akreditasi"
                        target="_blank"
                        class="px-3.5 py-2.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition flex items-center gap-2 shadow-xs"
                        title="Cetak Rekapitulasi Data Kuantitatif Akreditasi"
                    >
                        <i class="bi bi-printer-fill"></i>
                        <span>Cetak LKPT</span>
                    </a>

                    <button
                        @click="periodeModalOpen = true"
                        class="px-4 py-2.5 rounded-2xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-2 cursor-pointer shadow-xs"
                    >
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Ganti Periode</span>
                    </button>
                </div>
            </div>

            <!-- PPEPP 5-Pillar Visual Interactive Loop Tracker -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">
                                Siklus Penjaminan Mutu (PPEPP)
                            </h2>
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border"
                                :class="ppeppDetails?.is_loop_closed
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    : 'bg-indigo-50 text-indigo-700 border-indigo-200'"
                            >
                                {{ ppeppDetails?.is_loop_closed ? 'Loop Tertutup (Lengkap)' : 'Siklus Berjalan' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Kemajuan terintegrasi siklus SPMI pada periode {{ periode?.nama || 'aktif' }}.
                        </p>
                    </div>

                    <div class="text-right sm:text-right">
                        <span class="text-xs font-medium text-slate-400">Total Capaian Siklus:</span>
                        <div class="text-2xl font-black text-indigo-600">
                            {{ ppeppDetails?.overall || 0 }}%
                        </div>
                    </div>
                </div>

                <!-- 5 Pillar Steps Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-6">
                    <div
                        v-for="step in ppeppSteps"
                        :key="step.key"
                        class="p-4 rounded-2xl border transition duration-300 flex flex-col justify-between"
                        :class="step.percent > 0
                            ? 'bg-slate-50/80 border-slate-200/90 shadow-xs'
                            : 'bg-slate-50/40 border-slate-100 opacity-70'"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-extrabold bg-indigo-100 text-indigo-700 font-mono">
                                    {{ step.code }}
                                </span>
                                <div
                                    class="w-7 h-7 rounded-xl flex items-center justify-center text-xs text-white bg-gradient-to-tr"
                                    :class="step.color"
                                >
                                    <i :class="['bi', step.icon]"></i>
                                </div>
                            </div>

                            <h3 class="text-xs font-bold text-slate-800">{{ step.title }}</h3>
                            <p class="text-[10px] text-slate-400 leading-tight mt-0.5">{{ step.desc }}</p>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-4 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 mb-1">
                                <span>Progress</span>
                                <span :class="step.percent >= 80 ? 'text-emerald-600' : 'text-indigo-600'">{{ step.percent }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                <div
                                    class="h-1.5 rounded-full bg-gradient-to-r transition-all duration-500"
                                    :class="step.color"
                                    :style="{ width: `${step.percent}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Executive Unified 10-Module Cross-Domain Snapshot -->
            <div v-if="executiveSummary" class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                            <i class="bi bi-grid-1x2-fill"></i>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900">Ringkasan Eksekutif Terpadu 10 Modul</h2>
                            <p class="text-[11px] text-slate-400">Snapshot data strategis pimpinan Politeknik secara terintegrasi</p>
                        </div>
                    </div>
                    <Link href="/portal" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition">
                        <span>Buka Portal Modul</span>
                        <i class="bi bi-arrow-right"></i>
                    </Link>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
                    <!-- 1. SDM -->
                    <Link href="/sdm" class="p-3.5 rounded-2xl bg-purple-50/50 hover:bg-purple-50 border border-purple-100 transition group block">
                        <div class="flex items-center justify-between text-purple-700 mb-1.5">
                            <i class="bi bi-person-vcard text-lg"></i>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider">SDM Dosen</span>
                        </div>
                        <div class="text-xl font-black text-slate-900 group-hover:text-purple-700 transition">{{ executiveSummary.sdm?.total_dosen || 0 }}</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ executiveSummary.sdm?.dosen_s3 || 0 }} Dosen S3 · {{ executiveSummary.sdm?.total_pegawai || 0 }} Pegawai</div>
                    </Link>

                    <!-- 2. Data Akademik -->
                    <Link href="/mahasiswa" class="p-3.5 rounded-2xl bg-sky-50/50 hover:bg-sky-50 border border-sky-100 transition group block">
                        <div class="flex items-center justify-between text-sky-700 mb-1.5">
                            <i class="bi bi-mortarboard text-lg"></i>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider">Mahasiswa</span>
                        </div>
                        <div class="text-xl font-black text-slate-900 group-hover:text-sky-700 transition">{{ executiveSummary.akademik?.mhs_aktif || 0 }}</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5">IPK Rerata {{ executiveSummary.akademik?.avg_ipk || '0.00' }} · {{ executiveSummary.akademik?.total_prestasi || 0 }} Prestasi</div>
                    </Link>

                    <!-- 3. Tridharma -->
                    <Link href="/penelitian" class="p-3.5 rounded-2xl bg-rose-50/50 hover:bg-rose-50 border border-rose-100 transition group block">
                        <div class="flex items-center justify-between text-rose-700 mb-1.5">
                            <i class="bi bi-journal-bookmark text-lg"></i>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider">Tridharma</span>
                        </div>
                        <div class="text-xl font-black text-slate-900 group-hover:text-rose-700 transition">{{ (executiveSummary.tridharma?.penelitian || 0) + (executiveSummary.tridharma?.pengabdian || 0) }}</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ executiveSummary.tridharma?.publikasi || 0 }} Jurnal · {{ executiveSummary.tridharma?.hki || 0 }} HKI/Paten</div>
                    </Link>

                    <!-- 4. Tracer Study -->
                    <Link href="/tracer-study" class="p-3.5 rounded-2xl bg-emerald-50/50 hover:bg-emerald-50 border border-emerald-100 transition group block">
                        <div class="flex items-center justify-between text-emerald-700 mb-1.5">
                            <i class="bi bi-person-check text-lg"></i>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider">Tracer Study</span>
                        </div>
                        <div class="text-xl font-black text-slate-900 group-hover:text-emerald-700 transition">{{ executiveSummary.tracer?.bekerja_persen || 0 }}%</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ executiveSummary.tracer?.total_alumni || 0 }} Responden · {{ executiveSummary.tracer?.avg_tunggu || 0 }} Bln Tunggu</div>
                    </Link>

                    <!-- 5. Sarpras & Rapat -->
                    <Link href="/aset" class="p-3.5 rounded-2xl bg-teal-50/50 hover:bg-teal-50 border border-teal-100 transition group block">
                        <div class="flex items-center justify-between text-teal-700 mb-1.5">
                            <i class="bi bi-building text-lg"></i>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider">Sarpras & Rapat</span>
                        </div>
                        <div class="text-xl font-black text-slate-900 group-hover:text-teal-700 transition">{{ executiveSummary.sarpras?.total_aset || 0 }}</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ executiveSummary.sarpras?.rapat_selesai || 0 }} Rapat Selesai / Arsip</div>
                    </Link>

                    <!-- 6. Persuratan & Kerjasama -->
                    <Link href="/manajemen-surat" class="p-3.5 rounded-2xl bg-amber-50/50 hover:bg-amber-50 border border-amber-100 transition group block">
                        <div class="flex items-center justify-between text-amber-700 mb-1.5">
                            <i class="bi bi-envelope-paper text-lg"></i>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider">Surat & Mitra</span>
                        </div>
                        <div class="text-xl font-black text-slate-900 group-hover:text-amber-700 transition">{{ executiveSummary.persuratan?.surat_masuk || 0 }}</div>
                        <div class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ executiveSummary.persuratan?.disposisi || 0 }} Disposisi · {{ executiveSummary.kerjasama?.mitra_aktif || 0 }} Mitra</div>
                    </Link>
                </div>
            </div>

            <!-- 4 Top KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Total Audit -->
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Audit AMI</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.total_audit || 0 }}</p>
                        <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">
                            {{ stats?.audit_selesai || 0 }} Audit Selesai
                        </p>
                    </div>
                </div>

                <!-- Card 2: Temuan Open -->
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Temuan Terbuka</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.temuan_open || 0 }}</p>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                            Dari {{ stats?.total_temuan || 0 }} total temuan
                        </p>
                    </div>
                </div>

                <!-- Card 3: Total Dokumen Mutu -->
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dokumen Mutu</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.total_dokumen || 0 }}</p>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                            Dokumen standar & SOP
                        </p>
                    </div>
                </div>

                <!-- Card 4: Total Monitoring -->
                <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Data Monitoring</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.total_monitoring || 0 }}</p>
                        <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">
                            Indikator terukur
                        </p>
                    </div>
                </div>
            </div>

            <!-- Standar Progress Meters & Quick Activity Tabs Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Standar Progress Meters (1 col) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs lg:col-span-1">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Kelengkapan Dokumen Standar</h3>
                            <p class="text-[11px] text-slate-400">Progres per standar mutu</p>
                        </div>
                        <a href="/standar" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua</a>
                    </div>

                    <div class="space-y-4 max-h-[380px] overflow-y-auto pr-1">
                        <div
                            v-for="std in standarProgress"
                            :key="std.kode"
                            class="p-3 rounded-xl bg-slate-50/70 border border-slate-100"
                        >
                            <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                                <span class="truncate max-w-[200px]" :title="std.nama">{{ std.kode }} - {{ std.nama }}</span>
                                <span class="text-indigo-600">{{ std.percent }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                <div
                                    class="h-1.5 rounded-full bg-indigo-600 transition-all duration-300"
                                    :style="{ width: `${std.percent}%` }"
                                ></div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                                <span>{{ std.approved }} disetujui</span>
                                <span>{{ std.total }} total</span>
                            </div>
                        </div>

                        <div v-if="!standarProgress || standarProgress.length === 0" class="text-center py-8 text-xs text-slate-400">
                            Belum ada standar mutu terdaftar.
                        </div>
                    </div>
                </div>

                <!-- Right: Quick Activity Tabs (2 cols) -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs lg:col-span-2">
                    <!-- Tab Buttons Header -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <button
                                @click="activeTab = 'audit'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer"
                                :class="activeTab === 'audit'
                                    ? 'bg-indigo-50 text-indigo-700 shadow-xs'
                                    : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                            >
                                Audit Terbaru ({{ auditTerbaru?.length || 0 }})
                            </button>
                            <button
                                @click="activeTab = 'temuan'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer"
                                :class="activeTab === 'temuan'
                                    ? 'bg-rose-50 text-rose-700 shadow-xs'
                                    : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                            >
                                Temuan Mendekati Batas ({{ temuanDeadline?.length || 0 }})
                            </button>
                            <button
                                @click="activeTab = 'dokumen'"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer"
                                :class="activeTab === 'dokumen'
                                    ? 'bg-amber-50 text-amber-700 shadow-xs'
                                    : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                            >
                                Kadaluarsa ({{ listDokumenKadaluarsa?.length || 0 }})
                            </button>
                        </div>
                    </div>

                    <!-- Tab 1: Audit Terbaru -->
                    <div v-if="activeTab === 'audit'" class="space-y-3">
                        <div
                            v-for="aud in auditTerbaru"
                            :key="aud.id"
                            class="p-3.5 rounded-2xl border border-slate-100 hover:border-slate-200 transition flex items-center justify-between gap-4"
                        >
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-indigo-600">{{ aud.kode_audit }}</span>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold capitalize"
                                        :class="aud.status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'"
                                    >
                                        {{ aud.status }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-slate-800">{{ aud.unit_yang_diaudit }}</p>
                                <p class="text-[11px] text-slate-400">Auditor: {{ aud.ketua_auditor?.name || '-' }}</p>
                            </div>
                            <a
                                :href="`/audit/${aud.id}`"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-600 hover:bg-indigo-50 transition shrink-0"
                            >
                                Rincian &rarr;
                            </a>
                        </div>
                        <div v-if="!auditTerbaru || auditTerbaru.length === 0" class="text-center py-10 text-xs text-slate-400">
                            Tidak ada audit terbaru.
                        </div>
                    </div>

                    <!-- Tab 2: Temuan Deadline -->
                    <div v-if="activeTab === 'temuan'" class="space-y-3">
                        <div
                            v-for="tem in temuanDeadline"
                            :key="tem.id"
                            class="p-3.5 rounded-2xl border border-rose-100 bg-rose-50/20 flex items-center justify-between gap-4"
                        >
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-rose-100 text-rose-700">
                                        {{ tem.kategori }}
                                    </span>
                                    <span class="text-[11px] text-rose-600 font-semibold">
                                        Batas: {{ tem.batas_tindak_lanjut || '-' }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-slate-800">{{ tem.deskripsi }}</p>
                            </div>
                            <a
                                :href="`/audit/${tem.audit_id}/temuan`"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-100 transition shrink-0"
                            >
                                Tindak Lanjut &rarr;
                            </a>
                        </div>
                        <div v-if="!temuanDeadline || temuanDeadline.length === 0" class="text-center py-10 text-xs text-slate-400">
                            Tidak ada temuan yang mendekati batas waktu.
                        </div>
                    </div>

                    <!-- Tab 3: Dokumen Kadaluarsa -->
                    <div v-if="activeTab === 'dokumen'" class="space-y-3">
                        <div
                            v-for="doc in listDokumenKadaluarsa"
                            :key="doc.id"
                            class="p-3.5 rounded-2xl border border-amber-100 bg-amber-50/20 flex items-center justify-between gap-4"
                        >
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ doc.judul }}</p>
                                <p class="text-[11px] text-amber-700 mt-0.5">
                                    Kadaluarsa: {{ doc.tanggal_kadaluarsa }} (Unit: {{ doc.unit_pemilik }})
                                </p>
                            </div>
                            <a
                                :href="`/dokumen/${doc.id}`"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold text-amber-700 hover:bg-amber-100 transition shrink-0"
                            >
                                Perbarui &rarr;
                            </a>
                        </div>
                        <div v-if="!listDokumenKadaluarsa || listDokumenKadaluarsa.length === 0" class="text-center py-10 text-xs text-slate-400">
                            Tidak ada dokumen yang mendekati kadaluarsa.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Switch Periode Modal -->
        <div
            v-if="periodeModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="periodeModalOpen = false"
        >
            <div class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h3 class="text-base font-bold text-slate-900">Ganti Periode Aktif</h3>
                    <button @click="periodeModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Pilih Periode Mutu
                        </label>
                        <select
                            v-model="selectedPeriodeId"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        >
                            <option
                                v-for="p in allPeriodes"
                                :key="p.id"
                                :value="p.id"
                            >
                                {{ p.nama }} ({{ p.tahun }} - {{ p.semester }}) {{ p.is_aktif ? '[Aktif Sekarang]' : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                        <button
                            @click="periodeModalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </button>
                        <button
                            @click="changePeriode"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition shadow-xs"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
