<template>
    <AuthenticatedLayout title="Buat Rapat Baru">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/rapat"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Buat Rapat Baru</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Rapat akan disimpan sebagai Draft sebelum dijadwalkan secara resmi.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section 1: Identitas & Klasifikasi -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Informasi & Klasifikasi Rapat</h2>
                            <p class="text-[11px] text-slate-400">Tentukan topik dan kategori pelaksanaan rapat.</p>
                        </div>
                    </div>

                    <!-- Judul Rapat -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul / Topik Rapat <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.judul"
                            type="text"
                            required
                            placeholder="Contoh: Rapat Tinjauan Manajemen (RTM) Semester Genap 2025/2026"
                            class="w-full px-4 py-2.5 rounded-xl border text-xs focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition"
                            :class="errors.judul ? 'border-rose-400' : 'border-slate-200'"
                        />
                        <p v-if="errors.judul" class="text-rose-500 text-[10px] mt-1">{{ errors.judul }}</p>
                    </div>

                    <!-- Jenis Rapat Visual Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Jenis / Kategori Rapat <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            <button
                                v-for="(lbl, key) in jenisOptions"
                                :key="key"
                                type="button"
                                @click="form.jenis = key"
                                class="p-3 rounded-2xl border-2 text-left transition cursor-pointer flex flex-col justify-between"
                                :class="form.jenis === key ? 'border-teal-500 bg-teal-50/70 text-teal-900 shadow-2xs' : 'border-slate-200 hover:border-teal-200 text-slate-700 bg-white'"
                            >
                                <span class="text-xs font-bold">{{ key }}</span>
                                <span class="text-[10px] text-slate-400 mt-1 line-clamp-1">{{ lbl }}</span>
                            </button>
                        </div>
                        <p v-if="errors.jenis" class="text-rose-500 text-[10px] mt-1">{{ errors.jenis }}</p>
                    </div>

                    <!-- Periode Akademik -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Periode Akademik <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.periode_id"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition bg-white"
                        >
                            <option value="">-- Pilih Periode Akademik --</option>
                            <option v-for="p in periodes" :key="p.id" :value="p.id">
                                {{ p.nama }} {{ p.is_aktif ? '(Periode Berjalan ★)' : '' }}
                            </option>
                        </select>
                        <p v-if="errors.periode_id" class="text-rose-500 text-[10px] mt-1">{{ errors.periode_id }}</p>
                    </div>
                </div>

                <!-- Section 2: Jadwal & Tempat -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">2</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Waktu & Tempat Pelaksanaan</h2>
                            <p class="text-[11px] text-slate-400">Jadwalkan tanggal, jam, dan lokasi ruangan atau tautan online.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Tanggal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal"
                                type="date"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                            />
                            <p v-if="errors.tanggal" class="text-rose-500 text-[10px] mt-1">{{ errors.tanggal }}</p>
                        </div>

                        <!-- Waktu Mulai -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Waktu Mulai <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.waktu_mulai"
                                type="time"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                            />
                            <p v-if="errors.waktu_mulai" class="text-rose-500 text-[10px] mt-1">{{ errors.waktu_mulai }}</p>
                        </div>

                        <!-- Waktu Selesai -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Waktu Selesai <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.waktu_selesai"
                                type="time"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                            />
                            <p v-if="errors.waktu_selesai" class="text-rose-500 text-[10px] mt-1">{{ errors.waktu_selesai }}</p>
                        </div>
                    </div>

                    <!-- Tempat / Ruangan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tempat / Ruangan / Link Online <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tempat"
                            type="text"
                            required
                            placeholder="Contoh: Ruang Rapat Pimpinan Gedung A Lt. 2 / Zoom Meeting"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                        />
                        <p v-if="errors.tempat" class="text-rose-500 text-[10px] mt-1">{{ errors.tempat }}</p>
                    </div>

                    <!-- Deskripsi / Catatan Rapat -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Deskripsi / Pengantar Rapat
                        </label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="4"
                            placeholder="Tuliskan latar belakang, tujuan pembahasan, atau instruksi awal bagi peserta..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition resize-none leading-relaxed"
                        ></textarea>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="flex items-start gap-3.5 p-4 rounded-2xl bg-teal-50/70 border border-teal-200/80 text-xs text-teal-800">
                    <i class="bi bi-info-circle-fill text-teal-600 text-base shrink-0 mt-0.5"></i>
                    <p class="leading-relaxed text-[11px]">
                        Setelah rapat disimpan, Anda akan diarahkan ke halaman kelola detail untuk menyusun <b>Susunan Agenda</b>, mendaftarkan <b>Daftar Hadir Peserta</b>, mengunggah <b>Berkas Materi</b>, dan mempublikasikan status rapat menjadi <b>Terjadwal</b>.
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link
                        href="/rapat"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        <span>Simpan Rapat sebagai Draft</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    periodes: Array,
    periodeAktifId: [Number, String],
    jenisOptions: Object,
    users: Array,
});

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    judul: '',
    jenis: 'RTM',
    periode_id: props.periodeAktifId || '',
    tanggal: today,
    waktu_mulai: '09:00',
    waktu_selesai: '12:00',
    tempat: '',
    deskripsi: '',
});

const processing = ref(false);
const errors = ref({});

function submit() {
    processing.value = true;
    router.post('/rapat', form, {
        onError: (err) => {
            errors.value = err;
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
