<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash || {});

const sidebarOpen = ref(true);
const mobileSidebarOpen = ref(false);
const userDropdownOpen = ref(false);

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

// Accordion Groups for 5 Pillars of PPEPP
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
    <div class="min-h-screen bg-slate-100/70 text-slate-800 font-sans flex flex-col antialiased selection:bg-indigo-600 selection:text-white">
        <!-- Top Navbar -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/90 sticky top-0 z-30 shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <!-- Left: Hamburger Toggle & Brand -->
                    <div class="flex items-center gap-3.5">
                        <button
                            @click="toggleSidebar"
                            class="hidden md:flex p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer"
                            title="Sembunyikan / Buka Menu Sidebar"
                        >
                            <i class="bi bi-layout-sidebar-inset text-lg"></i>
                        </button>

                        <button
                            @click="toggleMobileSidebar"
                            class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer"
                            title="Menu Navigasi Mobile"
                        >
                            <i class="bi bi-list text-xl"></i>
                        </button>

                        <!-- Brand Logo & Badge -->
                        <Link href="/dashboard" class="flex items-center gap-3 group">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-700 via-indigo-600 to-violet-600 flex items-center justify-center text-white font-black text-lg shadow-md shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-200">
                                P
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-slate-900 text-base tracking-tight leading-none">
                                        ERP-POLKA
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60 leading-none">
                                        SPMI PPEPP
                                    </span>
                                </div>
                                <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase mt-0.5">
                                    Sistem Penjaminan Mutu Internal
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- Right: Quick Status, Portal Launcher, User Dropdown -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        
                        <!-- Status Siklus Berjalan -->
                        <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-200/80 text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-slate-600 font-medium">Siklus SPMI Aktif</span>
                        </div>

                        <!-- Portal Launcher Shortcut -->
                        <Link
                            href="/portal"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:text-indigo-600 hover:bg-indigo-50/80 border border-slate-200/90 hover:border-indigo-200 transition flex items-center gap-2 shadow-xs"
                            title="Buka Portal Beranda Modul ERP"
                        >
                            <i class="bi bi-grid-fill text-indigo-600 text-xs"></i>
                            <span class="hidden sm:inline">Portal Modul</span>
                        </Link>

                        <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>

                        <!-- User Profile Dropdown Button -->
                        <div class="relative">
                            <button
                                @click="userDropdownOpen = !userDropdownOpen"
                                class="flex items-center gap-2.5 p-1 rounded-2xl hover:bg-slate-100/80 transition cursor-pointer border border-transparent hover:border-slate-200"
                            >
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                    {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[130px]">
                                        {{ user?.name || 'Pengguna' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium capitalize leading-tight">
                                        {{ user?.role || 'Staff' }}
                                    </p>
                                </div>
                                <i class="bi bi-chevron-down text-slate-400 text-[10px] hidden md:block transition-transform duration-200" :class="{ 'rotate-180': userDropdownOpen }"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                v-if="userDropdownOpen"
                                @click.outside="userDropdownOpen = false"
                                class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150"
                            >
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ user?.name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ user?.email }}</p>
                                    <span class="inline-block mt-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-indigo-100 text-indigo-800">
                                        {{ user?.role || 'User' }}
                                    </span>
                                </div>

                                <div class="py-1.5">
                                    <a
                                        href="/profile"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 transition"
                                    >
                                        <i class="bi bi-person text-slate-400"></i>
                                        Profil Pengguna
                                    </a>
                                    <a
                                        href="/portal"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 transition"
                                    >
                                        <i class="bi bi-grid text-slate-400"></i>
                                        Kembali ke Portal
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 pt-1.5">
                                    <button
                                        @click="logout"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                    >
                                        <i class="bi bi-box-arrow-right text-rose-500"></i>
                                        Keluar dari Sistem
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Workspace Container -->
        <div class="flex flex-1 relative overflow-hidden">
            
            <!-- Desktop Sidebar Navigation (Sticky with Independent Scroll) -->
            <aside
                v-if="sidebarOpen"
                class="hidden md:flex flex-col w-72 bg-white border-r border-slate-200/80 shrink-0 h-[calc(100vh-4rem)] sticky top-16 select-none overflow-hidden"
            >
                <div class="p-3.5 flex-1 overflow-y-auto space-y-1.5">
                    
                    <!-- Dashboard -->
                    <a
                        href="/dashboard"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition group"
                        :class="isRouteActive('/dashboard')
                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30'
                            : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <i class="bi bi-speedometer2 text-base" :class="isRouteActive('/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600'"></i>
                        <span>Dashboard Eksekutif</span>
                    </a>

                    <!-- Siklus Master PPEPP -->
                    <a
                        href="/siklus-spmi"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition group"
                        :class="isRouteActive(['/siklus-spmi', '/ppepp'])
                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30'
                            : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <i class="bi bi-arrow-repeat text-base" :class="isRouteActive(['/siklus-spmi', '/ppepp']) ? 'text-white' : 'text-slate-400 group-hover:text-indigo-600'"></i>
                        <span>Siklus Mutu (Master PPEPP)</span>
                    </a>

                    <div class="pt-2 pb-1">
                        <div class="h-px bg-slate-100"></div>
                    </div>

                    <!-- PILAR 1: PENETAPAN STANDAR -->
                    <div>
                        <button
                            @click="toggleGroup('p1')"
                            class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-indigo-100 text-indigo-700 font-black text-[10px] flex items-center justify-center shrink-0">P1</span>
                                <span class="tracking-tight uppercase text-[11px] font-extrabold text-slate-800">Penetapan Standar</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openGroups.p1 }"></i>
                        </button>
                        <div v-show="openGroups.p1" class="pl-5 pr-1 py-1 space-y-0.5 border-l-2 border-indigo-100 ml-4.5 my-1">
                            <a
                                href="/dokumen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/dokumen') ? 'text-indigo-700 font-bold bg-indigo-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-folder2 text-xs" :class="isRouteActive('/dokumen') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                <span>Dokumen Mutu</span>
                            </a>
                            <a
                                href="/standar"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/standar') ? 'text-indigo-700 font-bold bg-indigo-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-bookmark-check text-xs" :class="isRouteActive('/standar') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                <span>Standar Mutu SN-Dikti</span>
                            </a>
                            <a
                                href="/kategori-dokumen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/kategori-dokumen') ? 'text-indigo-700 font-bold bg-indigo-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-tags text-xs" :class="isRouteActive('/kategori-dokumen') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                <span>Kategori Dokumen</span>
                            </a>
                            <a
                                href="/indikator-kinerja"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/indikator-kinerja') ? 'text-indigo-700 font-bold bg-indigo-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-bullseye text-xs" :class="isRouteActive('/indikator-kinerja') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                <span>Indikator Mutu (IKU/IKT)</span>
                            </a>
                            <a
                                href="/iku-resmi"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/iku-resmi') ? 'text-indigo-700 font-bold bg-indigo-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-award text-xs" :class="isRouteActive('/iku-resmi') ? 'text-indigo-600' : 'text-slate-400'"></i>
                                <span>8 IKU Kemdiktisaintek</span>
                            </a>
                        </div>
                    </div>

                    <!-- PILAR 2: PELAKSANAAN STANDAR -->
                    <div>
                        <button
                            @click="toggleGroup('p2')"
                            class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 font-black text-[10px] flex items-center justify-center shrink-0">P2</span>
                                <span class="tracking-tight uppercase text-[11px] font-extrabold text-slate-800">Pelaksanaan Standar</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openGroups.p2 }"></i>
                        </button>
                        <div v-show="openGroups.p2" class="pl-5 pr-1 py-1 space-y-0.5 border-l-2 border-blue-100 ml-4.5 my-1">
                            <a
                                href="/monitoring"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/monitoring') ? 'text-blue-700 font-bold bg-blue-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-bar-chart-line text-xs" :class="isRouteActive('/monitoring') ? 'text-blue-600' : 'text-slate-400'"></i>
                                <span>Monitoring Realisasi IKU</span>
                            </a>
                            <a
                                href="/integrasi"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/integrasi') ? 'text-blue-700 font-bold bg-blue-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-diagram-3 text-xs" :class="isRouteActive('/integrasi') ? 'text-blue-600' : 'text-slate-400'"></i>
                                <span>Integrasi Data ERP</span>
                            </a>
                        </div>
                    </div>

                    <!-- PILAR 3: EVALUASI PELAKSANAAN -->
                    <div>
                        <button
                            @click="toggleGroup('p3')"
                            class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-amber-100 text-amber-800 font-black text-[10px] flex items-center justify-center shrink-0">P3</span>
                                <span class="tracking-tight uppercase text-[11px] font-extrabold text-slate-800">Evaluasi Pelaksanaan</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openGroups.p3 }"></i>
                        </button>
                        <div v-show="openGroups.p3" class="pl-5 pr-1 py-1 space-y-0.5 border-l-2 border-amber-100 ml-4.5 my-1">
                            <a
                                href="/evaluasi"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/evaluasi') ? 'text-amber-800 font-bold bg-amber-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-graph-up-arrow text-xs" :class="isRouteActive('/evaluasi') ? 'text-amber-700' : 'text-slate-400'"></i>
                                <span>Evaluasi Capaian Standar</span>
                            </a>
                            <a
                                href="/audit"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/audit') ? 'text-amber-800 font-bold bg-amber-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-clipboard2-check text-xs" :class="isRouteActive('/audit') ? 'text-amber-700' : 'text-slate-400'"></i>
                                <span>Audit Mutu Internal (AMI)</span>
                            </a>
                            <a
                                href="/survei"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/survei') ? 'text-amber-800 font-bold bg-amber-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-chat-square-dots text-xs" :class="isRouteActive('/survei') ? 'text-amber-700' : 'text-slate-400'"></i>
                                <span>Survei Kepuasan</span>
                            </a>
                            <a
                                href="/kuesioner"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/kuesioner') ? 'text-amber-800 font-bold bg-amber-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-ui-checks text-xs" :class="isRouteActive('/kuesioner') ? 'text-amber-700' : 'text-slate-400'"></i>
                                <span>Manajemen Kuesioner</span>
                            </a>
                            <a
                                href="/kinerja-dosen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/kinerja-dosen') ? 'text-amber-800 font-bold bg-amber-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-person-badge text-xs" :class="isRouteActive('/kinerja-dosen') ? 'text-amber-700' : 'text-slate-400'"></i>
                                <span>Evaluasi Dosen (EDOM)</span>
                            </a>
                        </div>
                    </div>

                    <!-- PILAR 4: PENGENDALIAN -->
                    <div>
                        <button
                            @click="toggleGroup('p4')"
                            class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-rose-100 text-rose-700 font-black text-[10px] flex items-center justify-center shrink-0">P4</span>
                                <span class="tracking-tight uppercase text-[11px] font-extrabold text-slate-800">Pengendalian Mutu</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openGroups.p4 }"></i>
                        </button>
                        <div v-show="openGroups.p4" class="pl-5 pr-1 py-1 space-y-0.5 border-l-2 border-rose-100 ml-4.5 my-1">
                            <a
                                href="/tindak-lanjut"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/tindak-lanjut') ? 'text-rose-700 font-bold bg-rose-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-arrow-repeat text-xs" :class="isRouteActive('/tindak-lanjut') ? 'text-rose-600' : 'text-slate-400'"></i>
                                <span>Tindak Lanjut PTK</span>
                            </a>
                            <a
                                href="/rtm"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/rtm') ? 'text-rose-700 font-bold bg-rose-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-people-fill text-xs" :class="isRouteActive('/rtm') ? 'text-rose-600' : 'text-slate-400'"></i>
                                <span>Rapat Tinjauan Manajemen</span>
                            </a>
                        </div>
                    </div>

                    <!-- PILAR 5: PENINGKATAN & LAPORAN -->
                    <div>
                        <button
                            @click="toggleGroup('p5')"
                            class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-emerald-100 text-emerald-700 font-black text-[10px] flex items-center justify-center shrink-0">P5</span>
                                <span class="tracking-tight uppercase text-[11px] font-extrabold text-slate-800">Peningkatan & Laporan</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openGroups.p5 }"></i>
                        </button>
                        <div v-show="openGroups.p5" class="pl-5 pr-1 py-1 space-y-0.5 border-l-2 border-emerald-100 ml-4.5 my-1">
                            <a
                                href="/laporan"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/laporan') && !isRouteActive('/laporan/tren') ? 'text-emerald-700 font-bold bg-emerald-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-file-earmark-bar-graph text-xs" :class="isRouteActive('/laporan') && !isRouteActive('/laporan/tren') ? 'text-emerald-600' : 'text-slate-400'"></i>
                                <span>Pusat Laporan SPMI</span>
                            </a>
                            <a
                                href="/laporan/tren"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl text-xs font-medium transition"
                                :class="isRouteActive('/laporan/tren') ? 'text-emerald-700 font-bold bg-emerald-50/80 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                            >
                                <i class="bi bi-graph-up text-xs" :class="isRouteActive('/laporan/tren') ? 'text-emerald-600' : 'text-slate-400'"></i>
                                <span>Tren Perkembangan Mutu</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sticky Sidebar Footer Card -->
                <div class="p-3.5 border-t border-slate-100 bg-slate-50/60">
                    <div class="p-3.5 rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white shadow-md border border-white/10 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[11px] font-bold text-indigo-200">Siklus PPEPP Aktif</span>
                        </div>
                        <p class="text-[10px] text-slate-300 leading-snug">
                            Penjaminan Mutu Berkelanjutan SN-Dikti & Kepmendikti 358/2025.
                        </p>
                    </div>
                </div>
            </aside>

            <!-- Mobile Navigation Drawer -->
            <div
                v-if="mobileSidebarOpen"
                class="fixed inset-0 z-40 md:hidden bg-slate-900/60 backdrop-blur-xs"
                @click="mobileSidebarOpen = false"
            >
                <div
                    class="w-76 bg-white h-full flex flex-col p-5 shadow-2xl overflow-y-auto"
                    @click.stop
                >
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black">P</div>
                            <span class="font-extrabold text-slate-900">SPMI PPEPP</span>
                        </div>
                        <button @click="mobileSidebarOpen = false" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <nav class="space-y-1 flex-1 text-xs">
                        <a href="/dashboard" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold" :class="isRouteActive('/dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-700'">
                            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                        </a>
                        <a href="/siklus-spmi" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold" :class="isRouteActive(['/siklus-spmi', '/ppepp']) ? 'bg-indigo-600 text-white' : 'text-slate-700'">
                            <i class="bi bi-arrow-repeat"></i><span>Siklus PPEPP</span>
                        </a>

                        <div class="pt-3 pb-1 text-[10px] font-extrabold uppercase text-indigo-600 tracking-wider">P1: Penetapan</div>
                        <a href="/dokumen" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-folder2"></i><span>Dokumen Mutu</span></a>
                        <a href="/standar" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-bookmark-check"></i><span>Standar Mutu</span></a>
                        <a href="/indikator-kinerja" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-bullseye"></i><span>Indikator Mutu</span></a>
                        <a href="/iku-resmi" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-award"></i><span>8 IKU Resmi</span></a>

                        <div class="pt-3 pb-1 text-[10px] font-extrabold uppercase text-blue-600 tracking-wider">P2: Pelaksanaan</div>
                        <a href="/monitoring" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-bar-chart-line"></i><span>Monitoring IKU</span></a>
                        <a href="/integrasi" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-diagram-3"></i><span>Integrasi ERP</span></a>

                        <div class="pt-3 pb-1 text-[10px] font-extrabold uppercase text-amber-600 tracking-wider">P3: Evaluasi</div>
                        <a href="/evaluasi" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-graph-up-arrow"></i><span>Evaluasi Capaian</span></a>
                        <a href="/audit" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-clipboard2-check"></i><span>Audit Mutu Internal</span></a>
                        <a href="/survei" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-chat-square-dots"></i><span>Survei Kepuasan</span></a>

                        <div class="pt-3 pb-1 text-[10px] font-extrabold uppercase text-rose-600 tracking-wider">P4: Pengendalian</div>
                        <a href="/tindak-lanjut" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-arrow-repeat"></i><span>Tindak Lanjut PTK</span></a>
                        <a href="/rtm" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-people-fill"></i><span>RTM Pimpinan</span></a>

                        <div class="pt-3 pb-1 text-[10px] font-extrabold uppercase text-emerald-600 tracking-wider">P5: Peningkatan</div>
                        <a href="/laporan" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-file-earmark-bar-graph"></i><span>Pusat Laporan</span></a>
                        <a href="/laporan/tren" class="flex items-center gap-3 px-3 py-1.5 rounded-xl text-slate-600"><i class="bi bi-graph-up"></i><span>Tren Mutu</span></a>
                    </nav>
                </div>
            </div>

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
    </div>
</template>
