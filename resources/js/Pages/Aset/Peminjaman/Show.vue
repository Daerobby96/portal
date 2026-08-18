<template>
    <AuthenticatedLayout :title="`Peminjaman - ${peminjaman.aset_nama}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/peminjaman"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(peminjaman.status)">
                                {{ peminjaman.status }}
                            </span>
                            <span v-if="peminjaman.is_terlambat" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Terlambat
                            </span>
                        </div>
                        <h1 class="text-xl font-black text-slate-900 mt-1">Detail Peminjaman Aset</h1>
                    </div>
                </div>

                <!-- Action buttons for staff/admin -->
                <div v-if="canApprove" class="flex items-center gap-2">
                    <template v-if="peminjaman.status === 'pending'">
                        <button
                            @click="showApproveModal = true"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                        >
                            <i class="bi bi-check2-circle"></i>
                            <span>Setujui</span>
                        </button>
                        <button
                            @click="showRejectModal = true"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                        >
                            <i class="bi bi-x-circle"></i>
                            <span>Tolak</span>
                        </button>
                    </template>

                    <template v-if="peminjaman.status === 'disetujui' || peminjaman.status === 'dipinjam'">
                        <button
                            @click="showReturnModal = true"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                        >
                            <i class="bi bi-box-arrow-in-left"></i>
                            <span>Proses Pengembalian Aset</span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                <!-- Target Aset Info -->
                <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <span class="font-mono text-[10px] text-blue-700 font-bold uppercase">{{ peminjaman.aset_kode }}</span>
                            <h3 class="font-black text-slate-900 text-sm">{{ peminjaman.aset_nama }}</h3>
                            <span class="text-[11px] text-slate-500">Lokasi: {{ peminjaman.lokasi_aset }}</span>
                        </div>
                    </div>
                    <Link :href="`/aset/${peminjaman.aset_id}`" class="text-xs font-bold text-blue-700 hover:underline">
                        Lihat Profil Aset <i class="bi bi-arrow-right"></i>
                    </Link>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Nama Pemohon:</span>
                        <span class="font-bold text-slate-800">{{ peminjaman.peminjam_nama }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Tanggal Pengajuan:</span>
                        <span class="font-bold text-slate-800">{{ peminjaman.created_at }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Mulai Pinjam:</span>
                        <span class="font-bold text-slate-800">{{ peminjaman.tanggal_pinjam }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Rencana Selesai:</span>
                        <span class="font-bold text-slate-800">{{ peminjaman.tanggal_kembali_rencana }}</span>
                    </div>
                    <div v-if="peminjaman.tanggal_kembali_aktual" class="py-2 border-b border-slate-100 flex justify-between sm:col-span-2">
                        <span class="text-slate-400 font-medium">Tanggal Pengembalian Aktual:</span>
                        <span class="font-bold text-emerald-700">{{ peminjaman.tanggal_kembali_aktual }}</span>
                    </div>
                </div>

                <!-- Keperluan -->
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Keperluan Peminjaman</span>
                    <p class="text-xs text-slate-800 font-semibold bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        {{ peminjaman.keperluan }}
                    </p>
                </div>

                <!-- Catatan Pemohon -->
                <div v-if="peminjaman.catatan_peminjam">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Catatan Tambahan Pemohon</span>
                    <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line">
                        {{ peminjaman.catatan_peminjam }}
                    </p>
                </div>

                <!-- Approval Section Info -->
                <div v-if="peminjaman.approver_nama || peminjaman.catatan_approval || peminjaman.kondisi_saat_kembali" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                    <span class="font-bold text-slate-900 block text-xs border-b border-slate-200 pb-1.5">Catatan Verifikasi & Kondisi</span>
                    <div v-if="peminjaman.approver_nama" class="flex justify-between">
                        <span class="text-slate-500">Diverifikasi oleh:</span>
                        <span class="font-bold text-slate-800">{{ peminjaman.approver_nama }}</span>
                    </div>
                    <div v-if="peminjaman.catatan_approval" class="flex justify-between">
                        <span class="text-slate-500">Catatan Petugas:</span>
                        <span class="font-semibold text-slate-800">{{ peminjaman.catatan_approval }}</span>
                    </div>
                    <div v-if="peminjaman.kondisi_saat_pinjam" class="flex justify-between">
                        <span class="text-slate-500">Kondisi saat diserahkan:</span>
                        <span class="font-semibold text-slate-800">{{ peminjaman.kondisi_saat_pinjam }}</span>
                    </div>
                    <div v-if="peminjaman.kondisi_saat_kembali" class="flex justify-between">
                        <span class="text-slate-500">Kondisi saat kembali:</span>
                        <span class="font-semibold text-slate-800">{{ peminjaman.kondisi_saat_kembali }}</span>
                    </div>
                    <div v-if="peminjaman.denda > 0" class="flex justify-between font-bold text-rose-600 pt-1 border-t border-slate-200">
                        <span>Denda Keterlambatan / Kerusakan:</span>
                        <span>{{ formatRupiah(peminjaman.denda) }}</span>
                    </div>
                </div>
            </div>

            <!-- Approve Modal -->
            <Teleport to="body">
                <div v-if="showApproveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Setujui Peminjaman Aset</h3>
                            <button @click="showApproveModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitApprove" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kondisi Aset Saat Diserahkan</label>
                                <input
                                    v-model="approveForm.kondisi_saat_pinjam"
                                    type="text"
                                    placeholder="Contoh: Baik, lengkap dengan charger dan tas"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Approval (Opsional)</label>
                                <textarea
                                    v-model="approveForm.catatan_approval"
                                    rows="2"
                                    placeholder="Instruksi pengambilan barang di pos sarpras..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showApproveModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <i class="bi bi-check2-circle"></i>
                                    <span>Konfirmasi Persetujuan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Reject Modal -->
            <Teleport to="body">
                <div v-if="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Tolak Permohonan Peminjaman</h3>
                            <button @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitReject" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                <textarea
                                    v-model="rejectForm.catatan_approval"
                                    required
                                    rows="3"
                                    placeholder="Jelaskan alasan penolakan (misal: barang sedang dipakai untuk kegiatan institusi)..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none resize-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showRejectModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <i class="bi bi-x-circle"></i>
                                    <span>Konfirmasi Penolakan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Return Modal -->
            <Teleport to="body">
                <div v-if="showReturnModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Proses Pengembalian Aset</h3>
                            <button @click="showReturnModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitReturn" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kondisi Saat Dikembalikan <span class="text-rose-500">*</span></label>
                                <input
                                    v-model="returnForm.kondisi_saat_kembali"
                                    type="text"
                                    required
                                    placeholder="Contoh: Baik, lengkap, tidak ada cacat"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Denda / Biaya Ganti Rugi (Rp)</label>
                                <input
                                    v-model="returnForm.denda"
                                    type="number"
                                    min="0"
                                    placeholder="0 jika tidak ada denda"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                                />
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showReturnModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <i class="bi bi-check2-circle"></i>
                                    <span>Simpan Pengembalian</span>
                                </button>
                            </div>
                        </form>
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
    peminjaman: Object,
    canApprove: Boolean,
});

const showApproveModal = ref(false);
const showRejectModal = ref(false);
const showReturnModal = ref(false);

const approveForm = reactive({
    kondisi_saat_pinjam: 'Baik',
    catatan_approval: '',
});

const rejectForm = reactive({
    catatan_approval: '',
});

const returnForm = reactive({
    kondisi_saat_kembali: 'Baik',
    denda: 0,
});

function submitApprove() {
    router.put(`/peminjaman/${props.peminjaman.id}/approve`, approveForm, {
        onSuccess: () => {
            showApproveModal.value = false;
        },
    });
}

function submitReject() {
    router.put(`/peminjaman/${props.peminjaman.id}/reject`, rejectForm, {
        onSuccess: () => {
            showRejectModal.value = false;
        },
    });
}

function submitReturn() {
    router.put(`/peminjaman/${props.peminjaman.id}/return`, returnForm, {
        onSuccess: () => {
            showReturnModal.value = false;
        },
    });
}

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
}

function statusBadgeClass(status) {
    const map = {
        'pending': 'bg-amber-50 text-amber-700 border border-amber-200',
        'disetujui': 'bg-blue-50 text-blue-700 border border-blue-200',
        'dipinjam': 'bg-indigo-50 text-indigo-700 border border-indigo-200',
        'dikembalikan': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'ditolak': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
