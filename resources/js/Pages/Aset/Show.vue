<template>
    <AuthenticatedLayout :title="`Aset - ${aset.nama_aset}`">
        <div class="space-y-6">
            <!-- Top Bar -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/aset"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-black text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60 uppercase">
                                {{ aset.kode_aset }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="kondisiBadgeClass(aset.kondisi)">
                                {{ formatKondisi(aset.kondisi) }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(aset.status)">
                                {{ formatStatus(aset.status) }}
                            </span>
                        </div>
                        <h1 class="text-xl font-black text-slate-900 mt-1">{{ aset.nama_aset }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        :href="`/aset/${aset.id}/pemeliharaan`"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-xl text-xs font-bold transition border border-amber-200"
                    >
                        <i class="bi bi-tools text-amber-600"></i>
                        <span>Catat Pemeliharaan</span>
                    </Link>

                    <Link
                        :href="`/aset/${aset.id}/edit`"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200"
                    >
                        <i class="bi bi-pencil-fill text-slate-500"></i>
                        <span>Edit</span>
                    </Link>

                    <button
                        @click="showDeleteModal = true"
                        type="button"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200 cursor-pointer"
                        title="Hapus Aset"
                    >
                        <i class="bi bi-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Main 2-Column Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Foto & Kategori Card (Span 1) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4">
                        <div class="w-full aspect-video rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center">
                            <img v-if="aset.foto" :src="aset.foto" :alt="aset.nama_aset" class="w-full h-full object-cover" />
                            <div v-else class="text-slate-300 text-5xl">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">Kategori:</span>
                                <span class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="bi" :class="aset.kategori_icon"></i>
                                    {{ aset.kategori_nama || '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">Unit / Prodi:</span>
                                <span class="font-bold text-slate-800">{{ aset.prodi_nama }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">Penanggung Jawab:</span>
                                <span class="font-bold text-slate-800">{{ aset.penanggung_jawab || '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Spesifikasi, Lokasi & Finansial (Span 2) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs space-y-6">
                        <h2 class="font-black text-slate-900 text-sm pb-3 border-b border-slate-100">Informasi Lengkap Aset</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Merk / Brand</span>
                                <span class="font-bold text-slate-800">{{ aset.merk || '-' }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Tipe / Model</span>
                                <span class="font-bold text-slate-800">{{ aset.tipe || '-' }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Nomor Seri</span>
                                <span class="font-mono font-bold text-slate-800">{{ aset.nomor_seri || '-' }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Lokasi Gedung</span>
                                <span class="font-bold text-slate-800">{{ aset.lokasi }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Ruangan</span>
                                <span class="font-bold text-slate-800">{{ aset.ruangan || '-' }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Tanggal Perolehan</span>
                                <span class="font-bold text-slate-800">{{ aset.tanggal_perolehan || '-' }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Sumber Dana</span>
                                <span class="font-bold text-slate-800">{{ aset.sumber_perolehan || '-' }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Harga Perolehan</span>
                                <span class="font-bold text-emerald-700">{{ formatRupiah(aset.harga_perolehan) }}</span>
                            </div>
                            <div class="py-2 border-b border-slate-50 flex justify-between">
                                <span class="text-slate-400 font-medium">Umur Ekonomis</span>
                                <span class="font-bold text-slate-800">{{ aset.umur_ekonomis ? `${aset.umur_ekonomis} Tahun` : '-' }}</span>
                            </div>
                        </div>

                        <!-- Spesifikasi Detail -->
                        <div v-if="aset.spesifikasi">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Spesifikasi Teknis</span>
                            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ aset.spesifikasi }}
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div v-if="aset.keterangan">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Catatan Tambahan</span>
                            <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line">
                                {{ aset.keterangan }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Logs Section: Pemeliharaan & Peminjaman -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="flex items-center gap-2 p-3 border-b border-slate-100 bg-slate-50/50">
                    <button
                        @click="activeTab = 'pemeliharaan'"
                        type="button"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer"
                        :class="activeTab === 'pemeliharaan' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-500 hover:text-slate-800'"
                    >
                        <i class="bi bi-tools text-amber-500"></i>
                        <span>Riwayat Pemeliharaan & Servis ({{ pemeliharaans.length }})</span>
                    </button>

                    <button
                        @click="activeTab = 'peminjaman'"
                        type="button"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer"
                        :class="activeTab === 'peminjaman' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-500 hover:text-slate-800'"
                    >
                        <i class="bi bi-arrow-left-right text-blue-500"></i>
                        <span>Riwayat Peminjaman ({{ peminjamans.length }})</span>
                    </button>
                </div>

                <!-- Content Tab 1: Pemeliharaan -->
                <div v-if="activeTab === 'pemeliharaan'" class="p-6">
                    <div v-if="pemeliharaans.length === 0" class="text-center py-10 text-slate-400">
                        <i class="bi bi-shield-check text-4xl d-block mb-2 text-emerald-500/60"></i>
                        <p class="font-bold text-slate-600 text-xs">Belum ada riwayat pemeliharaan</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Aset belum pernah diservis atau dilakukan perbaikan.</p>
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="p in pemeliharaans"
                            :key="p.id"
                            class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition space-y-2 text-xs"
                        >
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ p.jenis }}
                                    </span>
                                    <span class="font-bold text-slate-800">{{ p.tanggal_pemeliharaan }}</span>
                                    <span class="text-slate-400">• Petugas: {{ p.petugas_nama }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="hasilBadgeClass(p.hasil)">
                                    {{ p.hasil }}
                                </span>
                            </div>

                            <p class="text-slate-700 leading-relaxed font-semibold">{{ p.deskripsi_kegiatan }}</p>

                            <div v-if="p.temuan || p.tindakan" class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 text-[11px]">
                                <div v-if="p.temuan" class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="font-bold text-slate-500 block text-[10px]">Temuan Masalah:</span>
                                    <span class="text-slate-700">{{ p.temuan }}</span>
                                </div>
                                <div v-if="p.tindakan" class="p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                                    <span class="font-bold text-slate-500 block text-[10px]">Tindakan Perbaikan:</span>
                                    <span class="text-slate-700">{{ p.tindakan }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1">
                                <span v-if="p.vendor">Vendor: <b>{{ p.vendor }}</b></span>
                                <span v-if="p.biaya" class="font-bold text-emerald-700">Biaya: {{ formatRupiah(p.biaya) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Tab 2: Peminjaman -->
                <div v-if="activeTab === 'peminjaman'" class="p-6">
                    <div v-if="peminjamans.length === 0" class="text-center py-10 text-slate-400">
                        <i class="bi bi-box2 text-4xl d-block mb-2 text-slate-300"></i>
                        <p class="font-bold text-slate-600 text-xs">Belum ada riwayat peminjaman</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Aset ini belum pernah dipinjam oleh civitas akademika.</p>
                    </div>

                    <div v-else class="divide-y divide-slate-100 text-xs">
                        <div
                            v-for="pm in peminjamans"
                            :key="pm.id"
                            class="py-3 flex items-center justify-between gap-4"
                        >
                            <div>
                                <span class="font-bold text-slate-900 block">{{ pm.peminjam_nama }}</span>
                                <span class="text-[11px] text-slate-500">{{ pm.keperluan }}</span>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider block mb-0.5" :class="pm.status === 'dikembalikan' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200'">
                                    {{ pm.status }}
                                </span>
                                <span class="text-[10px] text-slate-400">{{ pm.tanggal_pinjam }} s/d {{ pm.tanggal_kembali_rencana }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Aset?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data inventaris "<span class="font-bold text-slate-800">{{ aset.nama_aset }}</span>" beserta riwayat servisnya akan dihapus.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showDeleteModal = false"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm"
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
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    aset: Object,
    pemeliharaans: Array,
    peminjamans: Array,
});

const activeTab = ref('pemeliharaan');
const showDeleteModal = ref(false);

function proceedDelete() {
    router.delete(`/aset/${props.aset.id}`);
}

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
}

function formatKondisi(kondisi) {
    const map = {
        'baik': 'Baik',
        'rusak_ringan': 'Rusak Ringan',
        'rusak_berat': 'Rusak Berat',
        'hilang': 'Hilang',
    };
    return map[kondisi] || kondisi;
}

function formatStatus(status) {
    const map = {
        'aktif': 'Aktif',
        'non_aktif': 'Non Aktif',
        'dalam_perbaikan': 'Perbaikan',
        'dihapuskan': 'Dihapuskan',
    };
    return map[status] || status;
}

function kondisiBadgeClass(kondisi) {
    const map = {
        'baik': 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
        'rusak_ringan': 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'rusak_berat': 'bg-rose-50 text-rose-700 border border-rose-200/60',
        'hilang': 'bg-slate-100 text-slate-600 border border-slate-200',
    };
    return map[kondisi] || 'bg-slate-100 text-slate-600';
}

function statusBadgeClass(status) {
    const map = {
        'aktif': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'non_aktif': 'bg-slate-100 text-slate-600 border border-slate-200',
        'dalam_perbaikan': 'bg-amber-50 text-amber-700 border border-amber-200',
        'dihapuskan': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
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
