<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash || {});
const activePeriode = computed(() => page.props.active_periode || null);
const notifications = computed(() => page.props.notifications || { unread_count: 0, list: [] });

const sidebarOpen = ref(true);
const mobileSidebarOpen = ref(false);
const userDropdownOpen = ref(false);
const notificationDropdownOpen = ref(false);

// Global Command Palette (Ctrl + K)
const commandPaletteOpen = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const selectedResultIndex = ref(0);
let searchTimeout = null;

const openCommandPalette = () => {
    commandPaletteOpen.value = true;
    searchQuery.value = '';
    searchResults.value = [];
    selectedResultIndex.value = 0;
};

const closeCommandPalette = () => {
    commandPaletteOpen.value = false;
};

const handleSearchInput = () => {
    clearTimeout(searchTimeout);
    if (!searchQuery.value || searchQuery.value.trim().length < 2) {
        searchResults.value = [];
        isSearching.value = false;
        return;
    }

    isSearching.value = true;
    searchTimeout = setTimeout(async () => {
        try {
            const res = await fetch(`/api/global-search?q=${encodeURIComponent(searchQuery.value.trim())}`);
            if (res.ok) {
                const data = await res.json();
                searchResults.value = data.results || [];
                selectedResultIndex.value = 0;
            }
        } catch (e) {
            console.error('Search error:', e);
        } finally {
            isSearching.value = false;
        }
    }, 150);
};

const navigateToResult = (url) => {
    closeCommandPalette();
    router.visit(url);
};

const handleKeyDown = (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        commandPaletteOpen.value = !commandPaletteOpen.value;
        if (commandPaletteOpen.value) {
            searchQuery.value = '';
            searchResults.value = [];
        }
    }
    if (e.key === 'Escape' && commandPaletteOpen.value) {
        closeCommandPalette();
    }
    if (commandPaletteOpen.value && searchResults.value.length > 0) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedResultIndex.value = (selectedResultIndex.value + 1) % searchResults.value.length;
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedResultIndex.value = (selectedResultIndex.value - 1 + searchResults.value.length) % searchResults.value.length;
        } else if (e.key === 'Enter') {
            e.preventDefault();
            const target = searchResults.value[selectedResultIndex.value];
            if (target) {
                navigateToResult(target.url);
            }
        }
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
});

const markAsRead = (id) => {
    router.post(`/notifications/${id}/read`, {}, { preserveScroll: true });
};

const markAllAsRead = () => {
    router.post('/notifications/mark-all-read', {}, { preserveScroll: true });
};

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const toggleMobileSidebar = () => {
    mobileSidebarOpen.value = !mobileSidebarOpen.value;
};

const logout = () => {
    router.post('/logout');
};

const currentUrl = computed(() => page.url);

const isRouteActive = (patterns) => {
    if (!Array.isArray(patterns)) patterns = [patterns];
    return patterns.some(pattern => {
        return currentUrl.value === pattern ||
               currentUrl.value.startsWith(pattern + '/') ||
               currentUrl.value.startsWith(pattern + '?');
    });
};

// Detect active module dynamically based on URL route
const activeModule = computed(() => {
    const url = currentUrl.value;
    if (url.startsWith('/sdm')) {
        return 'sdm';
    }
    if (url.startsWith('/manajemen-surat') || url.startsWith('/surat-masuk') || url.startsWith('/surat-keluar') || url.startsWith('/disposisi') || url.startsWith('/surat-keputusan') || url.startsWith('/unit-pengelola')) {
        return 'persuratan';
    }
    if (url.startsWith('/rapat')) {
        return 'rapat';
    }
    if (url.startsWith('/kerjasama')) {
        return 'kerjasama';
    }
    if (url.startsWith('/aset') || url.startsWith('/kategori-aset') || url.startsWith('/pemeliharaan') || url.startsWith('/peminjaman') || url.startsWith('/booking-ruangan')) {
        return 'aset';
    }
    if (url.startsWith('/penelitian') || url.startsWith('/pengabdian') || url.startsWith('/publikasi') || url.startsWith('/hki') || url.startsWith('/tridharma')) {
        return 'tridharma';
    }
    if (url.startsWith('/tracer-study')) {
        return 'tracer_study';
    }
    if (url.startsWith('/mahasiswa') || url.startsWith('/prestasi') || url.startsWith('/alumni') || url.startsWith('/data-akademik')) {
        return 'data_akademik';
    }
    if (url.startsWith('/periode') || url.startsWith('/program-studi') || url.startsWith('/unit-kerja') || url.startsWith('/jabatan') || url.startsWith('/ruangan')) {
        return 'datamaster';
    }
    if (url.startsWith('/settings') || url.startsWith('/users') || url.startsWith('/roles') || url.startsWith('/activity-log')) {
        return 'systemadmin';
    }
    return 'spmi';
});

// Accordion Groups for SPMI 5 Pillars of PPEPP
const openGroups = ref({
    p1: isRouteActive(['/dokumen', '/standar', '/kategori-dokumen', '/indikator-kinerja', '/iku-resmi']),
    p2: isRouteActive(['/monitoring', '/integrasi']),
    p3: isRouteActive(['/evaluasi', '/audit', '/survei', '/kuesioner', '/kinerja-dosen']),
    p4: isRouteActive(['/tindak-lanjut', '/rtm']),
    p5: isRouteActive(['/laporan']),
});

const toggleGroup = (groupKey) => {
    openGroups.value[groupKey] = !openGroups.value[groupKey];
};
</script>

