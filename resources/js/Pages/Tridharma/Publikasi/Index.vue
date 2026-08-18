<template>
    <AuthenticatedLayout title="Publikasi Ilmiah Dosen">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Publikasi Ilmiah & Jurnal</h1>
                    <p class="text-xs text-slate-500 mt-1">Pangkalan data artikel ilmiah, jurnal terakreditasi SINTA / Scopus, prosiding, dan buku referensi.</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        href="/publikasi/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Publikasi</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Publikasi</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-purple-600">Internasional</span>
                    </div>
                    <div class="text-2xl font-black text-purple-700">{{ stats.internasional }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Jurnal Internasional / Scopus</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">SINTA</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.sinta }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Terakreditasi SINTA 1-6</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Buku & Monograf</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.buku }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Buku Referensi Dosen</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari judul artikel, penerbit, atau nama dosen..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.jenis"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Jenis Publikasi</option>
                            <option value="Jurnal Nasional">Jurnal Nasional</option>
                            <option value="Jurnal Internasional">Jurnal Internasional</option>
                            <option value="Prosiding">Prosiding Seminar</option>
                            <option value="Buku">Buku / Monograf</option>
                            <option value="HKI">HKI</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tingkat_sinta"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tingkat SINTA</option>
                            <option value="SINTA 1">SINTA 1</option>
                            <option value="SINTA 2">SINTA 2</option>
                            <option value="SINTA 3">SINTA 3</option>
                            <option value="SINTA 4">SINTA 4</option>
                            <option value="SINTA 5">SINTA 5</option>
                            <option value="SINTA 6">SINTA 6</option>
                            <option value="Non SINTA">Non SINTA</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tahun"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tahun</option>
                            <option v-for="t in tahuns" :key="t" :value="t">Tahun {{ t }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                <th class="px-5 py-3.5 text-left">Judul Karya & Tahun</th>
                                <th class="px-4 py-3.5 text-left">Penulis / Dosen</th>
                                <th class="px-4 py-3.5 text-left">Media Publikasi & Akreditasi</th>
                                <th class="px-4 py-3.5 text-left">Jenis</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="publikasis.data.length === 0">
                                <td colspan="5" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-journal-x"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada data publikasi ditemukan</p>
                                </td>
                            </tr>
                            <tr
                                v-for="p in publikasis.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 max-w-sm">
                                    <p class="font-black text-slate-900 leading-snug">{{ p.judul }}</p>
                                    <div class="flex items-center gap-2 mt-1 text-[10px]">
                                        <span class="font-bold text-rose-700 bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200/60 font-mono">
                                            {{ p.tahun }}
                                        </span>
                                        <a
                                            v-if="p.url_tautan"
                                            :href="p.url_tautan"
                                            target="_blank"
                                            class="text-blue-600 font-semibold hover:underline flex items-center gap-1 truncate max-w-[200px]"
                                        >
                                            <i class="bi bi-link-45deg"></i>
                                            <span>Link Artikel / DOI</span>
                                        </a>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <span class="font-bold text-slate-900 block">{{ p.pegawai_nama || '-' }}</span>
                                    <span v-if="p.pegawai_nip" class="font-mono text-[10px] text-slate-400 block">NIP: {{ p.pegawai_nip }}</span>
                                    <span v-if="p.prodi_nama" class="text-[10px] text-slate-500 block mt-0.5">{{ p.prodi_nama }}</span>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <span class="font-bold text-slate-800 block truncate" :title="p.nama_jurnal_penerbit">
                                        {{ p.nama_jurnal_penerbit || '-' }}
                                    </span>
                                    <div class="flex items-center gap-2 mt-0.5 text-[10px]">
                                        <span v-if="p.volume_nomor" class="text-slate-400 font-mono">{{ p.volume_nomor }}</span>
                                        <span v-if="p.tingkat_sinta" class="px-1.5 py-0.2 rounded font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            {{ p.tingkat_sinta }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="jenisBadgeClass(p.jenis)">
                                        {{ p.jenis }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/publikasi/${p.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>
                                        <button
                                            @click="confirmDelete(p)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="publikasis.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ publikasis.from }}–{{ publikasis.to }} dari {{ publikasis.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in publikasis.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-rose-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Publikasi?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Judul karya "<span class="font-bold text-slate-800">{{ deleteTarget.judul }}</span>" akan dihapus permanen.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="deleteTarget = null"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm cursor-pointer"
                            >
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    publikasis: Object,
    stats: Object,
    prodis: Array,
    tahuns: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    jenis: props.filters?.jenis || '',
    tingkat_sinta: props.filters?.tingkat_sinta || '',
    prodi: props.filters?.prodi || '',
    tahun: props.filters?.tahun || '',
});

function applyFilters() {
    router.get('/publikasi', filters, { preserveState: true, replace: true });
}

const deleteTarget = ref(null);
function confirmDelete(p) {
    deleteTarget.value = p;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/publikasi/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function jenisBadgeClass(jenis) {
    const map = {
        'Jurnal Internasional': 'bg-purple-50 text-purple-700 border border-purple-200',
        'Jurnal Nasional': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Prosiding': 'bg-amber-50 text-amber-700 border border-amber-200',
        'Buku': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'HKI': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[jenis] || 'bg-slate-100 text-slate-600';
}
</script>
