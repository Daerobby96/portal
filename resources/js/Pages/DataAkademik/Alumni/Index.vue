<template>
    <AuthenticatedLayout title="Data Alumni Akademik">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rekap Data Kelulusan Alumni</h1>
                    <p class="text-xs text-slate-500 mt-1">Daftar rekam akademik mahasiswa yang telah menyelesaikan studi (Lulus) di Politeknik.</p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        href="/tracer-study"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-emerald-200 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-person-check-fill text-emerald-600"></i>
                        <span>Buka Tracer Study</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Total Lulusan</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Alumni Terdata</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Lulus Tepat Waktu</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.tepat_waktu_persen }}%</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">{{ stats.tepat_waktu }} Orang (<= 48 Bln)</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="bi bi-stars"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-indigo-600">Rerata IPK</span>
                    </div>
                    <div class="text-2xl font-black text-indigo-700">{{ stats.avg_ipk }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Skala 4.00</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Masa Studi</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.avg_studi }} <span class="text-xs font-bold text-amber-600">Bulan</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Rerata Durasi Studi</div>
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
                                placeholder="Cari nama alumni atau NIM..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.prodi"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Program Studi</option>
                            <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.angkatan"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Angkatan</option>
                            <option v-for="a in angkatans" :key="a" :value="a">Angkatan {{ a }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tahun_lulus"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-blue-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tahun Kelulusan</option>
                            <option v-for="th in tahunLulus" :key="th" :value="th">Lulus Tahun {{ th }}</option>
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
                                <th class="px-5 py-3.5 text-left">NIM & Nama Alumni</th>
                                <th class="px-4 py-3.5 text-left">Program Studi</th>
                                <th class="px-4 py-3.5 text-left">Angkatan / Kelulusan</th>
                                <th class="px-4 py-3.5 text-left">IPK Akhir</th>
                                <th class="px-4 py-3.5 text-left">Masa Studi</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="alumnis.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Belum ada data alumni lulusan</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Status mahasiswa lulus akan otomatis terdata di sini</p>
                                </td>
                            </tr>
                            <tr
                                v-for="a in alumnis.data"
                                :key="a.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <Link :href="`/mahasiswa/${a.id}`" class="font-black text-slate-900 hover:text-blue-600 transition block">
                                        {{ a.nama }}
                                    </Link>
                                    <span class="font-mono text-[10px] text-slate-400 block">NIM: {{ a.nim }} · {{ a.jenis_kelamin === 'L' ? 'Laki-laki' : (a.jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800">{{ a.prodi_nama || '-' }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block font-mono">Angkatan {{ a.angkatan }}</span>
                                    <span class="text-[10px] text-emerald-700 block font-semibold">
                                        Lulus: {{ a.tanggal_lulus || '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-black text-indigo-700 block font-mono">{{ a.ipk !== null ? Number(a.ipk).toFixed(2) : '-' }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span v-if="a.masa_studi_bulan" class="px-2.5 py-1 rounded-full text-[10px] font-bold font-mono" :class="a.masa_studi_bulan <= 48 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'">
                                        {{ a.masa_studi_bulan }} Bulan
                                    </span>
                                    <span v-else class="text-slate-400">-</span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <Link
                                        :href="`/mahasiswa/${a.id}`"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition font-bold"
                                    >
                                        <i class="bi bi-eye text-xs"></i>
                                        <span>Profil</span>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="alumnis.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ alumnis.from }}–{{ alumnis.to }} dari {{ alumnis.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in alumnis.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    alumnis: Object,
    stats: Object,
    prodis: Array,
    angkatans: Array,
    tahunLulus: Array,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    prodi: props.filters?.prodi || '',
    angkatan: props.filters?.angkatan || '',
    tahun_lulus: props.filters?.tahun_lulus || '',
});

function applyFilters() {
    router.get('/alumni', filters, { preserveState: true, replace: true });
}
</script>
