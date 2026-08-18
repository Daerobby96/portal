<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    modules: {
        type: Array,
        default: () => [],
    },
    appSettings: {
        type: Object,
        default: () => ({}),
    },
});

const searchQuery = ref('');

const filteredModules = computed(() => {
    if (!searchQuery.value.trim()) {
        return props.modules;
    }
    const q = searchQuery.value.toLowerCase();
    return props.modules.filter(m =>
        m.name.toLowerCase().includes(q) ||
        m.desc.toLowerCase().includes(q) ||
        m.tag.toLowerCase().includes(q)
    );
});

const mainModules = computed(() => {
    return filteredModules.value.filter(m => m.category === 'Sistem Utama');
});

const supportingModules = computed(() => {
    return filteredModules.value.filter(m => m.category === 'Modul Pendukung');
});

const logout = () => {
    router.post('/logout');
};

const getColorClasses = (color) => {
    const colors = {
        indigo: 'from-indigo-600 to-indigo-700 text-indigo-600 bg-indigo-50 border-indigo-100 hover:border-indigo-300',
        sky: 'from-sky-500 to-sky-600 text-sky-600 bg-sky-50 border-sky-100 hover:border-sky-300',
        blue: 'from-blue-600 to-blue-700 text-blue-600 bg-blue-50 border-blue-100 hover:border-blue-300',
        slate: 'from-slate-700 to-slate-800 text-slate-700 bg-slate-100 border-slate-200 hover:border-slate-400',
        emerald: 'from-emerald-500 to-emerald-600 text-emerald-600 bg-emerald-50 border-emerald-100 hover:border-emerald-300',
        amber: 'from-amber-500 to-amber-600 text-amber-600 bg-amber-50 border-amber-100 hover:border-amber-300',
        cyan: 'from-cyan-500 to-cyan-600 text-cyan-600 bg-cyan-50 border-cyan-100 hover:border-cyan-300',
        purple: 'from-purple-600 to-indigo-700 text-purple-600 bg-purple-50 border-purple-100 hover:border-purple-300',
        violet: 'from-violet-500 to-violet-600 text-violet-600 bg-violet-50 border-violet-100 hover:border-violet-300',
        pink: 'from-pink-500 to-pink-600 text-pink-600 bg-pink-50 border-pink-100 hover:border-pink-300',
        rose: 'from-rose-500 to-rose-600 text-rose-600 bg-rose-50 border-rose-100 hover:border-rose-300',
        teal: 'from-teal-500 to-teal-600 text-teal-600 bg-teal-50 border-teal-100 hover:border-teal-300',
    };
    return colors[color] || colors.indigo;
};

const getIconGradient = (color) => {
    const gradients = {
        indigo: 'from-indigo-600 to-indigo-700 text-white shadow-indigo-200',
        purple: 'from-purple-600 to-indigo-700 text-white shadow-purple-200',
        sky: 'from-sky-500 to-sky-600 text-white shadow-sky-200',
        blue: 'from-blue-600 to-blue-700 text-white shadow-blue-200',
        slate: 'from-slate-700 to-slate-800 text-white shadow-slate-200',
        emerald: 'from-emerald-500 to-emerald-600 text-white shadow-emerald-200',
        amber: 'from-amber-500 to-amber-600 text-white shadow-amber-200',
        cyan: 'from-cyan-500 to-cyan-600 text-white shadow-cyan-200',
        violet: 'from-violet-500 to-violet-600 text-white shadow-violet-200',
        pink: 'from-pink-500 to-pink-600 text-white shadow-pink-200',
        rose: 'from-rose-500 to-rose-600 text-white shadow-rose-200',
        teal: 'from-teal-500 to-teal-600 text-white shadow-teal-200',
    };
    return gradients[color] || gradients.indigo;
};
</script>

