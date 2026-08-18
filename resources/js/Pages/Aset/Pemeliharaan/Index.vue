<template>
    <AuthenticatedLayout title="Pemeliharaan & Servis Aset">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/aset"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Pemeliharaan & Servis</h1>
                        <p class="text-xs text-slate-500 mt-1">Pencatatan perbaikan berkala, inspeksi teknis, dan kalibrasi sarana prasarana.</p>
                    </div>
                </div>

                <Link
                    href="/aset"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                >
                    <i class="bi bi-search"></i>
                    <span>Pilih Aset untuk Diservis</span>
                </Link>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-tools"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Pemeliharaan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Bulan Ini</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.bulan_ini }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Servis Bulan Ini</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-wrench-adjustable"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Perlu Perbaikan</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.perlu_perbaikan }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Tindak Lanjut Servis</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-rose-600">Ganti Unit</span>
                    </div>
                    <div class="text-2xl font-black text-rose-700">{{ stats.perlu_penggantian }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Perlu Penggantian</div>
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
                                placeholder="Cari nama aset / kode..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.jenis"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Jenis Servis</option>
                            <option value="preventif">Preventif (Rutin)</option>
                            <option value="korektif">Korektif (Perbaikan Kerusakan)</option>
                            <option value="kalibrasi">Kalibrasi Alat</option>
                            <option value="inspeksi">Inspeksi Kelayakan</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.hasil"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Hasil Servis</option>
                            <option value="baik">Baik (Selesai)</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan Lanjutan</option>
                            <option value="perlu_penggantian">Perlu Penggantian Unit</option>
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
                                <th class="px-5 py-3.5 text-left">Aset</th>
                                <th class="px-4 py-3.5 text-left">Tanggal & Jenis</th>
                                <th class="px-4 py-3.5 text-left">Deskripsi & Temuan</th>
                                <th class="px-4 py-3.5 text-left">Hasil</th>
                                <th class="px-4 py-3.5 text-left">Biaya & Vendor</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="pemeliharaans.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-tools"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Tidak ada riwayat pemeliharaan ditemukan</p>
                                </td>
                            </tr>
                            <tr
                                v-for="p in pemeliharaans.data"
                                :key="p.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 max-w-xs">
                                    <Link :href="`/aset/${p.aset_id}`" class="font-black text-slate-900 hover:text-emerald-600 transition block truncate">
                                        {{ p.aset_nama }}
                                    </Link>
                                    <span class="font-mono text-[10px] text-slate-400 font-semibold">{{ p.aset_kode }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block">{{ p.tanggal_pemeliharaan }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-slate-100 text-slate-600 border border-slate-200 inline-block mt-0.5">
                                        {{ p.jenis }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 max-w-sm">
                                    <p class="font-semibold text-slate-800 truncate" :title="p.deskripsi_kegiatan">{{ p.deskripsi_kegiatan }}</p>
                                    <span v-if="p.temuan" class="text-[10px] text-slate-400 block truncate">Temuan: {{ p.temuan }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="hasilBadgeClass(p.hasil)">
                                        {{ p.hasil }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-emerald-700 block">{{ formatRupiah(p.biaya) }}</span>
                                    <span class="text-[10px] text-slate-400">{{ p.vendor || 'Internal' }}</span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a
                                            v-if="p.bukti_foto"
                                            :href="p.bukti_foto"
                                            target="_blank"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                            title="Lihat Foto Bukti"
                                        >
                                            <i class="bi bi-image text-sm"></i>
                                        </a>

                                        <Link
                                            :href="`/pemeliharaan/${p.id}`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition"
                                            title="Detail"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </Link>

                                        <Link
                                            :href="`/pemeliharaan/${p.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="pemeliharaans.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ pemeliharaans.from }}–{{ pemeliharaans.to }} dari {{ pemeliharaans.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in pemeliharaans.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-emerald-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
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
    pemeliharaans: Object,
    stats: Object,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search || '',
    jenis: props.filters?.jenis || '',
    hasil: props.filters?.hasil || '',
});

function applyFilters() {
    router.get('/pemeliharaan', filters, { preserveState: true, replace: true });
}

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
}

function hasilBadgeClass(hasil) {
    const map = {
        'baik': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'perlu_perbaikan': 'bg-amber-50 text-amber-700 border border-amber-200',
        'perlu_penggantian': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[hasil] || 'bg-slate-100 text-slate-600';
}
</script>
