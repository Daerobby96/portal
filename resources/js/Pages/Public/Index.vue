<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    periode: Object,
    capaianPerStandar: Object,
    publicKuesioners: {
        type: Array,
        default: () => [],
    },
    appSettings: {
        type: Object,
        default: () => ({}),
    },
});

const activeTabPpepp = ref('p1');

const ppeppSteps = [
    {
        id: 'p1',
        code: 'P1',
        title: 'Penetapan Standar',
        subtitle: 'Formulasi Kebijakan & Dokumen Mutu',
        desc: 'Penyusunan dan pengesahan seluruh standar mutu perguruan tinggi (SN-Dikti & Standar Pelampauan Institusi), manual mutu, dan standar operasional prosedur (SOP).',
        icon: 'bi-bookmark-check-fill',
        color: 'from-blue-600 to-indigo-600',
        bgLight: 'bg-blue-50 text-blue-700 border-blue-200',
        features: ['Standar SN-Dikti Terintegrasi', 'Manual Mutu & SOP Institusi', 'Indikator IKU & IKT Terukur'],
    },
    {
        id: 'p2',
        code: 'P2',
        title: 'Pelaksanaan Standar',
        subtitle: 'Implementasi & Monitoring Realisasi',
        desc: 'Eksekusi operasional tridharma dan tata kelola perguruan tinggi oleh unit kerja, program studi, serta pemantauan data secara real-time melalui integrasi ERP.',
        icon: 'bi-play-circle-fill',
        color: 'from-sky-500 to-blue-600',
        bgLight: 'bg-sky-50 text-sky-700 border-sky-200',
        features: ['Monitoring Realisasi IKU', 'Integrasi Data SDM & Akademik', 'Pelaporan Kinerja Periodik'],
    },
    {
        id: 'p3',
        code: 'P3',
        title: 'Evaluasi Pelaksanaan',
        subtitle: 'Audit Mutu Internal (AMI) & Survei',
        desc: 'Pemeriksaan kepatuhan standar melalui audit mutu berkala oleh auditor bersertifikat, kuesioner evaluasi dosen (EDOM), dan survei kepuasan stakeholders.',
        icon: 'bi-clipboard2-check-fill',
        color: 'from-amber-500 to-orange-600',
        bgLight: 'bg-amber-50 text-amber-800 border-amber-200',
        features: ['Audit AMI Berkelanjutan', 'Identifikasi Temuan KTS & OB', 'Survei Kepuasan Stakeholder'],
    },
    {
        id: 'p4',
        code: 'P4',
        title: 'Pengendalian Pelaksanaan',
        subtitle: 'Rapat Tinjauan Manajemen (RTM) & PTK',
        desc: 'Perumusan langkah korektif terhadap temuan audit melalui Permintaan Tindakan Koreksi (PTK) dan evaluasi strategis dalam Rapat Tinjauan Manajemen (RTM).',
        icon: 'bi-sliders2',
        color: 'from-purple-500 to-indigo-600',
        bgLight: 'bg-purple-50 text-purple-700 border-purple-200',
        features: ['Tindak Lanjut PTK Auditee', 'RTM Berita Acara & Notulensi', 'Pengawalan Rekomendasi'],
    },
    {
        id: 'p5',
        code: 'P5',
        title: 'Peningkatan Standar',
        subtitle: 'Kaizen Mutu & Benchmark Nasional',
        desc: 'Peningkatan standar mutu secara berkelanjutan (*continuous improvement*) berdasarkan evaluasi capaian siklus sebelumnya untuk menuju akreditasi unggul.',
        icon: 'bi-arrow-up-right-circle-fill',
        color: 'from-emerald-500 to-teal-600',
        bgLight: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        features: ['Revisi Pelampauan Standar', 'Benchmarking Nasional & Global', 'Kesiapan Akreditasi LAM/BAN-PT'],
    },
];

