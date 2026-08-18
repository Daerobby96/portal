<template>
    <AuthenticatedLayout title="Pengajuan Peminjaman Aset">
        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/peminjaman"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Form Pengajuan Peminjaman Aset</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Permohonan peminjaman sarana dan fasilitas untuk kegiatan akademik atau institusi.</p>
                </div>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <!-- Pilih Aset -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Pilih Aset / Barang <span class="text-rose-500">*</span>
                    </label>
                    <select
                        v-model="form.aset_id"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none bg-white font-semibold"
                    >
                        <option value="">-- Pilih Barang yang Tersedia --</option>
                        <option v-for="a in asets" :key="a.id" :value="a.id">
                            {{ a.nama_aset }} ({{ a.kode_aset }}) — {{ a.lokasi }}
                        </option>
                    </select>
                </div>

                <!-- Keperluan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Keperluan / Nama Acara <span class="text-rose-500">*</span>
                    </label>
                    <input
                        v-model="form.keperluan"
                        type="text"
                        required
                        placeholder="Contoh: Kuliah Tamu Prodi TI / Ujian Kompetensi"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Pinjam -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Pinjam <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tanggal_pinjam"
                            type="date"
                            required
                            :min="today"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                        />
                    </div>

                    <!-- Tanggal Rencana Kembali -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Rencana Tanggal Pengembalian <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tanggal_kembali_rencana"
                            type="date"
                            required
                            :min="form.tanggal_pinjam || today"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none"
                        />
                    </div>
                </div>

                <!-- Catatan Peminjam -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan / Detail Kebutuhan</label>
                    <textarea
                        v-model="form.catatan_peminjam"
                        rows="3"
                        placeholder="Kelengkapan tambahan yang dibutuhkan, kontak PIC, lokasi penggunaan..."
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/30 outline-none resize-none leading-relaxed"
                    ></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/peminjaman"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-send-fill"></i>
                        <span>Kirim Permohonan Peminjaman</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    asets: Array,
});

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    aset_id: '',
    keperluan: '',
    tanggal_pinjam: today,
    tanggal_kembali_rencana: '',
    catatan_peminjam: '',
});

const processing = ref(false);

function submit() {
    processing.value = true;
    router.post('/peminjaman', form, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
