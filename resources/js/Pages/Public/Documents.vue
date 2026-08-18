<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    documents: Object,
    kategoris: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({ search: '', kategori_id: '' }),
    },
});

const search = ref(props.filters.search || '');
const selectedKategori = ref(props.filters.kategori_id || '');
let searchTimeout = null;

const applyFilters = () => {
    router.get('/documents', {
        search: search.value || undefined,
        kategori_id: selectedKategori.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

watch(selectedKategori, () => {
    applyFilters();
});
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-600 selection:text-white flex flex-col">
        <Head title="Repositori Dokumen Mutu Publik" />

        <!-- Public Navbar Header -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
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
                            Repositori Dokumen Mutu Publik
                        </span>
                    </div>
                </Link>

                <div class="flex items-center gap-3">
                    <Link href="/" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition flex items-center gap-1.5">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Beranda</span>
                    </Link>

                    <Link
                        href="/login"
                        class="px-4 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs"
                    >
                        <span>Masuk Portal</span>
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Banner -->
        <section class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-md">
                    <i class="bi bi-folder2-open"></i>
                    <span>Katalog Dokumen Terbuka</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight">
                    Repositori Dokumen & Standar SPMI
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Akses dan unduh kebijakan mutu, manual SPMI, standar nasional pendidikan tinggi, standar operasional prosedur (SOP), dan instrumen mutu institusi yang telah disahkan.
                </p>
            </div>
        </section>

        <!-- Filters & Search Bar -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
            <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative w-full md:w-96">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari judul dokumen atau kode..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none"
                    />
                </div>

                <!-- Total Count -->
                <div class="text-xs font-bold text-slate-500 self-end md:self-center">
                    Menampilkan <span class="text-indigo-600 font-extrabold">{{ documents.total || 0 }}</span> dokumen mutu resmi
                </div>
            </div>

            <!-- Documents Grid -->
            <div v-if="documents.data && documents.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="doc in documents.data"
                    :key="doc.id"
                    class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs hover:shadow-md transition duration-200 flex flex-col justify-between space-y-4 group"
                >
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ doc.kode_dokumen || 'DOK-SPMI' }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">
                                Versi {{ doc.versi || '1.0' }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition leading-snug line-clamp-2">
                                {{ doc.judul }}
                            </h3>
                            <p v-if="doc.keterangan" class="text-xs text-slate-500 line-clamp-2 mt-1">
                                {{ doc.keterangan }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 font-medium">
                            <span><i class="bi bi-building mr-1"></i>{{ doc.unit_pemilik || 'Lembaga Penjaminan Mutu' }}</span>
                            <span v-if="doc.tanggal_terbit">{{ new Date(doc.tanggal_terbit).toLocaleDateString('id-ID', { year: 'numeric', month: 'short' }) }}</span>
                        </div>
                    </div>

                    <a
                        :href="doc.file_path ? `/storage/${doc.file_path}` : `/dokumen/${doc.id}/download`"
                        target="_blank"
                        class="w-full py-2.5 rounded-2xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-bold transition flex items-center justify-center gap-2 group-hover:shadow-md group-hover:shadow-indigo-600/20"
                    >
                        <i class="bi bi-download"></i>
                        <span>Unduh Dokumen PDF</span>
                    </a>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center text-slate-400 space-y-3">
                <i class="bi bi-folder-x text-4xl text-slate-300 block"></i>
                <h3 class="text-sm font-bold text-slate-700">Tidak ada dokumen ditemukan</h3>
                <p class="text-xs text-slate-400">Coba kata kunci lain atau bersihkan kotak pencarian.</p>
            </div>

            <!-- Pagination -->
            <div v-if="documents.links && documents.links.length > 3" class="flex items-center justify-center gap-1.5 pt-4">
                <Link
                    v-for="(link, idx) in documents.links"
                    :key="idx"
                    :href="link.url || '#'"
                    v-html="link.label"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                    :class="[
                        link.active ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50',
                        !link.url ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
                    ]"
                />
            </div>
        </main>

        <!-- Public Footer -->
        <footer class="bg-slate-900 text-white mt-auto py-6 border-t border-slate-800 text-center text-xs text-slate-400">
            <p>&copy; {{ new Date().getFullYear() }} PINTAR. Repositori Dokumen Mutu Publik.</p>
        </footer>
    </div>
</template>
