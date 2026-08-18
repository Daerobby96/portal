<template>
    <AuthenticatedLayout title="Edit Prestasi Mahasiswa">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Data Prestasi</h1>
                    <p class="text-xs text-slate-500 mt-1">Perbarui rincian capaian prestasi kegiatan {{ prestasi.nama_kegiatan }}.</p>
                </div>
                <Link
                    href="/prestasi"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mahasiswa Peraih Prestasi <span class="text-rose-500">*</span></label>
                    <select
                        v-model="form.mahasiswa_id"
                        required
                        class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30 bg-white font-semibold"
                    >
                        <option value="">-- Pilih Mahasiswa --</option>
                        <option v-for="m in mahasiswas" :key="m.id" :value="m.id">
                            {{ m.nim }} — {{ m.nama }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kegiatan / Kompetisi <span class="text-rose-500">*</span></label>
                    <input
                        v-model="form.nama_kegiatan"
                        type="text"
                        required
                        class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30 font-semibold"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Prestasi <span class="text-rose-500">*</span></label>
                        <select
                            v-model="form.jenis_prestasi"
                            required
                            class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30 bg-white font-semibold"
                        >
                            <option v-for="j in jenisOptions" :key="j" :value="j">{{ j }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Wilayah <span class="text-rose-500">*</span></label>
                        <select
                            v-model="form.tingkat"
                            required
                            class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30 bg-white font-semibold"
                        >
                            <option v-for="t in tingkatOptions" :key="t" :value="t">{{ t }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Perolehan <span class="text-rose-500">*</span></label>
                        <input
                            v-model="form.tahun"
                            type="number"
                            required
                            min="2000"
                            :max="currentYear + 1"
                            class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30 font-mono font-semibold"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Penyelenggara Kegiatan</label>
                        <input
                            v-model="form.penyelenggara"
                            type="text"
                            class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Peringkat / Capaian</label>
                        <input
                            v-model="form.peringkat"
                            type="text"
                            class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Upload Sertifikat Baru (PDF Maks 5MB)</label>
                    <div v-if="prestasi.sertifikat_url" class="mb-2 flex items-center gap-2 text-xs text-slate-600 bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                        <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-base"></i>
                        <span>Sertifikat saat ini:</span>
                        <a :href="prestasi.sertifikat_url" target="_blank" class="font-bold text-amber-800 underline">Unduh / Preview Sertifikat</a>
                    </div>
                    <input
                        type="file"
                        accept=".pdf"
                        @change="handleFileUpload"
                        class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 cursor-pointer"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Tambahan</label>
                    <textarea
                        v-model="form.keterangan"
                        rows="2"
                        class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-amber-500/30"
                    ></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <Link
                        href="/prestasi"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5 cursor-pointer"
                    >
                        <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    prestasi: Object,
    mahasiswas: Array,
    jenisOptions: Array,
    tingkatOptions: Array,
});

const currentYear = new Date().getFullYear();

const form = useForm({
    _method: 'PUT',
    mahasiswa_id: props.prestasi.mahasiswa_id || '',
    nama_kegiatan: props.prestasi.nama_kegiatan || '',
    jenis_prestasi: props.prestasi.jenis_prestasi || 'Akademik',
    tingkat: props.prestasi.tingkat || 'Nasional',
    tahun: props.prestasi.tahun || currentYear,
    penyelenggara: props.prestasi.penyelenggara || '',
    peringkat: props.prestasi.peringkat || '',
    keterangan: props.prestasi.keterangan || '',
    sertifikat: null,
});

function handleFileUpload(e) {
    form.sertifikat = e.target.files[0];
}

function submit() {
    form.post(`/prestasi/${props.prestasi.id}`, {
        forceFormData: true,
    });
}
</script>
