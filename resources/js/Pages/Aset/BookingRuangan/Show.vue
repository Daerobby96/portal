<template>
    <AuthenticatedLayout :title="`Booking - ${booking.ruangan_nama}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/booking-ruangan"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(booking.status)">
                                {{ booking.status }}
                            </span>
                        </div>
                        <h1 class="text-xl font-black text-slate-900 mt-1">Detail Reservasi Ruangan</h1>
                    </div>
                </div>

                <!-- Approval Actions for admin/kaprodi -->
                <div class="flex items-center gap-2">
                    <template v-if="booking.status === 'pending' && canApprove">
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

                    <button
                        v-if="booking.status === 'pending'"
                        @click="showCancelModal = true"
                        type="button"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200 cursor-pointer"
                        title="Batalkan Booking"
                    >
                        <i class="bi bi-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                <!-- Ruangan Info Card -->
                <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100 flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <div>
                            <span class="font-mono text-[10px] text-purple-700 font-bold uppercase">{{ booking.ruangan_kode }}</span>
                            <h3 class="font-black text-slate-900 text-sm">{{ booking.ruangan_nama }}</h3>
                            <span class="text-[11px] text-slate-500">{{ booking.ruangan_gedung }} {{ booking.ruangan_lantai ? `Lt. ${booking.ruangan_lantai}` : '' }} (Kapasitas: {{ booking.ruangan_kapasitas || '-' }} org)</span>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Pemohon:</span>
                        <span class="font-bold text-slate-800">{{ booking.pemohon_nama }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Tanggal Pengajuan:</span>
                        <span class="font-bold text-slate-800">{{ booking.created_at }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Tanggal Penggunaan:</span>
                        <span class="font-bold text-slate-800">{{ booking.tanggal }}</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between">
                        <span class="text-slate-400 font-medium">Waktu / Jam:</span>
                        <span class="font-bold text-purple-700">{{ booking.jam_mulai }} - {{ booking.jam_selesai }} WIB</span>
                    </div>
                    <div class="py-2 border-b border-slate-100 flex justify-between sm:col-span-2">
                        <span class="text-slate-400 font-medium">Estimasi Peserta:</span>
                        <span class="font-bold text-slate-800">{{ booking.jumlah_peserta ? `${booking.jumlah_peserta} Orang` : '-' }}</span>
                    </div>
                </div>

                <!-- Keperluan Acara -->
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Nama Acara & Keperluan</span>
                    <p class="text-xs text-slate-800 font-semibold bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        {{ booking.keperluan }}
                    </p>
                </div>

                <!-- Deskripsi & Kebutuhan Fasilitas -->
                <div v-if="booking.deskripsi">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Deskripsi & Kebutuhan Tambahan</span>
                    <p class="text-xs text-slate-600 bg-slate-50 p-3.5 rounded-2xl border border-slate-100 whitespace-pre-line leading-relaxed">
                        {{ booking.deskripsi }}
                    </p>
                </div>

                <!-- Verification Log -->
                <div v-if="booking.approver_nama || booking.catatan_approval" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                    <span class="font-bold text-slate-900 block text-xs border-b border-slate-200 pb-1.5">Hasil Verifikasi</span>
                    <div v-if="booking.approver_nama" class="flex justify-between">
                        <span class="text-slate-500">Diverifikasi oleh:</span>
                        <span class="font-bold text-slate-800">{{ booking.approver_nama }}</span>
                    </div>
                    <div v-if="booking.catatan_approval" class="flex justify-between">
                        <span class="text-slate-500">Catatan Petugas:</span>
                        <span class="font-semibold text-slate-800">{{ booking.catatan_approval }}</span>
                    </div>
                </div>
            </div>

            <!-- Approve Modal -->
            <Teleport to="body">
                <div v-if="showApproveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Setujui Booking Ruangan</h3>
                            <button @click="showApproveModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitApprove" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Persetujuan (Opsional)</label>
                                <textarea
                                    v-model="approveForm.catatan_approval"
                                    rows="2"
                                    placeholder="Kunci ruangan dapat diambil di pos keamanan..."
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
                            <h3 class="font-black text-slate-900 text-sm">Tolak Booking Ruangan</h3>
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
                                    placeholder="Jelaskan alasan penolakan (misal: jadwal bentrok dengan acara pimpinan)..."
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

            <!-- Cancel Modal -->
            <Teleport to="body">
                <div v-if="showCancelModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Batalkan Reservasi?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Permohonan reservasi ruangan untuk acara "<span class="font-bold text-slate-800">{{ booking.keperluan }}</span>" akan dibatalkan.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showCancelModal = false"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedCancel"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm"
                            >
                                Ya, Batalkan
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
    booking: Object,
    canApprove: Boolean,
});

const showApproveModal = ref(false);
const showRejectModal = ref(false);
const showCancelModal = ref(false);

const approveForm = reactive({
    catatan_approval: '',
});

const rejectForm = reactive({
    catatan_approval: '',
});

function submitApprove() {
    router.put(`/booking-ruangan/${props.booking.id}/approve`, approveForm, {
        onSuccess: () => {
            showApproveModal.value = false;
        },
    });
}

function submitReject() {
    router.put(`/booking-ruangan/${props.booking.id}/reject`, rejectForm, {
        onSuccess: () => {
            showRejectModal.value = false;
        },
    });
}

function proceedCancel() {
    router.delete(`/booking-ruangan/${props.booking.id}`, {
        onSuccess: () => {
            showCancelModal.value = false;
        },
    });
}

function statusBadgeClass(status) {
    const map = {
        'pending': 'bg-amber-50 text-amber-700 border border-amber-200',
        'disetujui': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'ditolak': 'bg-rose-50 text-rose-700 border border-rose-200',
        'dibatalkan': 'bg-slate-100 text-slate-600 border border-slate-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
