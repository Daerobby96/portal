<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioners: Array,
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user?.id);
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <Head title="Survei & Kuesioner Kepuasan" />

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- IF AUTHENTICATED: WRAP IN AUTHENTICATED LAYOUT                     -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <AuthenticatedLayout v-if="isAuthenticated">
        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-chat-square-text"></i>
                        <span>Survei Kepuasan Sivitas</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Daftar Kuesioner & Survei Aktif
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Sampaikan masukan dan evaluasi Anda untuk mendukung continuous quality improvement mutu akademik institusi.
                    </p>
                </div>
            </div>

            <!-- Flash Success / Error -->
            <div v-if="flash.success" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i>
                <span>{{ flash.success }}</span>
            </div>
            <div v-if="flash.error" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
                <span>{{ flash.error }}</span>
            </div>

            <!-- Survey Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="k in kuesioners"
                    :key="k.id"
                    class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between"
                >
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ k.periode?.nama || 'Periode Aktif' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 mb-1.5">{{ k.judul }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            {{ k.deskripsi || 'Silakan isi survei ini dengan jujur dan objektif.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Target: {{ k.target_role || 'Semua' }}</span>
                        <Link
                            :href="`/survei/${k.id}`"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-xs"
                        >
                            Isi Survei &rarr;
                        </Link>
                    </div>
                </div>

                <div v-if="!kuesioners || kuesioners.length === 0" class="col-span-full py-16 text-center text-slate-400 text-xs bg-white rounded-3xl border border-slate-100">
                    Tidak ada survei atau kuesioner aktif yang perlu Anda isi saat ini.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- IF GUEST / PUBLIC USER: STANDALONE PUBLIC SURVEY LIST              -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div v-else class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-600 selection:text-white flex flex-col">
        <!-- Public Minimalist Navbar -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-600 to-slate-900 text-white flex items-center justify-center font-black text-sm">
                        P
                    </div>
                    <span class="font-black text-slate-900 text-sm">PINTAR</span>
                </Link>

                <div class="flex items-center gap-3">
                    <Link href="/" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1">
                        <i class="bi bi-arrow-left"></i>
                        <span>Beranda</span>
                    </Link>
                    <Link href="/login" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs">
                        Masuk Portal
                    </Link>
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full space-y-6">
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-3">
                    <i class="bi bi-chat-square-text"></i>
                    <span>Instrumen Evaluasi Terbuka</span>
                </div>
                <h1 class="text-xl sm:text-3xl font-black tracking-tight text-white">Daftar Kuesioner & Survei Publik Aktif</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed max-w-2xl">
                    Partisipasi Anda sebagai mahasiswa, alumni, dan mitra dalam mengisi kuesioner sangat berharga untuk peningkatan mutu layanan perguruan tinggi.
                </p>
            </div>

            <!-- Flash Success / Error -->
            <div v-if="flash.success" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i>
                <span>{{ flash.success }}</span>
            </div>
            <div v-if="flash.error" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
                <span>{{ flash.error }}</span>
            </div>

            <!-- Survey Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="k in kuesioners"
                    :key="k.id"
                    class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4 hover:shadow-md transition"
                >
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ k.periode?.nama || 'Periode Aktif' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Terbuka
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 mb-1.5">{{ k.judul }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            {{ k.deskripsi || 'Silakan isi survei ini dengan jujur dan objektif.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Bebas Akses (Publik)</span>
                        <Link
                            :href="`/survei/${k.id}`"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5"
                        >
                            <span>Isi Kuesioner</span>
                            <i class="bi bi-arrow-right text-[11px]"></i>
                        </Link>
                    </div>
                </div>

                <div v-if="!kuesioners || kuesioners.length === 0" class="col-span-full py-16 text-center text-slate-400 text-xs bg-white rounded-3xl border border-slate-100">
                    Tidak ada survei atau kuesioner aktif yang tersedia saat ini.
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
            <p>&copy; {{ new Date().getFullYear() }} PINTAR. Sistem Informasi Penjaminan Mutu Internal.</p>
        </footer>
    </div>
</template>
