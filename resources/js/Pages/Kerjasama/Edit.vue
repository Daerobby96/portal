<template>
    <AuthenticatedLayout :title="`Edit - ${kerjasama.nama_mitra}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    :href="`/kerjasama/${kerjasama.id}`"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Data Kerjasama</h1>
                    <p class="text-xs text-slate-400 mt-0.5">{{ kerjasama.nama_mitra }} — {{ kerjasama.judul_kerjasama }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section 1: Informasi Mitra -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Identitas Mitra Kerjasama</h2>
                            <p class="text-[11px] text-slate-400">Informasi nama mitra, jenis dan tingkat kerjasama.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lembaga / Mitra <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_mitra"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Jenis Mitra <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.jenis_mitra"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition bg-white"
                            >
                                <option v-for="j in jenisMitra" :key="j" :value="j">{{ j }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Tingkat Wilayah <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.tingkat"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition bg-white"
                            >
                                <option v-for="t in tingkatList" :key="t" :value="t">{{ t }}</option>
                            </select>
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

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul / Topik Kerjasama <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.judul_kerjasama"
                            type="text"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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

                <!-- Section 3: Masa Berlaku & Dokumen -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold">3</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Masa Berlaku & Berkas MoU</h2>
                            <p class="text-[11px] text-slate-400">Jangka waktu berlakunya perjanjian dan unggah naskah digital.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                        </div>

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
                        </div>
                    </div>

                    <!-- Dokumen MoU Currently Uploaded + Upload New -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Ganti Dokumen MoU / MoA (PDF)
                        </label>
                        <div v-if="kerjasama.dokumen_mou" class="p-3 mb-2.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
                            <span class="text-emerald-800 font-semibold flex items-center gap-1.5">
                                <i class="bi bi-file-earmark-check-fill text-emerald-600"></i>
                                Dokumen saat ini sudah terunggah
                            </span>
                            <a :href="kerjasama.dokumen_mou" target="_blank" class="font-bold text-emerald-700 hover:underline">
                                Lihat Berkas
                            </a>
                        </div>
                        <input
                            type="file"
                            accept=".pdf"
                            @change="e => selectedFile = e.target.files[0]"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none"
                        />
                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah berkas saat ini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Keterangan Tambahan
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none transition resize-none leading-relaxed"
                        ></textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link
                        :href="`/kerjasama/${kerjasama.id}`"
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
                        <span>Simpan Perubahan</span>
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
    kerjasama: Object,
    prodis: Array,
    jenisMitra: Array,
    tingkatList: Array,
    jenisDokumen: Array,
    statusList: Array,
});

const form = reactive({
    nama_mitra: props.kerjasama.nama_mitra || '',
    jenis_mitra: props.kerjasama.jenis_mitra || '',
    tingkat: props.kerjasama.tingkat || 'Nasional',
    judul_kerjasama: props.kerjasama.judul_kerjasama || '',
    jenis_dokumen: props.kerjasama.jenis_dokumen || 'MoU',
    prodi_id: props.kerjasama.prodi_id || '',
    status: props.kerjasama.status || 'Aktif',
    tanggal_mulai: props.kerjasama.tanggal_mulai || '',
    tanggal_selesai: props.kerjasama.tanggal_selesai || '',
    keterangan: props.kerjasama.keterangan || '',
});

const selectedFile = ref(null);
const processing = ref(false);

function submit() {
    processing.value = true;
    const data = new FormData();
    data.append('_method', 'PUT');

    Object.entries(form).forEach(([key, val]) => {
        if (val !== null && val !== '') {
            data.append(key, val);
        }
    });
    if (selectedFile.value) {
        data.append('dokumen_mou', selectedFile.value);
    }

    router.post(`/kerjasama/${props.kerjasama.id}`, data, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
