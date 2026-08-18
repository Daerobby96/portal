<template>
    <AuthenticatedLayout title="HKI & Paten Dosen">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Hak Kekayaan Intelektual (HKI) & Paten</h1>
                    <p class="text-xs text-slate-500 mt-1">Pencatatan Paten, Hak Cipta Karya, Merek, dan Desain Industri hasil karya sivitas akademika.</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        href="/hki/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah HKI / Paten</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total HKI</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Karya Terdaftar</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Granted</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.granted }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Sertifikat Terbit (Granted)</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-purple-600">Paten</span>
                    </div>
                    <div class="text-2xl font-black text-purple-700">{{ stats.paten }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Paten & Paten Sederhana</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari judul HKI, nomor pencatatan, nama dosen..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.jenis_hki"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Jenis HKI</option>
                            <option v-for="j in jenisOptions" :key="j" :value="j">{{ j }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.status"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Status</option>
                            <option v-for="s in statusOptions" :key="s" :value="s">{{ s }}</option>
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
                                <th class="px-5 py-3.5 text-left">Judul HKI & Nomor Pencatatan</th>
                                <th class="px-4 py-3.5 text-left">Inventor / Pemegang Hak</th>
                                <th class="px-4 py-3.5 text-left">Jenis & Tahun</th>
                                <th class="px-4 py-3.5 text-left">Status</th>
                                <th class="px-4 py-3.5 text-left">Sertifikat</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="hkis.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-patch-minus"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada data HKI atau Paten ditemukan</p>
                                </td>
                            </tr>
                            <tr
                                v-for="h in hkis.data"
                                :key="h.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 max-w-sm">
                                    <p class="font-black text-slate-900 leading-snug">{{ h.judul_hki }}</p>
                                    <div class="flex items-center gap-2 mt-1 text-[10px]">
                                        <span v-if="h.nomor_pencatatan" class="font-mono text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                            No: {{ h.nomor_pencatatan }}
                                        </span>
                                        <span v-else class="text-slate-400 italic">No. pencatatan belum ada</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <span class="font-bold text-slate-900 block">{{ h.pegawai_nama || '-' }}</span>
                                    <span v-if="h.pegawai_nip" class="font-mono text-[10px] text-slate-400 block">NIP: {{ h.pegawai_nip }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="jenisHkiBadgeClass(h.jenis_hki)">
                                        {{ h.jenis_hki }}
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-500 block mt-1">Tahun {{ h.tahun_terbit }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="h.status === 'Granted/Sertifikat' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'">
                                        <i v-if="h.status === 'Granted/Sertifikat'" class="bi bi-check-circle-fill mr-1"></i>
                                        <i v-else class="bi bi-hourglass-split mr-1"></i>
                                        <span>{{ h.status }}</span>
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <a
                                        v-if="h.sertifikat_url"
                                        :href="h.sertifikat_url"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] border border-rose-200 transition"
                                    >
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                        <span>Unduh PDF</span>
                                    </a>
                                    <span v-else class="text-[10px] text-slate-400 italic">Belum diunggah</span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/hki/${h.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>
                                        <button
                                            @click="confirmDelete(h)"
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

                <div v-if="hkis.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ hkis.from }}–{{ hkis.to }} dari {{ hkis.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in hkis.links"
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
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Data HKI?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Karya HKI "<span class="font-bold text-slate-800">{{ deleteTarget.judul_hki }}</span>" akan dihapus permanen.
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
    hkis: Object,
    stats: Object,
    jenisOptions: Array,
    statusOptions: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    jenis_hki: props.filters?.jenis_hki || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get('/hki', filters, { preserveState: true, replace: true });
}

const deleteTarget = ref(null);
function confirmDelete(h) {
    deleteTarget.value = h;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/hki/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function jenisHkiBadgeClass(jenis) {
    const map = {
        'Paten': 'bg-purple-50 text-purple-700 border border-purple-200',
        'Paten Sederhana': 'bg-indigo-50 text-indigo-700 border border-indigo-200',
        'Hak Cipta': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Merek': 'bg-amber-50 text-amber-700 border border-amber-200',
        'Desain Industri': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
    };
    return map[jenis] || 'bg-slate-100 text-slate-600';
}
</script>
