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

// Accordion Groups Open State
const openGroups = ref({
    dokumen: isRouteActive(['/dokumen', '/standar', '/kategori-dokumen']),
    monitoring: isRouteActive(['/indikator-kinerja', '/iku-resmi', '/monitoring', '/evaluasi', '/integrasi']),
    audit: isRouteActive(['/audit', '/tindak-lanjut', '/rtm']),
    laporan: isRouteActive(['/laporan', '/survei', '/kuesioner', '/kinerja-dosen']),
});

const toggleGroup = (groupKey) => {
    openGroups.value[groupKey] = !openGroups.value[groupKey];
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans flex flex-col antialiased selection:bg-indigo-500 selection:text-white">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs backdrop-blur-md bg-white/90">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Left: Hamburger & Logo -->
                    <div class="flex items-center gap-3">
                        <button
                            @click="toggleSidebar"
                            class="hidden md:flex p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition cursor-pointer"
                            title="Toggle Sidebar"
                        >
                            <i class="bi bi-list text-xl"></i>
                        </button>

                        <button
                            @click="toggleMobileSidebar"
                            class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition cursor-pointer"
                            title="Menu"
                        >
                            <i class="bi bi-list text-xl"></i>
                        </button>

                        <!-- Brand -->
                        <Link href="/dashboard" class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-indigo-200">
                                P
                            </div>
                            <div>
                                <span class="font-extrabold text-slate-900 text-base tracking-tight block leading-tight">
                                    ERP-POLKA
                                </span>
                                <span class="text-[10px] font-bold tracking-wider text-indigo-600 uppercase">
                                    Sistem Penjaminan Mutu
                                </span>
                            </div>
                        </Link>
                    </div>

                    <!-- Right: Portal Launcher, User Dropdown -->
                    <div class="flex items-center gap-3">
                        <!-- Portal Launcher Shortcut -->
                        <Link
                            href="/portal"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/70 border border-slate-200/80 hover:border-indigo-200 transition flex items-center gap-1.5"
                            title="Buka Portal Modul ERP"
                        >
                            <i class="bi bi-grid-fill text-indigo-500 text-xs"></i>
                            <span class="hidden sm:inline">Portal Modul</span>
                        </Link>

                        <div class="h-5 w-px bg-slate-200 hidden sm:block"></div>

                        <!-- User Profile Dropdown Button -->
                        <div class="relative">
                            <button
                                @click="userDropdownOpen = !userDropdownOpen"
                                class="flex items-center gap-2.5 p-1.5 rounded-2xl hover:bg-slate-100 transition cursor-pointer"
                            >
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-700 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                    {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[130px]">
                                        {{ user?.name || 'Pengguna' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium capitalize">
                                        {{ user?.role || 'User' }}
                                    </p>
                                </div>
                                <i class="bi bi-chevron-down text-slate-400 text-[10px] hidden md:block"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                v-if="userDropdownOpen"
                                @click.outside="userDropdownOpen = false"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150"
                            >
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ user?.name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ user?.email }}</p>
                                </div>

                                <div class="py-1">
                                    <a
                                        href="/profile"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition"
                                    >
                                        <i class="bi bi-person text-slate-400"></i>
                                        Profil Saya
                                    </a>
                                    <a
                                        href="/portal"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition"
                                    >
                                        <i class="bi bi-grid text-slate-400"></i>
                                        Portal Modul
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 pt-1">
                                    <button
                                        @click="logout"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                    >
                                        <i class="bi bi-box-arrow-right text-rose-500"></i>
                                        Keluar (Logout)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Wrapper -->
        <div class="flex flex-1 relative overflow-hidden">
            <!-- Desktop Sidebar -->
            <aside
                v-if="sidebarOpen"
                class="hidden md:flex flex-col w-68 bg-white border-r border-slate-200/80 shrink-0 p-4 transition-all duration-300 select-none overflow-y-auto"
            >
                <nav class="space-y-1 flex-1">
                    <!-- Dashboard -->
                    <a
                        href="/dashboard"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition group"
                        :class="isRouteActive('/dashboard')
                            ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <i class="bi bi-speedometer2 text-base" :class="isRouteActive('/dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Siklus PPEPP -->
                    <a
                        href="/siklus-spmi"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition group"
                        :class="isRouteActive(['/siklus-spmi', '/ppepp'])
                            ? 'bg-indigo-50 text-indigo-700 font-bold shadow-xs'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <i class="bi bi-arrow-repeat text-base" :class="isRouteActive(['/siklus-spmi', '/ppepp']) ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'"></i>
                        <span>Siklus PPEPP</span>
                    </a>

                    <!-- Accordion 1: Dokumen & Standar -->
                    <div class="pt-1">
                        <button
                            @click="toggleGroup('dokumen')"
                            class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2.5">
                                <i class="bi bi-folder2-open text-base text-slate-400"></i>
                                <span>Dokumen & Standar</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.dokumen }"></i>
                        </button>
                        <div v-show="openGroups.dokumen" class="pl-7 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-4.5 my-1">
                            <a
                                href="/dokumen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/dokumen') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-folder2 text-xs"></i>
                                <span>Dokumen Mutu</span>
                            </a>
                            <a
                                href="/standar"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/standar') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-bookmark-check text-xs"></i>
                                <span>Standar Mutu</span>
                            </a>
                            <a
                                href="/kategori-dokumen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/kategori-dokumen') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-tags text-xs"></i>
                                <span>Kategori Dokumen</span>
                            </a>
                        </div>
                    </div>

                    <!-- Accordion 2: Monitoring & Evaluasi -->
                    <div class="pt-1">
                        <button
                            @click="toggleGroup('monitoring')"
                            class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2.5">
                                <i class="bi bi-bar-chart-line text-base text-slate-400"></i>
                                <span>Monitoring & Evaluasi</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.monitoring }"></i>
                        </button>
                        <div v-show="openGroups.monitoring" class="pl-7 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-4.5 my-1">
                            <a
                                href="/indikator-kinerja"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/indikator-kinerja') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-bullseye text-xs"></i>
                                <span>Indikator SPMI (IKU/IKT)</span>
                            </a>
                            <a
                                href="/iku-resmi"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/iku-resmi') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-award text-xs"></i>
                                <span>IKU Kemdiktisaintek</span>
                            </a>
                            <a
                                href="/monitoring"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/monitoring') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-bar-chart-line text-xs"></i>
                                <span>Monitoring IKU/IKT</span>
                            </a>
                            <a
                                href="/evaluasi"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/evaluasi') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-graph-up-arrow text-xs"></i>
                                <span>Evaluasi</span>
                            </a>
                            <a
                                href="/integrasi"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/integrasi') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-diagram-3 text-xs"></i>
                                <span>Integrasi Data Modul</span>
                            </a>
                        </div>
                    </div>

                    <!-- Accordion 3: Audit Mutu Internal -->
                    <div class="pt-1">
                        <button
                            @click="toggleGroup('audit')"
                            class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2.5">
                                <i class="bi bi-clipboard2-check text-base text-slate-400"></i>
                                <span>Audit Mutu Internal</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.audit }"></i>
                        </button>
                        <div v-show="openGroups.audit" class="pl-7 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-4.5 my-1">
                            <a
                                href="/audit"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/audit') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-clipboard2-check text-xs"></i>
                                <span>Pelaksanaan Audit</span>
                            </a>
                            <a
                                href="/tindak-lanjut"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/tindak-lanjut') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-arrow-repeat text-xs"></i>
                                <span>Tindak Lanjut</span>
                            </a>
                            <a
                                href="/rtm"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/rtm') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-people-fill text-xs"></i>
                                <span>Tinjauan Manajemen (RTM)</span>
                            </a>
                        </div>
                    </div>

                    <!-- Accordion 4: Umpan Balik & Laporan -->
                    <div class="pt-1">
                        <button
                            @click="toggleGroup('laporan')"
                            class="w-full flex items-center justify-between px-3.5 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition cursor-pointer"
                        >
                            <div class="flex items-center gap-2.5">
                                <i class="bi bi-chat-square-text text-base text-slate-400"></i>
                                <span>Umpan Balik & Laporan</span>
                            </div>
                            <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': openGroups.laporan }"></i>
                        </button>
                        <div v-show="openGroups.laporan" class="pl-7 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-4.5 my-1">
                            <a
                                href="/laporan"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/laporan') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-file-earmark-bar-graph text-xs"></i>
                                <span>Laporan SPMI</span>
                            </a>
                            <a
                                href="/survei"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/survei') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-clipboard2-data text-xs"></i>
                                <span>Survei & Kuesioner</span>
                            </a>
                            <a
                                href="/kuesioner"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/kuesioner') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-ui-checks text-xs"></i>
                                <span>Manajemen Kuesioner</span>
                            </a>
                            <a
                                href="/kinerja-dosen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-xs transition"
                                :class="isRouteActive('/kinerja-dosen') ? 'text-indigo-600 font-bold bg-indigo-50/70' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                            >
                                <i class="bi bi-person-badge text-xs"></i>
                                <span>Kinerja Dosen (EDOM)</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Sidebar Footer Status Card -->
                <div class="mt-6 p-4 rounded-2xl bg-gradient-to-br from-indigo-900 to-slate-900 text-white shadow-md">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[11px] font-bold text-indigo-200">Siklus PPEPP Aktif</span>
                    </div>
                    <p class="text-[11px] text-slate-300 leading-snug">
                        Sistem Penjaminan Mutu Internal POLKA.
                    </p>
                </div>
            </aside>

            <!-- Mobile Drawer -->
            <div
                v-if="mobileSidebarOpen"
                class="fixed inset-0 z-40 md:hidden bg-slate-900/60 backdrop-blur-xs"
                @click="mobileSidebarOpen = false"
            >
                <div
                    class="w-72 bg-white h-full flex flex-col p-5 shadow-2xl overflow-y-auto"
                    @click.stop
                >
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">P</div>
                            <span class="font-extrabold text-slate-900">ERP-POLKA</span>
                        </div>
                        <button @click="mobileSidebarOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <nav class="space-y-1 flex-1">
                        <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold" :class="isRouteActive('/dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600'">
                            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                        </a>
                        <a href="/siklus-spmi" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold" :class="isRouteActive(['/siklus-spmi', '/ppepp']) ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600'">
                            <i class="bi bi-arrow-repeat"></i><span>Siklus PPEPP</span>
                        </a>
                        <a href="/dokumen" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-folder2"></i><span>Dokumen Mutu</span>
                        </a>
                        <a href="/standar" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-bookmark-check"></i><span>Standar Mutu</span>
                        </a>
                        <a href="/audit" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-clipboard2-check"></i><span>Pelaksanaan Audit</span>
                        </a>
                        <a href="/tindak-lanjut" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-arrow-repeat"></i><span>Tindak Lanjut</span>
                        </a>
                        <a href="/rtm" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-people-fill"></i><span>RTM</span>
                        </a>
                        <a href="/monitoring" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-bar-chart-line"></i><span>Monitoring IKU/IKT</span>
                        </a>
                        <a href="/laporan" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600">
                            <i class="bi bi-file-earmark-bar-graph"></i><span>Laporan</span>
                        </a>
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
