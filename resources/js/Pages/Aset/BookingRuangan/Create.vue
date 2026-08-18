<template>
    <AuthenticatedLayout title="Pengajuan Booking Ruangan">
        <div class="max-w-2xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/booking-ruangan"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Form Booking / Reservasi Ruangan</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Reservasi ruang kelas, laboratorium, dan auditorium kampus.</p>
                </div>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <!-- Pilih Ruangan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Pilih Ruangan <span class="text-rose-500">*</span>
                    </label>
                    <select
                        v-model="form.ruangan_id"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none bg-white font-semibold"
                    >
                        <option value="">-- Pilih Ruangan Kampus --</option>
                        <option v-for="r in ruangans" :key="r.id" :value="r.id">
                            {{ r.nama_ruangan }} ({{ r.kode_ruangan }}) — {{ r.gedung }} {{ r.lantai ? `Lt. ${r.lantai}` : '' }} (Kapasitas: {{ r.kapasitas || '-' }} orang)
                        </option>
                    </select>
                </div>

                <!-- Keperluan Acara -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nama Acara / Keperluan <span class="text-rose-500">*</span>
                    </label>
                    <input
                        v-model="form.keperluan"
                        type="text"
                        required
                        placeholder="Contoh: Kuliah Umum AI & Data Science / Sidang Skripsi"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tanggal"
                            type="date"
                            required
                            :min="today"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none"
                        />
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jam Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.jam_mulai"
                            type="time"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none"
                        />
                    </div>

                    <!-- Jam Selesai -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jam Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.jam_selesai"
                            type="time"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none"
                        />
                    </div>
                </div>

                <!-- Estimasi Peserta -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Estimasi Jumlah Peserta (Orang)</label>
                    <input
                        v-model="form.jumlah_peserta"
                        type="number"
                        min="1"
                        placeholder="Contoh: 50"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none"
                    />
                </div>

                <!-- Deskripsi & Kebutuhan Fasilitas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi & Kebutuhan Fasilitas Tambahan</label>
                    <textarea
                        v-model="form.deskripsi"
                        rows="3"
                        placeholder="Kebutuhan sound system, mic wireless, pointer presentasi, penataan meja/kursi..."
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500/30 outline-none resize-none leading-relaxed"
                    ></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/booking-ruangan"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-calendar-plus-fill"></i>
                        <span>Kirim Permohonan Booking</span>
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
    ruangans: Array,
});

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    ruangan_id: '',
    keperluan: '',
    tanggal: today,
    jam_mulai: '08:00',
    jam_selesai: '12:00',
    jumlah_peserta: '',
    deskripsi: '',
    catatan_pemohon: '',
});

const processing = ref(false);

function submit() {
    processing.value = true;
    router.post('/booking-ruangan', form, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
