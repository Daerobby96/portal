<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const sidebarOpen = ref(true);
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-800">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Left: Logo & Sidebar Toggle -->
                    <div class="flex items-center gap-3">
                        <button
                            @click="toggleSidebar"
                            class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition focus:outline-none"
                            title="Toggle Sidebar"
                        >
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-200 font-bold text-lg tracking-wider">
                                P
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 text-base tracking-tight">ERP-POLKA</span>
                                <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    Vue 3 + Inertia
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Navigation Links & User Info -->
                    <div class="flex items-center gap-4">
                        <a
                            href="/dashboard"
                            class="text-xs font-medium text-slate-600 hover:text-indigo-600 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition flex items-center gap-1.5"
                        >
                            <i class="bi bi-box-arrow-left"></i>
                            Kembali ke Blade Dashboard
                        </a>

                        <div class="h-6 w-px bg-slate-200"></div>

                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 border border-indigo-200 text-indigo-700 font-bold text-xs flex items-center justify-center">
                                {{ page.props.auth?.user?.name ? page.props.auth.user.name.charAt(0).toUpperCase() : 'U' }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-semibold text-slate-800 leading-tight">
                                    {{ page.props.auth?.user?.name || 'Administrator' }}
                                </p>
                                <p class="text-[10px] text-slate-500">
                                    {{ page.props.auth?.user?.role || 'User' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Wrapper -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside
                v-if="sidebarOpen"
                class="w-64 bg-white border-r border-slate-200 flex flex-col py-4 px-3 shrink-0 transition-all"
            >
                <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Modul Migrasi
                </div>
                <nav class="space-y-1">
                    <a
                        href="/vue-demo"
                        class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold rounded-xl bg-indigo-50 text-indigo-700 transition"
                    >
                        <i class="bi bi-speedometer2 text-base text-indigo-600"></i>
                        <span>Vue 3 POC Demo</span>
                    </a>
                    <a
                        href="/dashboard"
                        class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition"
                    >
                        <i class="bi bi-layout-text-window-reverse text-base text-slate-400"></i>
                        <span>Dashboard (Blade)</span>
                    </a>
                    <a
                        href="/portal"
                        class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 rounded-xl transition"
                    >
                        <i class="bi bi-grid text-base text-slate-400"></i>
                        <span>Portal Launcher</span>
                    </a>
                </nav>

                <div class="mt-auto p-3 bg-gradient-to-br from-slate-900 to-indigo-950 rounded-2xl text-white">
                    <p class="text-xs font-bold text-indigo-300">Inertia Stack Aktif</p>
                    <p class="text-[11px] text-slate-300 mt-1">
                        Sistem siap untuk migrasi bertahap modul SPMI, SDM, dll.
                    </p>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 p-6 sm:p-8 max-w-7xl mx-auto w-full">
                <slot />
            </main>
        </div>
    </div>
</template>
