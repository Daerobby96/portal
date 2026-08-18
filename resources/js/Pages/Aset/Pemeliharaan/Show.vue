<template>
    <AuthenticatedLayout :title="`Detail Servis - ${pemeliharaan.aset_nama}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/pemeliharaan"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                {{ pemeliharaan.jenis }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="hasilBadgeClass(pemeliharaan.hasil)">
                                {{ pemeliharaan.hasil }}
                            </span>
                        </div>
                        <h1 class="text-xl font-black text-slate-900 mt-1">Detail Tiket Pemeliharaan</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        :href="`/pemeliharaan/${pemeliharaan.id}/edit`"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200"
                    >
                        <i class="bi bi-pencil-fill text-slate-500"></i>
                        <span>Edit</span>
                    </Link>

                    <button
                        @click="showDeleteModal = true"
                        type="button"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200 cursor-pointer"
                        title="Hapus"
                    >
                        <i class="bi bi-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                <!-- Target Aset Info -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Aset yang Diservis</span>
                        <h3 class="font-black text-slate-900 text-sm mt-0.5">{{ pemeliharaan.aset_nama }}</h3>
                        <span class="font-mono text-xs text-emerald-700 font-bold">{{ pemeliharaan.aset_kode }}</span>
                    </div>
                    <Link :href="`/aset/${pemeliharaan.aset_id}`" class="text-xs font-bold text-emerald-700 hover:underline">
                        Lihat Data Aset <i class="bi bi-arrow-right"></i>
                    </Link>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Tanggal Servis:</span>
                        <span class="font-bold text-slate-800">{{ pemeliharaan.tanggal_pemeliharaan }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Petugas Pencatat:</span>
                        <span class="font-bold text-slate-800">{{ pemeliharaan.petugas_nama }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Vendor / Teknisi:</span>
                        <span class="font-bold text-slate-800">{{ pemeliharaan.vendor || 'Internal' }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Biaya Servis:</span>
                        <span class="font-bold text-emerald-700">{{ formatRupiah(pemeliharaan.biaya) }}</span>
                    </div>
                    <div v-if="pemeliharaan.tanggal_berikutnya" class="py-2 border-b border-slate-100 flex justify-between sm:col-span-2">
                        <span class="text-slate-400 font-medium">Jadwal Servis Berikutnya:</span>
                        <span class="font-bold text-amber-700">{{ pemeliharaan.tanggal_berikutnya }}</span>
                    </div>
                </div>

                <!-- Deskripsi Kegiatan -->
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Uraian Kegiatan Pemeliharaan</span>
                    <p class="text-xs text-slate-700 leading-relaxed font-semibold bg-slate-50 p-3.5 rounded-2xl border border-slate-100 whitespace-pre-line">
                        {{ pemeliharaan.deskripsi_kegiatan }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <!-- Temuan -->
                    <div v-if="pemeliharaan.temuan">
                        <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Temuan Masalah</span>
                        <p class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 whitespace-pre-line">
                            {{ pemeliharaan.temuan }}
                        </p>
                    </div>

                    <!-- Tindakan -->
                    <div v-if="pemeliharaan.tindakan">
                        <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Tindakan Perbaikan</span>
                        <p class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 whitespace-pre-line">
                            {{ pemeliharaan.tindakan }}
                        </p>
                    </div>
                </div>

                <!-- Bukti Foto -->
                <div v-if="pemeliharaan.bukti_foto">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-2">Foto Bukti / Nota Pengerjaan</span>
                    <div class="max-w-md rounded-2xl overflow-hidden border border-slate-200 bg-slate-100">
                        <img :src="pemeliharaan.bukti_foto" alt="Bukti servis" class="w-full object-cover" />
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <Teleport to="body">
                <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Catatan Servis?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data pemeliharaan ini akan dihapus permanen dari sistem.
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
    pemeliharaan: Object,
});

const showDeleteModal = ref(false);

function proceedDelete() {
    router.delete(`/pemeliharaan/${props.pemeliharaan.id}`);
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