const publicServicesList = [
    {
        title: 'Repositori Dokumen Mutu Terbuka',
        desc: 'Akses dan unduh kebijakan mutu, standar SPMI, manual mutu, dan SOP resmi perguruan tinggi yang telah disahkan.',
        icon: 'bi-folder2-open',
        color: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        url: '/documents',
        ctaText: 'Buka Dokumen Mutu',
        isExternal: false,
    },
    {
        title: 'Transparansi Capaian 8 IKU',
        desc: 'Pemantauan indikator kinerja utama perguruan tinggi sesuai standar Kemdiktisaintek untuk akreditasi institusi.',
        icon: 'bi-bullseye',
        color: 'bg-sky-50 text-sky-700 border-sky-200',
        url: '#statistik',
        ctaText: 'Lihat Capaian Mutu',
        isExternal: false,
    },
    {
        title: 'Pelacakan Karir Alumni (Tracer Study)',
        desc: 'Data keterserapan lulusan di dunia kerja, wirausaha, serta keselarasan kurikulum vokasi dengan dunia industri.',
        icon: 'bi-mortarboard-fill',
        color: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        url: '/login',
        ctaText: 'Portal Tracer Study',
        isExternal: false,
    },
    {
        title: 'Survei & Umpan Balik Stakeholders',
        desc: 'Pengisian kuesioner evaluasi dosen (EDOM) dan survei kepuasan pemangku kepentingan secara transparan.',
        icon: 'bi-chat-square-dots-fill',
        color: 'bg-amber-50 text-amber-700 border-amber-200',
        url: '#survei',
        ctaText: 'Isi Survei Kepuasan',
        isExternal: false,
    },
    {
        title: 'Jaringan Kemitraan & Kerjasama',
        desc: 'Informasi kolaborasi strategis tridharma perguruan tinggi bersama mitra industri nasional dan internasional.',
        icon: 'bi-diagram-3-fill',
        color: 'bg-rose-50 text-rose-700 border-rose-200',
        url: '/login',
        ctaText: 'Informasi Kerjasama',
        isExternal: false,
    },
    {
        title: 'Gerbang Masuk Sivitas Akademika',
        desc: 'Akses terautentikasi dan aman bagi Pimpinan, Auditor Mutu, Dosen, dan Tenaga Kependidikan.',
        icon: 'bi-shield-lock-fill',
        color: 'bg-purple-50 text-purple-700 border-purple-200',
        url: '/login',
        ctaText: 'Login Portal ERP',
        isExternal: false,
    },
];
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-600 selection:text-white flex flex-col">
        <Head title="Portal Penjaminan Mutu & ERP Terpadu" />

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- PUBLIC NAVBAR                                                   -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
                <!-- Brand Logo -->
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-600/20 group-hover:scale-105 transition duration-200">
                        P
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-indigo-600 transition">
                                PINTAR
                            </span>
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200 tracking-wider">
                                SPMI PPEPP
                            </span>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 leading-tight block">
                            Pusat Penjaminan Mutu Perguruan Tinggi
                        </span>
                    </div>
                </Link>

                <!-- Navigation Links & Portal Button -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <nav class="hidden md:flex items-center gap-1 text-xs font-bold text-slate-600">
                        <a href="#ppepp" class="px-3 py-2 rounded-xl hover:text-indigo-600 hover:bg-slate-100 transition">Siklus PPEPP</a>
                        <a href="#statistik" class="px-3 py-2 rounded-xl hover:text-indigo-600 hover:bg-slate-100 transition">Capaian Mutu</a>
                        <a href="#layanan" class="px-3 py-2 rounded-xl hover:text-indigo-600 hover:bg-slate-100 transition">Layanan Publik</a>
                        <Link href="/documents" class="px-3 py-2 rounded-xl hover:text-indigo-600 hover:bg-slate-100 transition">Dokumen Mutu</Link>
                    </nav>

                    <div class="h-6 w-px bg-slate-200 hidden md:block"></div>

                    <Link
                        href="/login"
                        class="px-4.5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-md shadow-indigo-600/25 cursor-pointer group"
                    >
                        <i class="bi bi-box-arrow-in-right text-sm group-hover:translate-x-0.5 transition duration-150"></i>
                        <span>Masuk Portal</span>
                    </Link>
                </div>
            </div>
        </header>

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- HERO BANNER SECTION                                             -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <section class="relative bg-gradient-to-b from-slate-900 via-indigo-950 to-slate-900 text-white pt-16 pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <!-- Background Ambience Gradients -->
            <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 -right-40 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl pointer-events-none"></div>

            <div class="max-w-5xl mx-auto text-center relative z-10 space-y-6">
                <!-- Status Period Pill -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Periode Mutu Aktif: <strong>{{ periode?.nama || 'Tahun Akademik Berjalan' }}</strong></span>
                </div>

                <!-- Main Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight sm:leading-tight">
                    Sistem Penjaminan Mutu & <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-sky-300 to-indigo-200">
                        Ekosistem ERP Kampus Terpadu
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    Platform terintegrasi pengelolaan siklus <strong>PPEPP SPMI</strong>, Audit Mutu Internal (AMI), ketercapaian 8 IKU Kementerian, serta data operasional kepegawaian dan tridharma perguruan tinggi.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap items-center justify-center gap-3.5 pt-2">
                    <Link
                        href="/login"
                        class="px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-black transition flex items-center gap-2 shadow-xl shadow-indigo-600/30 group"
                    >
                        <span>Masuk Portal ERP</span>
                        <i class="bi bi-arrow-right group-hover:translate-x-1 transition duration-150"></i>
                    </Link>

                    <Link
                        href="/documents"
                        class="px-6 py-3.5 rounded-2xl bg-white/15 hover:bg-white/25 text-white text-xs sm:text-sm font-bold border border-white/20 transition flex items-center gap-2 backdrop-blur-sm"
                    >
                        <i class="bi bi-folder2-open"></i>
                        <span>Repositori Dokumen Mutu</span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- EXECUTIVE STATS SUMMARY CARDS                                   -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <section id="statistik" class="relative -mt-12 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Stat 1: Total Dokumen Mutu -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-lg shadow-slate-900/5 flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Dokumen Mutu</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.total_dokumen || 0 }}</p>
                        <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Standar & SOP Publik</p>
                    </div>
                </div>

                <!-- Stat 2: Capaian Standar IKU -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-lg shadow-slate-900/5 flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Capaian IKU</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.avg_capaian || 0 }}%</p>
                        <p class="text-[10px] text-sky-600 font-semibold mt-0.5">Rerata Ketercapaian</p>
                    </div>
                </div>

                <!-- Stat 3: Progres Audit AMI -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-lg shadow-slate-900/5 flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-clipboard2-check-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Audit AMI</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.audit_progress || 0 }}%</p>
                        <p class="text-[10px] text-amber-700 font-semibold mt-0.5">Selesai Diverifikasi</p>
                    </div>
                </div>

                <!-- Stat 4: Kepuasan Stakeholder -->
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-lg shadow-slate-900/5 flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl font-bold shrink-0">
                        <i class="bi bi-emoji-smile-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kepuasan Mahasiswa</p>
                        <p class="text-2xl font-black text-slate-900">{{ stats?.avg_edom || '3.80' }} <span class="text-xs text-slate-400 font-normal">/ 4.00</span></p>
                        <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Indeks EDOM Evaluasi Dosen</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- SIKLUS PPEPP INTERACTIVE SECTION                                -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <section id="ppepp" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Kerangka Penjaminan Mutu
                </span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Siklus Berkelanjutan PPEPP SPMI
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Sistem Penjaminan Mutu Internal dijalankan secara terus-menerus melalui lima pilar utama siklus PPEPP untuk menjamin mutu pendidikan tinggi yang unggul.
                </p>
            </div>

            <!-- PPEPP Nav Tabs -->
            <div class="flex items-center justify-center gap-2 sm:gap-3 flex-wrap mb-8">
                <button
                    v-for="step in ppeppSteps"
                    :key="step.id"
                    @click="activeTabPpepp = step.id"
                    type="button"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 cursor-pointer border"
                    :class="activeTabPpepp === step.id ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/20 scale-105' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                >
                    <span class="w-5 h-5 rounded-lg flex items-center justify-center text-[10px] font-black" :class="activeTabPpepp === step.id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">{{ step.code }}</span>
                    <span>{{ step.title }}</span>
                </button>
            </div>

            <!-- PPEPP Active Card Details -->
            <div v-for="step in ppeppSteps" :key="step.id" v-show="activeTabPpepp === step.id" class="animate-in fade-in zoom-in-95 duration-200">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-sm flex flex-col md:flex-row items-center gap-8">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-br text-white flex items-center justify-center text-4xl sm:text-5xl font-bold shadow-lg shrink-0" :class="step.color">
                        <i class="bi" :class="step.icon"></i>
                    </div>
                    <div class="flex-1 space-y-3 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border" :class="step.bgLight">{{ step.code }}</span>
                            <span class="text-xs font-bold text-slate-400">{{ step.subtitle }}</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">{{ step.title }}</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ step.desc }}</p>

                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 pt-2">
                            <span v-for="feat in step.features" :key="feat" class="px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                <i class="bi bi-check2-circle text-indigo-600"></i>
                                <span>{{ feat }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- PUBLIC SERVICES & TRANSPARENCY HUB                              -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <section id="layanan" class="py-20 bg-slate-100/70 border-y border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Transparansi & Pelayanan
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Pusat Layanan & Penjaminan Mutu
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Akses publik untuk penjaminan mutu, repositori dokumen resmi, dan partisipasi pemangku kepentingan.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div
                        v-for="svc in publicServicesList"
                        :key="svc.title"
                        class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs hover:shadow-md transition duration-200 flex flex-col justify-between space-y-4 group"
                    >
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl font-bold border transition group-hover:scale-105 duration-200" :class="svc.color">
                                <i class="bi" :class="svc.icon"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 group-hover:text-indigo-600 transition">{{ svc.title }}</h3>
                                <p class="text-xs text-slate-500 leading-relaxed mt-1">{{ svc.desc }}</p>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <a
                                v-if="svc.url.startsWith('#')"
                                :href="svc.url"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 group-hover:text-indigo-800 transition"
                            >
                                <span>{{ svc.ctaText }}</span>
                                <i class="bi bi-arrow-right text-[11px]"></i>
                            </a>
                            <Link
                                v-else
                                :href="svc.url"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 group-hover:text-indigo-800 transition"
                            >
                                <span>{{ svc.ctaText }}</span>
                                <i class="bi bi-arrow-right text-[11px]"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- PUBLIC QUESTIONNAIRES BANNER LINK                               -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <section id="survei" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 border border-white/10 relative overflow-hidden">
                <div class="space-y-3 text-center md:text-left relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-white/10 text-indigo-200 border border-white/15 backdrop-blur-md">
                        <i class="bi bi-chat-square-text"></i>
                        <span>Instrumen Evaluasi Mutu Terbuka</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black tracking-tight">Kuesioner & Survei Kepuasan Sivitas</h3>
                    <p class="text-xs sm:text-sm text-indigo-200 max-w-2xl leading-relaxed">
                        Sampaikan masukan, penilaian proses pembelajaran, dan evaluasi layanan institusi secara aman, objektif, dan anonim untuk mendukung continuous quality improvement perguruan tinggi.
                    </p>
                </div>

                <div class="shrink-0 relative z-10">
                    <Link
                        href="/survei"
                        class="px-7 py-4 rounded-2xl bg-white text-indigo-900 hover:bg-indigo-50 text-xs sm:text-sm font-black transition flex items-center gap-3 shadow-xl shadow-indigo-950/30 group"
                    >
                        <span>Buka Daftar Kuesioner</span>
                        <i class="bi bi-arrow-right text-indigo-600 group-hover:translate-x-1 transition duration-150"></i>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════ -->
        <!-- FOOTER                                                          -->
        <!-- ═══════════════════════════════════════════════════════════════ -->
        <footer class="bg-slate-900 text-white mt-auto pt-12 pb-8 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-lg">
                            P
                        </div>
                        <div>
                            <span class="font-black text-white text-base">PINTAR</span>
                            <span class="text-xs text-slate-400 block">Sistem Informasi Penjaminan Mutu & Manajemen Institusi</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-400">
                        <Link href="/" class="hover:text-white transition">Beranda</Link>
                        <Link href="/documents" class="hover:text-white transition">Dokumen Mutu</Link>
                        <Link href="/portal" class="hover:text-white transition">Portal ERP</Link>
                        <Link href="/login" class="hover:text-white transition">Login Pengguna</Link>
                    </div>
                </div>

                <div class="pt-6 text-center text-xs text-slate-500">
                    <p>&copy; {{ new Date().getFullYear() }} PINTAR. Hak Cipta Dilindungi Undang-Undang.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