<template>
    <div class="min-h-screen bg-slate-50/50 text-slate-800 font-sans flex antialiased selection:bg-indigo-500 selection:text-white">
        
        <!-- Desktop Sidebar (Full Height from Top to Bottom) -->
        <aside
            v-if="sidebarOpen"
            class="hidden md:flex flex-col w-72 h-screen bg-white border-r border-slate-200/80 shrink-0 p-4 sticky top-0 transition-all duration-300 select-none overflow-y-auto z-20"
        >
            <!-- Dynamic Brand Logo Header based on Active Module -->
            <div class="pb-4 mb-3 border-b border-slate-100">
                <!-- 1. SPMI Header -->
                <div v-if="activeModule === 'spmi'" class="flex items-center gap-3">
                    <Link href="/dashboard" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-600/20 group-hover:scale-105 transition duration-200 shrink-0">
                            P
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-indigo-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200/60 tracking-wider">
                                    SPMI PPEPP
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Penjaminan Mutu Internal
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 2. SDM Header -->
                <div v-else-if="activeModule === 'sdm'" class="flex items-center gap-3">
                    <Link href="/sdm" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-600 via-indigo-700 to-slate-900 flex items-center justify-center text-white font-black text-lg shadow-md shadow-purple-600/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-people-fill text-lg"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-purple-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-purple-50 text-purple-700 border border-purple-200/60 tracking-wider">
                                    MODUL SDM
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Kepegawaian & Dosen
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 3. Data Master Header -->
                <div v-else-if="activeModule === 'datamaster'" class="flex items-center gap-3">
                    <Link href="/periode" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sky-600 via-indigo-700 to-slate-900 flex items-center justify-center text-white font-black text-lg shadow-md shadow-sky-600/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-database-fill-gear text-lg"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-sky-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-sky-50 text-sky-700 border border-sky-200/60 tracking-wider">
                                    DATA MASTER
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Master Data Institusi
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 4. System Admin Header -->
                <div v-else-if="activeModule === 'systemadmin'" class="flex items-center gap-3">
                    <Link href="/settings" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-slate-700 via-slate-800 to-indigo-950 flex items-center justify-center text-white font-black text-lg shadow-md shadow-slate-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-gear-fill text-lg text-slate-200"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-indigo-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-slate-100 text-slate-800 border border-slate-300/60 tracking-wider">
                                    SYSTEM ADMIN
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Konfigurasi & Akses
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 5. Persuratan Header -->
                <div v-else-if="activeModule === 'persuratan'" class="flex items-center gap-3">
                    <Link href="/manajemen-surat" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-500 via-orange-600 to-amber-700 flex items-center justify-center text-white font-black text-lg shadow-md shadow-amber-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-envelope-paper-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-amber-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-amber-50 text-amber-800 border border-amber-200/60 tracking-wider">
                                    PERSURATAN
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Tata Naskah & Disposisi
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 6. Manajemen Rapat Header -->
                <div v-else-if="activeModule === 'rapat'" class="flex items-center gap-3">
                    <Link href="/rapat" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-teal-500 via-emerald-600 to-teal-800 flex items-center justify-center text-white font-black text-lg shadow-md shadow-teal-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-calendar2-check-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-teal-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-teal-50 text-teal-800 border border-teal-200/60 tracking-wider">
                                    RAPAT & RTM
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Jadwal, Notulensi & TL
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 7. Kerjasama & Mitra Header -->
                <div v-else-if="activeModule === 'kerjasama'" class="flex items-center gap-3">
                    <Link href="/kerjasama" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-pink-500 via-rose-600 to-pink-800 flex items-center justify-center text-white font-black text-lg shadow-md shadow-pink-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-diagram-3-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-pink-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-pink-50 text-pink-800 border border-pink-200/60 tracking-wider">
                                    KERJASAMA
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                MoU, MoA & Evaluasi
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 8. Manajemen Aset & Sarpras Header -->
                <div v-else-if="activeModule === 'aset'" class="flex items-center gap-3">
                    <Link href="/aset" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-800 flex items-center justify-center text-white font-black text-lg shadow-md shadow-emerald-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-box-seam-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-emerald-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200/60 tracking-wider">
                                    ASET & SARPRAS
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Inventaris, Servis & Pinjam
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 9. Tridharma Dosen Header -->
                <div v-else-if="activeModule === 'tridharma'" class="flex items-center gap-3">
                    <Link href="/penelitian" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-rose-500 via-pink-600 to-rose-900 flex items-center justify-center text-white font-black text-lg shadow-md shadow-rose-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-journal-bookmark-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-rose-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-rose-50 text-rose-800 border border-rose-200/60 tracking-wider">
                                    TRIDHARMA
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Riset, PkM, Jurnal & HKI
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 10. Tracer Study & Alumni Header -->
                <div v-else-if="activeModule === 'tracer_study'" class="flex items-center gap-3">
                    <Link href="/tracer-study" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-600 to-emerald-800 flex items-center justify-center text-white font-black text-lg shadow-md shadow-emerald-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-person-check-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-emerald-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200/60 tracking-wider">
                                    TRACER STUDY
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Pelacakan & Karir Alumni
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- 11. Data Akademik Header -->
                <div v-else-if="activeModule === 'data_akademik'" class="flex items-center gap-3">
                    <Link href="/mahasiswa" class="flex items-center gap-3 group min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-800 flex items-center justify-center text-white font-black text-lg shadow-md shadow-blue-900/20 group-hover:scale-105 transition duration-200 shrink-0">
                            <i class="bi bi-mortarboard-fill text-lg text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-black text-slate-900 text-base tracking-tight leading-none group-hover:text-sky-600 transition">
                                    PINTAR
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-sky-50 text-sky-800 border border-sky-200/60 tracking-wider">
                                    AKADEMIK
                                </span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 mt-1 leading-tight truncate">
                                Mahasiswa, Prestasi & Alumni
                            </span>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Contextual Navigation Menus -->

            <!-- 1. MODUL SDM SIDEBAR MENU -->
            <nav v-if="activeModule === 'sdm'" class="space-y-1.5 flex-1 text-xs">
                <a
                    href="/sdm"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="currentUrl === '/sdm' ? 'bg-purple-50 text-purple-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-speedometer2 text-base" :class="currentUrl === '/sdm' ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Dashboard SDM</span>
                </a>

                <a
                    href="/sdm/pegawai"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/sdm/pegawai') ? 'bg-purple-50 text-purple-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-person-vcard text-base" :class="isRouteActive('/sdm/pegawai') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Master Pegawai & Dosen</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Operasional Kehadiran</div>
                
                <a
                    href="/sdm/presensi"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/sdm/presensi') && !isRouteActive('/sdm/presensi/rekap') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-fingerprint text-base"></i>
                    <span>Presensi Harian</span>
                </a>

                <a
                    href="/sdm/presensi/rekap"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/sdm/presensi/rekap') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-file-earmark-bar-graph text-base"></i>
                    <span>Rekapitulasi Kehadiran</span>
                </a>

                <a
                    href="/sdm/cuti"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/sdm/cuti') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-calendar-event text-base"></i>
                    <span>Manajemen Cuti & Izin</span>
                </a>

                <a
                    href="/sdm/lembur"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/sdm/lembur') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-stopwatch text-base"></i>
                    <span>Lembur Pegawai</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kinerja & Penugasan</div>

                <a
                    href="/sdm/penilaian-kinerja"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/sdm/penilaian-kinerja') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-award text-base"></i>
                    <span>Penilaian Kinerja (SKP)</span>
                </a>

                <a
                    href="/sdm/surat-tugas"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/sdm/surat-tugas') ? 'bg-purple-50 text-purple-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-journal-bookmark text-base"></i>
                    <span>Surat Tugas Kedinasan</span>
                </a>
            </nav>

            <!-- 2. DATA MASTER SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'datamaster'" class="space-y-1.5 flex-1 text-xs">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Struktur Akademik</div>
                
                <a
                    href="/periode"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/periode') ? 'bg-sky-50 text-sky-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-calendar3 text-base" :class="isRouteActive('/periode') ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Periode Akademik & Semester</span>
                </a>

                <a
                    href="/program-studi"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/program-studi') ? 'bg-sky-50 text-sky-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-mortarboard text-base" :class="isRouteActive('/program-studi') ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Program Studi & Jurusan</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kelembagaan & SDM</div>

                <a
                    href="/unit-kerja"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/unit-kerja') ? 'bg-sky-50 text-sky-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-building text-base" :class="isRouteActive('/unit-kerja') ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Unit Kerja & Lembaga</span>
                </a>

                <a
                    href="/jabatan"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/jabatan') ? 'bg-sky-50 text-sky-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-award text-base" :class="isRouteActive('/jabatan') ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Jabatan & Fungsional</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Fasilitas Kampus</div>

                <a
                    href="/ruangan"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/ruangan') ? 'bg-sky-50 text-sky-700 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-door-open text-base" :class="isRouteActive('/ruangan') ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Ruangan & Gedung</span>
                </a>
            </nav>

            <!-- 3. SYSTEM ADMIN SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'systemadmin'" class="space-y-1.5 flex-1 text-xs">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Konfigurasi Institusi</div>
                
                <a
                    href="/settings"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/settings') ? 'bg-slate-900 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-sliders text-base" :class="isRouteActive('/settings') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Pengaturan Sistem & Kop</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Manajemen Pengguna</div>

                <a
                    href="/users"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/users') ? 'bg-slate-900 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-people-fill text-base" :class="isRouteActive('/users') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Pengguna & Akun</span>
                </a>

                <a
                    href="/roles"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/roles') ? 'bg-slate-900 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-shield-lock-fill text-base" :class="isRouteActive('/roles') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Peran & Hak Akses (Roles)</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Keamanan & Audit</div>

                <a
                    href="/activity-log"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/activity-log') ? 'bg-slate-900 text-white font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-clock-history text-base" :class="isRouteActive('/activity-log') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Log Aktivitas Sistem</span>
                </a>
            </nav>

            <!-- 4. PERSURATAN SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'persuratan'" class="space-y-1.5 flex-1 text-xs">
                <a
                    href="/manajemen-surat"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="currentUrl === '/manajemen-surat' ? 'bg-amber-50 text-amber-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-speedometer2 text-base" :class="currentUrl === '/manajemen-surat' ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Dashboard Persuratan</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Naskah Masuk & Disposisi</div>

                <a
                    href="/surat-masuk"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/surat-masuk') ? 'bg-amber-50 text-amber-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-inbox-fill text-base" :class="isRouteActive('/surat-masuk') ? 'text-amber-600' : 'text-slate-400'"></i>
                    <span>Surat Masuk</span>
                </a>

                <a
                    href="/disposisi/my-disposisi"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/disposisi') ? 'bg-amber-50 text-amber-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-send-check-fill text-base" :class="isRouteActive('/disposisi') ? 'text-amber-600' : 'text-slate-400'"></i>
                    <span>Disposisi Saya</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Naskah Keluar & SK</div>

                <a
                    href="/surat-keluar"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/surat-keluar') ? 'bg-amber-50 text-amber-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-send-fill text-base" :class="isRouteActive('/surat-keluar') ? 'text-amber-600' : 'text-slate-400'"></i>
                    <span>Surat Keluar Dinas</span>
                </a>

                <a
                    href="/surat-keputusan"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/surat-keputusan') ? 'bg-amber-50 text-amber-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-file-earmark-text-fill text-base" :class="isRouteActive('/surat-keputusan') ? 'text-amber-600' : 'text-slate-400'"></i>
                    <span>Generator Surat Keputusan</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Pengaturan Tata Naskah</div>

                <a
                    href="/unit-pengelola"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/unit-pengelola') ? 'bg-amber-50 text-amber-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-tags-fill text-base" :class="isRouteActive('/unit-pengelola') ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Unit & Klasifikasi Surat</span>
                </a>
            </nav>

            <!-- 5. MANAJEMEN RAPAT SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'rapat'" class="space-y-1.5 flex-1 text-xs">
                <Link
                    href="/rapat"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="currentUrl === '/rapat' ? 'bg-teal-50 text-teal-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-calendar2-check-fill text-base" :class="currentUrl === '/rapat' ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Daftar Semua Rapat</span>
                </Link>

                <Link
                    href="/rapat/create"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/rapat/create') ? 'bg-teal-50 text-teal-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-plus-circle-fill text-base" :class="isRouteActive('/rapat/create') ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Buat Rapat Baru</span>
                </Link>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Status Rapat</div>

                <Link
                    href="/rapat?status=terjadwal"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status=terjadwal') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-calendar-event text-base text-blue-500"></i>
                    <span>Rapat Terjadwal</span>
                </Link>

                <Link
                    href="/rapat?status=berlangsung"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status=berlangsung') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-broadcast text-base text-amber-500 animate-pulse"></i>
                    <span>Sedang Berlangsung</span>
                </Link>

                <Link
                    href="/rapat?status=selesai"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status=selesai') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-check2-all text-base text-emerald-500"></i>
                    <span>Arsip & Selesai</span>
                </Link>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kategori Rapat</div>

                <Link
                    href="/rapat?jenis=RTM"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('jenis=RTM') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-award-fill text-base text-indigo-500"></i>
                    <span>RTM (Tinjauan Manajemen)</span>
                </Link>
            </nav>

            <!-- 6. KERJASAMA & MITRA SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'kerjasama'" class="space-y-1.5 flex-1 text-xs">
                <a
                    href="/kerjasama"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="currentUrl === '/kerjasama' || currentUrl.startsWith('/kerjasama?') ? 'bg-pink-50 text-pink-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-diagram-3-fill text-base" :class="currentUrl === '/kerjasama' ? 'text-pink-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Daftar Kerjasama</span>
                </a>

                <a
                    href="/kerjasama/create"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/kerjasama/create') ? 'bg-pink-50 text-pink-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-plus-circle-fill text-base" :class="isRouteActive('/kerjasama/create') ? 'text-pink-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Tambah Kerjasama Baru</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Tingkat Wilayah</div>

                <a
                    href="/kerjasama?tingkat=Internasional"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('tingkat=Internasional') ? 'bg-pink-50 text-pink-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-globe-americas text-base text-amber-500"></i>
                    <span>Internasional (Global)</span>
                </a>

                <a
                    href="/kerjasama?tingkat=Nasional"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('tingkat=Nasional') ? 'bg-pink-50 text-pink-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-flag-fill text-base text-blue-500"></i>
                    <span>Nasional</span>
                </a>

                <a
                    href="/kerjasama?tingkat=Lokal"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('tingkat=Lokal') ? 'bg-pink-50 text-pink-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-geo-alt-fill text-base text-slate-400"></i>
                    <span>Lokal / Wilayah</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Status Dokumen</div>

                <a
                    href="/kerjasama?status=Aktif"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status=Aktif') ? 'bg-pink-50 text-pink-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-check-circle-fill text-base text-emerald-500"></i>
                    <span>Status Aktif</span>
                </a>

                <a
                    href="/kerjasama?status=Draft"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status=Draft') ? 'bg-pink-50 text-pink-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-file-earmark-text-fill text-base text-slate-400"></i>
                    <span>Draft Dokumen</span>
                </a>
            </nav>

            <!-- 7. MANAJEMEN ASET & SARPRAS SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'aset'" class="space-y-1.5 flex-1 text-xs">
                <!-- Inventaris Aset -->
                <a
                    href="/aset"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/aset') && !isRouteActive('/aset/create') ? 'bg-emerald-50 text-emerald-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-box-seam-fill text-base" :class="isRouteActive('/aset') && !isRouteActive('/aset/create') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Daftar Inventaris Aset</span>
                </a>

                <a
                    href="/aset/create"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/aset/create') ? 'bg-emerald-50 text-emerald-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-plus-circle-fill text-base" :class="isRouteActive('/aset/create') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Tambah Aset Baru</span>
                </a>

                <a
                    href="/kategori-aset"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/kategori-aset') ? 'bg-emerald-50 text-emerald-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-tags-fill text-base" :class="isRouteActive('/kategori-aset') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Kategori Aset</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Pemeliharaan & Layanan</div>

                <a
                    href="/pemeliharaan"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/pemeliharaan') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-tools text-base text-amber-500"></i>
                    <span>Pemeliharaan / Servis</span>
                </a>

                <a
                    href="/peminjaman"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/peminjaman') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-arrow-left-right text-base text-blue-500"></i>
                    <span>Peminjaman Aset</span>
                </a>

                <a
                    href="/booking-ruangan"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="isRouteActive('/booking-ruangan') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-door-open-fill text-base text-purple-500"></i>
                    <span>Booking Ruangan</span>
                </a>
            </nav>

            <!-- 8. MODUL TRIDHARMA DOSEN SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'tridharma'" class="space-y-1.5 flex-1 text-xs">
                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Pilar Tridharma</div>

                <Link
                    href="/penelitian"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/penelitian') ? 'bg-rose-50 text-rose-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-journal-bookmark-fill text-base" :class="isRouteActive('/penelitian') ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Penelitian & Riset Dosen</span>
                </Link>

                <Link
                    href="/pengabdian"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/pengabdian') ? 'bg-rose-50 text-rose-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-people-fill text-base" :class="isRouteActive('/pengabdian') ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Pengabdian Masyarakat (PkM)</span>
                </Link>

                <Link
                    href="/publikasi"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/publikasi') ? 'bg-rose-50 text-rose-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-journal-text text-base" :class="isRouteActive('/publikasi') ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Publikasi Ilmiah & Jurnal</span>
                </Link>

                <Link
                    href="/hki"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/hki') ? 'bg-rose-50 text-rose-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-award-fill text-base" :class="isRouteActive('/hki') ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>HKI & Paten Dosen</span>
                </Link>
            </nav>

            <!-- 9. MODUL TRACER STUDY SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'tracer_study'" class="space-y-1.5 flex-1 text-xs">
                <Link
                    href="/tracer-study"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="currentUrl === '/tracer-study' ? 'bg-emerald-50 text-emerald-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-people-fill text-base" :class="currentUrl === '/tracer-study' ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Daftar Alumni & Responden</span>
                </Link>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <div class="px-3.5 py-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Status Karir</div>

                <Link
                    href="/tracer-study?status_kerja=Bekerja"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status_kerja=Bekerja') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-briefcase-fill text-base text-emerald-600"></i>
                    <span>Alumni Bekerja</span>
                </Link>

                <Link
                    href="/tracer-study?status_kerja=Wirausaha"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status_kerja=Wirausaha') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-shop text-base text-purple-600"></i>
                    <span>Wiraswasta / Wirausaha</span>
                </Link>

                <Link
                    href="/tracer-study?status_kerja=Melanjutkan"
                    class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition"
                    :class="currentUrl.includes('status_kerja=Melanjutkan') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-mortarboard-fill text-base text-blue-600"></i>
                    <span>Melanjutkan Pendidikan</span>
                </Link>
            </nav>

            <!-- 10. MODUL DATA AKADEMIK SIDEBAR MENU -->
            <nav v-else-if="activeModule === 'data_akademik'" class="space-y-1.5 flex-1 text-xs">
                <Link
                    href="/mahasiswa"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive(['/mahasiswa']) ? 'bg-sky-50 text-sky-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-people-fill text-base" :class="isRouteActive(['/mahasiswa']) ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Data Mahasiswa</span>
                </Link>

                <Link
                    href="/prestasi"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive(['/prestasi']) ? 'bg-sky-50 text-sky-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-trophy-fill text-base" :class="isRouteActive(['/prestasi']) ? 'text-amber-500' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Prestasi Mahasiswa</span>
                </Link>

                <Link
                    href="/alumni"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive(['/alumni']) ? 'bg-sky-50 text-sky-800 font-bold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-mortarboard-fill text-base" :class="isRouteActive(['/alumni']) ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Rekap Data Alumni</span>
                </Link>
            </nav>

            <!-- 11. MODUL SPMI (PPEPP) SIDEBAR MENU -->
            <nav v-else class="space-y-1.5 flex-1 text-xs">
                <!-- Dashboard -->
                <a
                    href="/dashboard"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive('/dashboard')
                        ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-speedometer2 text-base" :class="isRouteActive('/dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Dashboard Eksekutif</span>
                </a>

                <!-- Siklus Master PPEPP -->
                <a
                    href="/siklus-spmi"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold transition group"
                    :class="isRouteActive(['/siklus-spmi', '/ppepp'])
                        ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <i class="bi bi-arrow-repeat text-base" :class="isRouteActive(['/siklus-spmi', '/ppepp']) ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                    <span>Siklus Mutu (Master PPEPP)</span>
                </a>

                <div class="pt-2 pb-1">
                    <div class="h-px bg-slate-100"></div>
                </div>

                <!-- PILAR 1: PENETAPAN STANDAR -->
                <div>
                    <button
                        @click="toggleGroup('p1')"
                        class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-lg bg-indigo-100 text-indigo-700 font-bold text-[10px] flex items-center justify-center">P1</span>
                            <span class="tracking-tight uppercase text-[11px]">Penetapan Standar</span>
                        </div>
                        <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.p1 }"></i>
                    </button>
                    <div v-show="openGroups.p1" class="pl-6 pr-1 py-1 space-y-1 border-l-2 border-indigo-100 ml-4.5 my-1">
                        <a href="/dokumen" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/dokumen') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-folder2 text-xs"></i><span>Dokumen Mutu</span></a>
                        <a href="/standar" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/standar') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-bookmark-check text-xs"></i><span>Standar Mutu SN-Dikti</span></a>
                        <a href="/kategori-dokumen" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/kategori-dokumen') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-tags text-xs"></i><span>Kategori Dokumen</span></a>
                        <a href="/indikator-kinerja" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/indikator-kinerja') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-bullseye text-xs"></i><span>Indikator Kinerja (IKU/IKT)</span></a>
                        <a href="/iku-resmi" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/iku-resmi') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-award text-xs"></i><span>8 IKU Kemdiktisaintek</span></a>
                    </div>
                </div>

                <!-- PILAR 2: PELAKSANAAN STANDAR -->
                <div>
                    <button
                        @click="toggleGroup('p2')"
                        class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 font-bold text-[10px] flex items-center justify-center">P2</span>
                            <span class="tracking-tight uppercase text-[11px]">Pelaksanaan Standar</span>
                        </div>
                        <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.p2 }"></i>
                    </button>
                    <div v-show="openGroups.p2" class="pl-6 pr-1 py-1 space-y-1 border-l-2 border-blue-100 ml-4.5 my-1">
                        <a href="/monitoring" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/monitoring') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-bar-chart-line text-xs"></i><span>Monitoring Realisasi IKU</span></a>
                        <a href="/integrasi" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/integrasi') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-diagram-3 text-xs"></i><span>Integrasi Data ERP</span></a>
                    </div>
                </div>

                <!-- PILAR 3: EVALUASI PELAKSANAAN -->
                <div>
                    <button
                        @click="toggleGroup('p3')"
                        class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-lg bg-amber-100 text-amber-800 font-bold text-[10px] flex items-center justify-center">P3</span>
                            <span class="tracking-tight uppercase text-[11px]">Evaluasi Pelaksanaan</span>
                        </div>
                        <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.p3 }"></i>
                    </button>
                    <div v-show="openGroups.p3" class="pl-6 pr-1 py-1 space-y-1 border-l-2 border-amber-100 ml-4.5 my-1">
                        <a href="/evaluasi" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/evaluasi') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-graph-up-arrow text-xs"></i><span>Evaluasi Capaian Standar</span></a>
                        <a href="/audit" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/audit') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-clipboard2-check text-xs"></i><span>Audit Mutu Internal (AMI)</span></a>
                        <a href="/kuesioner" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/kuesioner') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-ui-checks text-xs"></i><span>Manajemen Kuesioner</span></a>
                        <a href="/kinerja-dosen" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/kinerja-dosen') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-person-badge text-xs"></i><span>Evaluasi Dosen (EDOM)</span></a>
                    </div>
                </div>

                <!-- PILAR 4: PENGENDALIAN -->
                <div>
                    <button
                        @click="toggleGroup('p4')"
                        class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-lg bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center justify-center">P4</span>
                            <span class="tracking-tight uppercase text-[11px]">Pengendalian Mutu</span>
                        </div>
                        <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.p4 }"></i>
                    </button>
                    <div v-show="openGroups.p4" class="pl-6 pr-1 py-1 space-y-1 border-l-2 border-rose-100 ml-4.5 my-1">
                        <a href="/tindak-lanjut" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/tindak-lanjut') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-arrow-repeat text-xs"></i><span>Tindak Lanjut PTK</span></a>
                        <a href="/rtm" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/rtm') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-people-fill text-xs"></i><span>Rapat Tinjauan Manajemen</span></a>
                    </div>
                </div>

                <!-- PILAR 5: PENINGKATAN & LAPORAN -->
                <div>
                    <button
                        @click="toggleGroup('p5')"
                        class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                    >
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-[10px] flex items-center justify-center">P5</span>
                            <span class="tracking-tight uppercase text-[11px]">Peningkatan & Laporan</span>
                        </div>
                        <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.p5 }"></i>
                    </button>
                    <div v-show="openGroups.p5" class="pl-6 pr-1 py-1 space-y-1 border-l-2 border-emerald-100 ml-4.5 my-1">
                        <a href="/peningkatan-standar" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/peningkatan-standar') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-arrow-up-right-circle text-xs"></i><span>Peningkatan Standar (Kaizen)</span></a>
                        <a href="/benchmarking" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/benchmarking') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-buildings text-xs"></i><span>Benchmarking Mutu</span></a>
                        <a href="/laporan" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/laporan') && !isRouteActive('/laporan/tren') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-file-earmark-bar-graph text-xs"></i><span>Pusat Laporan SPMI</span></a>
                        <a href="/laporan/tren" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition" :class="isRouteActive('/laporan/tren') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"><i class="bi bi-graph-up text-xs"></i><span>Tren Perkembangan Mutu</span></a>
                    </div>
                </div>
            </nav>

            <!-- Switch to Portal Quick Launcher Button -->
            <div class="pt-2">
                <Link
                    href="/portal"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-700 border border-slate-200/80 transition"
                >
                    <i class="bi bi-grid-fill text-indigo-600"></i>
                    <span>Kembali ke Portal Modul</span>
                </Link>
            </div>
        </aside>

        <!-- Right Wrapper (Topbar + Main Content Area) -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            
            <!-- Top Navbar (Positioned strictly beside sidebar) -->
            <header class="bg-white/90 border-b border-slate-200/80 sticky top-0 z-30 shadow-xs backdrop-blur-xl transition-all">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center gap-4">
                        
                        <!-- Left: Hamburger / Toggle & Active Period Indicator -->
                        <div class="flex items-center gap-3 shrink-0">
                            <button
                                @click="toggleSidebar"
                                class="hidden md:flex p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer border border-slate-200/80 shadow-2xs"
                                title="Sembunyikan / Tampilkan Sidebar"
                            >
                                <i class="bi bi-layout-sidebar text-base"></i>
                            </button>

                            <button
                                @click="toggleMobileSidebar"
                                class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer border border-slate-200/80 shadow-2xs"
                                title="Menu"
                            >
                                <i class="bi bi-list text-xl"></i>
                            </button>

                            <!-- Mobile Logo for small screens -->
                            <div class="flex md:hidden items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">P</div>
                                <span class="font-extrabold text-slate-900 text-sm">PINTAR</span>
                            </div>

                            <!-- Active Period Status Pill -->
                            <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-100/80 border border-slate-200/80 text-xs shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-slate-500 font-medium">Periode Akademik:</span>
                                <span class="font-extrabold text-slate-900 font-mono">
                                    {{ activePeriode?.nama || (activePeriode?.tahun ? `${activePeriode.tahun} (${activePeriode.semester || '-'})` : 'Tahun Akademik Berjalan') }}
                                </span>
                            </div>
                        </div>

                        <!-- Right: Search, Notifications, Portal, and User Profile -->
                        <div class="flex items-center gap-2 sm:gap-2.5">
                            <!-- Global Omnibar Search Trigger (Ctrl + K) -->
                            <button
                                @click="openCommandPalette"
                                type="button"
                                class="flex items-center gap-2 px-3 py-1.5 sm:py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100/80 hover:bg-slate-200/80 border border-slate-200/80 transition cursor-pointer group shadow-2xs"
                                title="Pencarian Cepat Global (Ctrl + K)"
                            >
                                <i class="bi bi-search text-slate-400 group-hover:text-indigo-600 transition"></i>
                                <span class="hidden md:inline text-slate-600 text-[11px]">Cari lintas modul...</span>
                                <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[9px] font-mono font-bold text-slate-400 bg-white border border-slate-200 rounded-md shadow-2xs">Ctrl K</kbd>
                            </button>

                            <!-- Notification Bell Dropdown -->
                            <div class="relative">
                                <button
                                    @click="notificationDropdownOpen = !notificationDropdownOpen"
                                    type="button"
                                    class="relative p-2 sm:p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 border border-slate-200/80 transition cursor-pointer shadow-2xs"
                                    title="Pusat Pemberitahuan"
                                >
                                    <i class="bi bi-bell-fill text-sm sm:text-base" :class="notifications.unread_count > 0 ? 'text-indigo-600' : 'text-slate-500'"></i>
                                    <span
                                        v-if="notifications.unread_count > 0"
                                        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white font-black text-[9px] flex items-center justify-center border-2 border-white shadow-xs animate-pulse"
                                    >
                                        {{ notifications.unread_count > 9 ? '9+' : notifications.unread_count }}
                                    </span>
                                </button>

                                <!-- Notification Dropdown Card -->
                                <div
                                    v-if="notificationDropdownOpen"
                                    @click.outside="notificationDropdownOpen = false"
                                    class="absolute right-0 mt-2.5 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-100 py-3 z-50 animate-in fade-in slide-in-from-top-2 duration-150"
                                >
                                    <div class="px-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-slate-900 text-xs">Pemberitahuan</span>
                                            <span v-if="notifications.unread_count > 0" class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                                {{ notifications.unread_count }} Baru
                                            </span>
                                        </div>
                                        <button
                                            v-if="notifications.unread_count > 0"
                                            @click="markAllAsRead"
                                            type="button"
                                            class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition cursor-pointer"
                                        >
                                            Tandai Semua Dibaca
                                        </button>
                                    </div>

                                    <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                                        <div v-if="!notifications.list || notifications.list.length === 0" class="py-8 text-center text-slate-400">
                                            <i class="bi bi-bell-slash text-2xl mb-1.5 block"></i>
                                            <p class="text-xs font-bold text-slate-600">Tidak ada notifikasi baru</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Semua agenda & temuan telah terpantau</p>
                                        </div>
                                        <div
                                            v-for="notif in notifications.list"
                                            :key="notif.id"
                                            class="p-3 hover:bg-slate-50 transition flex items-start gap-3 cursor-pointer group"
                                            :class="{ 'bg-indigo-50/30': !notif.read_at }"
                                            @click="markAsRead(notif.id)"
                                        >
                                            <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center text-sm shadow-2xs" :class="!notif.read_at ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500'">
                                                <i class="bi" :class="notif.type.includes('Rapat') ? 'bi-calendar-event' : (notif.type.includes('Finding') ? 'bi-clipboard2-check' : 'bi-info-circle')"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-slate-800 leading-snug line-clamp-2">
                                                    {{ notif.data?.title || notif.data?.pesan || notif.type }}
                                                </p>
                                                <p v-if="notif.data?.subtitle || notif.data?.detail" class="text-[10px] text-slate-500 line-clamp-1 mt-0.5">
                                                    {{ notif.data?.subtitle || notif.data?.detail }}
                                                </p>
                                                <span class="text-[9px] font-semibold text-slate-400 mt-1 block">
                                                    {{ notif.created_at }}
                                                </span>
                                            </div>
                                            <span v-if="!notif.read_at" class="w-2 h-2 rounded-full bg-indigo-600 shrink-0 mt-1.5"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Portal Modul Launcher -->
                            <Link
                                href="/portal"
                                class="px-3 py-1.5 sm:py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-indigo-600 bg-slate-100/80 hover:bg-indigo-50/80 border border-slate-200/70 hover:border-indigo-200 transition flex items-center gap-1.5 shadow-2xs group"
                                title="Buka Portal Modul ERP"
                            >
                                <i class="bi bi-grid-fill text-indigo-600 text-xs group-hover:rotate-12 transition duration-200"></i>
                                <span class="hidden sm:inline">Portal</span>
                            </Link>

                            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <button
                                    @click="userDropdownOpen = !userDropdownOpen"
                                    class="flex items-center gap-2 p-1.5 sm:pr-2.5 rounded-2xl hover:bg-slate-100 transition cursor-pointer border border-transparent hover:border-slate-200"
                                >
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-700 text-white font-black text-xs flex items-center justify-center shadow-xs ring-2 ring-indigo-500/20">
                                        {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                                    </div>
                                    <div class="hidden md:block text-left leading-tight">
                                        <p class="text-xs font-extrabold text-slate-800 truncate max-w-[130px]">
                                            {{ user?.name || 'Super Admin' }}
                                        </p>
                                        <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">
                                            {{ user?.role || 'Administrator' }}
                                        </p>
                                    </div>
                                    <i class="bi bi-chevron-down text-slate-400 text-[10px] hidden md:block transition-transform duration-150" :class="{ 'rotate-180': userDropdownOpen }"></i>
                                </button>

                                <!-- Dropdown Menu Card -->
                                <div
                                    v-if="userDropdownOpen"
                                    @click.outside="userDropdownOpen = false"
                                    class="absolute right-0 mt-2.5 w-60 bg-white rounded-3xl shadow-2xl border border-slate-100 py-2.5 z-50 animate-in fade-in slide-in-from-top-2 duration-150"
                                >
                                    <div class="px-4 py-3 border-b border-slate-100">
                                        <p class="text-xs font-black text-slate-900 truncate">{{ user?.name || 'Super Admin' }}</p>
                                        <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ user?.email || 'admin@polka.ac.id' }}</p>
                                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ user?.role || 'Super Admin' }}
                                        </span>
                                    </div>

                                    <div class="py-1.5">
                                        <a
                                            href="/profile"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition"
                                        >
                                            <i class="bi bi-person text-slate-400"></i>
                                            <span>Profil Pengguna</span>
                                        </a>
                                        <a
                                            href="/portal"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition"
                                        >
                                            <i class="bi bi-grid text-slate-400"></i>
                                            <span>Portal Modul ERP</span>
                                        </a>
                                    </div>

                                    <div class="border-t border-slate-100 pt-1.5 px-2">
                                        <button
                                            @click="logout"
                                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                        >
                                            <i class="bi bi-box-arrow-right text-rose-500"></i>
                                            <span>Keluar (Logout)</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full overflow-y-auto">
                <div v-if="flash?.success" class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-base shrink-0"></i>
                    <span class="flex-1">{{ flash.success }}</span>
                </div>

                <div v-if="flash?.error" class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <i class="bi bi-exclamation-octagon-fill text-rose-600 text-base shrink-0"></i>
                    <span class="flex-1">{{ flash.error }}</span>
                </div>

                <slot />
            </main>
        </div>

        <!-- Mobile Drawer (Full Overlay for Phones/Tablets) -->
        <div
            v-if="mobileSidebarOpen"
            class="fixed inset-0 z-50 md:hidden bg-slate-900/60 backdrop-blur-xs"
            @click="mobileSidebarOpen = false"
        >
            <div
                class="w-72 bg-white h-full flex flex-col p-5 shadow-2xl overflow-y-auto"
                @click.stop
            >
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-indigo-600 to-slate-900 text-white flex items-center justify-center font-bold text-base shadow-sm">P</div>
                        <div>
                            <span class="font-extrabold text-slate-900 text-sm block leading-tight">PINTAR</span>
                            <span class="text-[10px] font-bold uppercase text-indigo-600">{{ activeModule === 'sdm' ? 'MODUL SDM' : (activeModule === 'datamaster' ? 'DATA MASTER' : (activeModule === 'systemadmin' ? 'SYSTEM ADMIN' : 'SPMI PPEPP')) }}</span>
                        </div>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Mobile SDM Navigation -->
                <nav v-if="activeModule === 'sdm'" class="space-y-1 flex-1 text-xs">
                    <a href="/sdm" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-speedometer2"></i><span>Dashboard SDM</span></a>
                    <a href="/sdm/pegawai" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-person-vcard"></i><span>Master Pegawai</span></a>
                    
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kehadiran</div>
                    <a href="/sdm/presensi" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-fingerprint"></i><span>Presensi Harian</span></a>
                    <a href="/sdm/presensi/rekap" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-file-earmark-bar-graph"></i><span>Rekap Kehadiran</span></a>
                    <a href="/sdm/cuti" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-calendar-event"></i><span>Cuti & Izin</span></a>
                    <a href="/sdm/lembur" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-stopwatch"></i><span>Lembur</span></a>
                    
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kinerja</div>
                    <a href="/sdm/penilaian-kinerja" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-award"></i><span>Penilaian Kinerja</span></a>
                    <a href="/sdm/surat-tugas" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-journal-bookmark"></i><span>Surat Tugas</span></a>
                </nav>

                <!-- Mobile DataMaster Navigation -->
                <nav v-else-if="activeModule === 'datamaster'" class="space-y-1 flex-1 text-xs">
                    <a href="/periode" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-calendar3"></i><span>Periode Akademik</span></a>
                    <a href="/program-studi" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-mortarboard"></i><span>Program Studi</span></a>
                    <a href="/unit-kerja" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-building"></i><span>Unit Kerja & Lembaga</span></a>
                    <a href="/jabatan" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-award"></i><span>Jabatan & Fungsional</span></a>
                    <a href="/ruangan" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-door-open"></i><span>Ruangan & Gedung</span></a>
                </nav>

                <!-- Mobile Persuratan Navigation -->
                <nav v-else-if="activeModule === 'persuratan'" class="space-y-1 flex-1 text-xs">
                    <a href="/manajemen-surat" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-speedometer2"></i><span>Dashboard Persuratan</span></a>
                    <a href="/surat-masuk" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-inbox-fill"></i><span>Surat Masuk</span></a>
                    <a href="/disposisi/my-disposisi" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-send-check-fill"></i><span>Disposisi Saya</span></a>
                    <a href="/surat-keluar" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-send-fill"></i><span>Surat Keluar Dinas</span></a>
                    <a href="/surat-keputusan" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-file-earmark-text-fill"></i><span>Surat Keputusan</span></a>
                    <a href="/unit-pengelola" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-tags-fill"></i><span>Unit & Klasifikasi Surat</span></a>
                </nav>

                <!-- Mobile Rapat Navigation -->
                <nav v-else-if="activeModule === 'rapat'" class="space-y-1 flex-1 text-xs">
                    <Link href="/rapat" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-calendar2-check-fill"></i><span>Daftar Semua Rapat</span></Link>
                    <Link href="/rapat/create" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-plus-circle-fill"></i><span>Buat Rapat Baru</span></Link>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Filter Status</div>
                    <Link href="/rapat?status=terjadwal" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-calendar-event"></i><span>Rapat Terjadwal</span></Link>
                    <Link href="/rapat?status=berlangsung" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-broadcast"></i><span>Sedang Berlangsung</span></Link>
                    <Link href="/rapat?status=selesai" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-check2-all"></i><span>Arsip & Selesai</span></Link>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kategori</div>
                    <Link href="/rapat?jenis=RTM" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-award-fill"></i><span>RTM (Tinjauan Manajemen)</span></Link>
                </nav>

                <!-- Mobile Kerjasama Navigation -->
                <nav v-else-if="activeModule === 'kerjasama'" class="space-y-1 flex-1 text-xs">
                    <a href="/kerjasama" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-diagram-3-fill"></i><span>Daftar Kerjasama</span></a>
                    <a href="/kerjasama/create" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-plus-circle-fill"></i><span>Tambah Kerjasama Baru</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Tingkat Wilayah</div>
                    <a href="/kerjasama?tingkat=Internasional" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-globe-americas"></i><span>Internasional</span></a>
                    <a href="/kerjasama?tingkat=Nasional" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-flag-fill"></i><span>Nasional</span></a>
                    <a href="/kerjasama?tingkat=Lokal" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-geo-alt-fill"></i><span>Lokal</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Status</div>
                    <a href="/kerjasama?status=Aktif" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-check-circle-fill"></i><span>Status Aktif</span></a>
                </nav>

                <!-- Mobile Aset Navigation -->
                <nav v-else-if="activeModule === 'aset'" class="space-y-1 flex-1 text-xs">
                    <a href="/aset" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-box-seam-fill"></i><span>Daftar Inventaris Aset</span></a>
                    <a href="/aset/create" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-plus-circle-fill"></i><span>Tambah Aset Baru</span></a>
                    <a href="/kategori-aset" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-tags-fill"></i><span>Kategori Aset</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Layanan Sarpras</div>
                    <a href="/pemeliharaan" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-tools"></i><span>Pemeliharaan / Servis</span></a>
                    <a href="/peminjaman" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-arrow-left-right"></i><span>Peminjaman Aset</span></a>
                    <a href="/booking-ruangan" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-door-open-fill"></i><span>Booking Ruangan</span></a>
                </nav>

                <!-- Mobile Tridharma Navigation -->
                <nav v-else-if="activeModule === 'tridharma'" class="space-y-1 flex-1 text-xs">
                    <Link href="/penelitian" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-journal-bookmark-fill"></i><span>Penelitian & Riset</span></Link>
                    <Link href="/pengabdian" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-people-fill"></i><span>Pengabdian (PkM)</span></Link>
                    <Link href="/publikasi" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-journal-text"></i><span>Publikasi & Jurnal</span></Link>
                    <Link href="/hki" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-award-fill"></i><span>HKI & Paten</span></Link>
                </nav>

                <!-- Mobile Tracer Study Navigation -->
                <nav v-else-if="activeModule === 'tracer_study'" class="space-y-1 flex-1 text-xs">
                    <Link href="/tracer-study" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-people-fill"></i><span>Daftar Alumni</span></Link>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">Status Karir</div>
                    <Link href="/tracer-study?status_kerja=Bekerja" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-briefcase-fill text-emerald-600"></i><span>Alumni Bekerja</span></Link>
                    <Link href="/tracer-study?status_kerja=Wirausaha" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-shop text-purple-600"></i><span>Wirausaha</span></Link>
                    <Link href="/tracer-study?status_kerja=Melanjutkan" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-mortarboard-fill text-blue-600"></i><span>Lanjut Studi</span></Link>
                </nav>

                <!-- Mobile Data Akademik Navigation -->
                <nav v-else-if="activeModule === 'data_akademik'" class="space-y-1 flex-1 text-xs">
                    <Link href="/mahasiswa" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-people-fill"></i><span>Data Mahasiswa</span></Link>
                    <Link href="/prestasi" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-trophy-fill"></i><span>Prestasi Mahasiswa</span></Link>
                    <Link href="/alumni" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-mortarboard-fill"></i><span>Rekap Data Alumni</span></Link>
                </nav>

                <!-- Mobile SystemAdmin Navigation -->
                <nav v-else-if="activeModule === 'systemadmin'" class="space-y-1 flex-1 text-xs">
                    <a href="/settings" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-sliders"></i><span>Pengaturan Sistem</span></a>
                    <a href="/users" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-people-fill"></i><span>Pengguna & Akun</span></a>
                    <a href="/roles" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-shield-lock-fill"></i><span>Peran & Hak Akses</span></a>
                    <a href="/activity-log" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-clock-history"></i><span>Log Aktivitas</span></a>
                </nav>

                <!-- Mobile SPMI Navigation -->
                <nav v-else class="space-y-1 flex-1 text-xs">
                    <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                    <a href="/siklus-spmi" class="flex items-center gap-3 px-3 py-2 rounded-xl font-semibold"><i class="bi bi-arrow-repeat"></i><span>Siklus PPEPP</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">P1: Penetapan</div>
                    <a href="/dokumen" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-folder2"></i><span>Dokumen Mutu</span></a>
                    <a href="/standar" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-bookmark-check"></i><span>Standar Mutu</span></a>
                    <a href="/indikator-kinerja" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-bullseye"></i><span>Indikator IKU/IKT</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">P2: Pelaksanaan</div>
                    <a href="/monitoring" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-bar-chart-line"></i><span>Monitoring IKU</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">P3: Evaluasi</div>
                    <a href="/audit" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-clipboard2-check"></i><span>Audit Mutu (AMI)</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">P4: Pengendalian</div>
                    <a href="/tindak-lanjut" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-arrow-repeat"></i><span>Tindak Lanjut PTK</span></a>
                    <a href="/rtm" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-people-fill"></i><span>RTM</span></a>
                    <div class="pt-3 pb-1 text-[10px] font-bold uppercase text-slate-400 tracking-wider">P5: Peningkatan</div>
                    <a href="/laporan" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-file-earmark-bar-graph"></i><span>Pusat Laporan</span></a>
                </nav>

                <div class="pt-4 mt-auto border-t border-slate-100">
                    <Link href="/portal" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                        <i class="bi bi-grid-fill text-indigo-600"></i>
                        <span>Portal Modul</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Global Command Palette Modal (Ctrl + K) -->
        <Teleport to="body">
            <div
                v-if="commandPaletteOpen"
                class="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24 bg-slate-900/40 backdrop-blur-xs p-4"
                @click.self="closeCommandPalette"
            >
                <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/80 w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 flex flex-col max-h-[80vh]">
                    <!-- Search Bar Header -->
                    <div class="p-4 border-b border-slate-100 flex items-center gap-3">
                        <i class="bi bi-search text-slate-400 text-base"></i>
                        <input
                            v-model="searchQuery"
                            @input="handleSearchInput"
                            type="text"
                            placeholder="Cari dosen, mahasiswa, dokumen mutu, surat, rapat..."
                            class="flex-1 text-sm font-semibold text-slate-900 placeholder:text-slate-400 outline-none bg-transparent"
                            autofocus
                        />
                        <i v-if="isSearching" class="bi bi-arrow-repeat text-indigo-600 animate-spin text-sm"></i>
                        <kbd class="px-2 py-1 text-[10px] font-mono font-bold text-slate-400 bg-slate-100 rounded-lg">ESC</kbd>
                    </div>

                    <!-- Search Results List -->
                    <div class="overflow-y-auto p-2 divide-y divide-slate-50 flex-1">
                        <!-- Empty Query State -->
                        <div v-if="!searchQuery || searchQuery.trim().length < 2" class="p-8 text-center text-slate-400 space-y-2">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg mx-auto">
                                <i class="bi bi-command"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Pencarian Lintas Modul ERP</p>
                            <p class="text-[11px] text-slate-400 max-w-xs mx-auto">Ketik minimal 2 karakter untuk mencari Dosen (SDM), Mahasiswa, Dokumen Mutu, Surat, Rapat, atau Alumni.</p>
                            <div class="flex items-center justify-center gap-2 pt-2 text-[10px] text-slate-400 font-mono">
                                <span><kbd class="px-1.5 py-0.5 bg-slate-100 rounded">↑</kbd> <kbd class="px-1.5 py-0.5 bg-slate-100 rounded">↓</kbd> Navigasi</span>
                                <span>·</span>
                                <span><kbd class="px-1.5 py-0.5 bg-slate-100 rounded">↵</kbd> Buka</span>
                            </div>
                        </div>

                        <!-- No Results State -->
                        <div v-else-if="!isSearching && searchResults.length === 0" class="p-8 text-center text-slate-400">
                            <i class="bi bi-search text-2xl mb-1.5 block text-slate-300"></i>
                            <p class="text-xs font-bold text-slate-600">Tidak ada data yang cocok</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Coba kata kunci lain atau periksa ejaan</p>
                        </div>

                        <!-- Results Items -->
                        <div
                            v-for="(item, index) in searchResults"
                            :key="index"
                            @click="navigateToResult(item.url)"
                            @mouseenter="selectedResultIndex = index"
                            class="p-3 rounded-2xl flex items-center justify-between gap-3 cursor-pointer transition"
                            :class="selectedResultIndex === index ? 'bg-indigo-50/80 text-indigo-900' : 'hover:bg-slate-50 text-slate-800'"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center text-base" :class="selectedResultIndex === index ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600'">
                                    <i class="bi" :class="item.icon || 'bi-link-45deg'"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-extrabold truncate" :class="selectedResultIndex === index ? 'text-indigo-950' : 'text-slate-900'">
                                        {{ item.title }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">
                                        {{ item.subtitle }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase shrink-0 border" :class="item.badge_bg || 'bg-slate-100 text-slate-700'">
                                {{ item.badge }}
                            </span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-[10px] text-slate-400 flex items-center justify-between px-4">
                        <span>PINTAR Unified Search Engine</span>
                        <span class="font-mono text-slate-500">{{ searchResults.length }} hasil ditemukan</span>
                    </div>
                </div>
            </div>
        </Teleport>

    </div>
</template>