<template>
    <Head title="Portal Modul" />

    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans relative selection:bg-indigo-500 selection:text-white">
        <!-- Subtle Top Ambient Glow -->
        <div class="fixed top-0 inset-x-0 h-96 bg-gradient-to-b from-indigo-100/40 via-transparent to-transparent pointer-events-none -z-0"></div>

        <!-- Top Header Navigation -->
        <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white font-black text-xl shadow-md shadow-indigo-200">
                    P
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 text-base tracking-tight block">
                        {{ appSettings?.appName || 'PINTAR' }}
                    </span>
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                        Portal Layanan Terpadu
                    </span>
                </div>
            </div>

            <!-- User Info & Logout Button -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-xs">
                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">
                        {{ user?.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                    </div>
                    <span class="text-xs font-semibold text-slate-700 max-w-[150px] truncate">{{ user?.name }}</span>
                </div>

                <button
                    @click="logout"
                    class="px-3.5 py-1.5 text-xs font-semibold rounded-full bg-white hover:bg-rose-50 text-slate-600 hover:text-rose-600 border border-slate-200 hover:border-rose-200 transition shadow-xs flex items-center gap-1.5 cursor-pointer"
                    title="Keluar dari sesi"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
            <!-- Hero Title & Live Search Bar -->
            <div class="text-center max-w-2xl mx-auto mb-10">
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-1.5">
                    Selamat Datang, {{ user?.name }}
                </p>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Portal Modul Aplikasi
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">
                    Pilih modul di bawah untuk mulai mengakses fitur dan layanan perguruan tinggi.
                </p>

                <!-- Search Input -->
                <div class="mt-6 relative max-w-md mx-auto">
                    <i class="bi bi-search absolute left-4 top-3 text-slate-400 text-sm pointer-events-none"></i>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari modul (contoh: SPMI, Audit, SDM, Rapat)..."
                        class="w-full pl-11 pr-4 py-2.5 text-xs sm:text-sm rounded-2xl bg-white border border-slate-200 shadow-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition"
                    />
                    <button
                        v-if="searchQuery"
                        @click="searchQuery = ''"
                        class="absolute right-3.5 top-3 text-slate-400 hover:text-slate-600 text-xs"
                    >
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            </div>

            <!-- Section 1: Sistem Utama -->
            <section v-if="mainModules.length > 0" class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Sistem Utama</span>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <a
                        v-for="mod in mainModules"
                        :key="mod.id"
                        :href="mod.url"
                        class="group p-6 rounded-3xl bg-white border border-slate-200 shadow-xs hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition duration-300 flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-gradient-to-tr flex items-center justify-center text-2xl shadow-md"
                                    :class="getIconGradient(mod.color)"
                                >
                                    <i :class="['bi', mod.icon]"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">
                                    {{ mod.tag }}
                                </span>
                            </div>

                            <h2 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition">
                                {{ mod.name }}
                            </h2>
                            <p class="text-xs text-slate-500 leading-relaxed mt-1.5">
                                {{ mod.desc }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-indigo-600">
                            <span>Buka Modul</span>
                            <i class="bi bi-arrow-right group-hover:translate-x-1.5 transition duration-200"></i>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Section 2: Modul Pendukung -->
            <section v-if="supportingModules.length > 0">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Modul Pendukung</span>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <a
                        v-for="mod in supportingModules"
                        :key="mod.id"
                        :href="mod.url"
                        class="group p-5 rounded-3xl bg-white border border-slate-200 shadow-xs hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition duration-300 flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-3.5">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-tr flex items-center justify-center text-xl shadow-md"
                                    :class="getIconGradient(mod.color)"
                                >
                                    <i :class="['bi', mod.icon]"></i>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition">
                                    {{ mod.tag }}
                                </span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition">
                                {{ mod.name }}
                            </h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1">
                                {{ mod.desc }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-slate-600 group-hover:text-indigo-600 transition">
                            <span>Akses Layanan</span>
                            <i class="bi bi-arrow-right group-hover:translate-x-1 transition duration-200"></i>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Empty Search State -->
            <div v-if="filteredModules.length === 0" class="text-center py-16">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="bi bi-search"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-700">Tidak ada modul yang cocok</h4>
                <p class="text-xs text-slate-400 mt-1">Coba kata kunci pencarian yang lain.</p>
                <button
                    @click="searchQuery = ''"
                    class="mt-3 text-xs font-semibold text-indigo-600 hover:text-indigo-700"
                >
                    Tampilkan Semua Modul
                </button>
            </div>
        </main>
    </div>
</template>
