<template>
    <AuthenticatedLayout title="Tambah Kerjasama Baru">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/kerjasama"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Tambah Kerjasama Baru</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Catat nota kesepahaman (MoU), perjanjian kerjasama (MoA), atau IA mitra.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section 1: Informasi Mitra -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Identitas Mitra Kerjasama</h2>
                            <p class="text-[11px] text-slate-400">Informasi institusi atau lembaga mitra yang diajak bekerjasama.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Mitra -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lembaga / Mitra <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_mitra"
                                type="text"
                                required
                                placeholder="Contoh: Universitas Gadjah Mada / PT Telkom Indonesia"
                                class="w-full px-4 py-2.5 rounded-xl border text-xs focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition"
                                :class="errors.nama_mitra ? 'border-rose-400' : 'border-slate-200'"
                            />
                            <p v-if="errors.nama_mitra" class="text-rose-500 text-[10px] mt-1">{{ errors.nama_mitra }}</p>
                        </div>

                        <!-- Jenis Mitra -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Jenis Mitra <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.jenis_mitra"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition bg-white"
                            >
                                <option value="">-- Pilih Jenis Mitra --</option>
                                <option v-for="j in jenisMitra" :key="j" :value="j">{{ j }}</option>
                            </select>
                            <p v-if="errors.jenis_mitra" class="text-rose-500 text-[10px] mt-1">{{ errors.jenis_mitra }}</p>
                        </div>

                        <!-- Tingkat Wilayah -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tingkat Wilayah <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.tingkat"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition bg-white"
                            >
                                <option v-for="t in tingkatList" :key="t" :value="t">{{ t }}</option>
                            </select>
                            <p v-if="errors.tingkat" class="text-rose-500 text-[10px] mt-1">{{ errors.tingkat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Ruang Lingkup & Dokumen -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">2</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Ruang Lingkup & Pengusul</h2>
                            <p class="text-[11px] text-slate-400">Judul kegiatan, jenis naskah hukum, dan unit prodi pengusul.</p>
                        </div>
                    </div>

                    <!-- Judul Kegiatan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul / Topik Kerjasama <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.judul_kerjasama"
                            type="text"
                            required
                            placeholder="Contoh: MoU Pertukaran Pelajar, Magang Industri, dan Riset Bersama"
                            class="w-full px-4 py-2.5 rounded-xl border text-xs focus:ring-2 focus:ring-pink-500/30 focus:border-pink-400 outline-none transition"
                            :class="errors.judul_kerjasama ? 'border-rose-400' : 'border-slate-200'"
                        />
                        <p v-if="errors.judul_kerjasama" class="text-rose-500 text-[10px] mt-1">{{ errors.judul_kerjasama }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Jenis Dokumen -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Jenis Dokumen <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.jenis_dokumen"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none bg-white"
                            >
                                <option v-for="jd in jenisDokumen" :key="jd" :value="jd">{{ jd }}</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Status <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none bg-white"
                            >
                                <option v-for="st in statusList" :key="st" :value="st">{{ st }}</option>
                            </select>
                        </div>

                        <!-- Prodi Pengusul -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Prodi Pengusul
                            </label>
                            <select
                                v-model="form.prodi_id"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none bg-white"
                            >
                                <option value="">-- Institusi / Umum --</option>
                                <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Masa Berlaku & Berkas -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold">3</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Masa Berlaku & Berkas MoU</h2>
                            <p class="text-[11px] text-slate-400">Jangka waktu berlakunya perjanjian dan unggah naskah digital.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tanggal Mulai Berlaku <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal_mulai"
                                type="date"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition"
                            />
                            <p v-if="errors.tanggal_mulai" class="text-rose-500 text-[10px] mt-1">{{ errors.tanggal_mulai }}</p>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tanggal Berakhir
                            </label>
                            <input
                                v-model="form.tanggal_selesai"
                                type="date"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition"
                            />
                            <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ada batas waktu berakhir.</p>
                            <p v-if="errors.tanggal_selesai" class="text-rose-500 text-[10px] mt-1">{{ errors.tanggal_selesai }}</p>
                        </div>
                    </div>

                    <!-- Upload Dokumen MoU -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Naskah MoU / MoA / IA (PDF)
                        </label>
                        <input
                            type="file"
                            accept=".pdf"
                            @change="e => selectedFile = e.target.files[0]"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none"
                        />
                        <p class="text-[10px] text-slate-400 mt-1">Maksimal 10 MB (Format berkas .pdf).</p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Keterangan Tambahan
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            placeholder="Catatan ruang lingkup, PIC mitra, atau ketentuan khusus lainnya..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition resize-none leading-relaxed"
                        ></textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link
                        href="/kerjasama"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        <span>Simpan Data Kerjasama</span>
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
    prodis: Array,
    jenisMitra: Array,
    tingkatList: Array,
    jenisDokumen: Array,
    statusList: Array,
});

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    nama_mitra: '',
    jenis_mitra: '',
    tingkat: 'Nasional',
    judul_kerjasama: '',
    jenis_dokumen: 'MoU',
    prodi_id: '',
    status: 'Aktif',
    tanggal_mulai: today,
    tanggal_selesai: '',
    keterangan: '',
});

const selectedFile = ref(null);
const processing = ref(false);
const errors = ref({});

function submit() {
    processing.value = true;
    const data = new FormData();
    Object.entries(form).forEach(([key, val]) => {
        if (val !== null && val !== '') {
            data.append(key, val);
        }
    });
    if (selectedFile.value) {
        data.append('dokumen_mou', selectedFile.value);
    }

    router.post('/kerjasama', data, {
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
