<template>
    <AuthenticatedLayout :title="`Edit Servis - ${pemeliharaan.aset_nama}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    :href="`/pemeliharaan/${pemeliharaan.id}`"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Data Pemeliharaan</h1>
                    <p class="text-xs text-slate-400 mt-0.5">{{ pemeliharaan.aset_kode }} — {{ pemeliharaan.aset_nama }}</p>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Pemeliharaan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tanggal_pemeliharaan"
                            type="date"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jenis Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.jenis"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="preventif">Preventif (Rutin / Berkala)</option>
                            <option value="korektif">Korektif (Perbaikan Kerusakan)</option>
                            <option value="kalibrasi">Kalibrasi Alat Ukur</option>
                            <option value="inspeksi">Inspeksi & Uji Kelayakan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Deskripsi Kegiatan <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        v-model="form.deskripsi_kegiatan"
                        required
                        rows="3"
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none resize-none leading-relaxed"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Temuan Masalah</label>
                        <textarea
                            v-model="form.temuan"
                            rows="2"
                            class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none resize-none"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tindakan Perbaikan</label>
                        <textarea
                            v-model="form.tindakan"
                            rows="2"
                            class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none resize-none"
                        ></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Hasil Akhir <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.hasil"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="baik">Baik (Selesai & Normal)</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan Lanjutan</option>
                            <option value="perlu_penggantian">Perlu Penggantian Unit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Biaya Perbaikan (Rp)</label>
                        <input
                            v-model="form.biaya"
                            type="number"
                            min="0"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Vendor / Teknisi</label>
                        <input
                            v-model="form.vendor"
                            type="text"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jadwal Servis Berikutnya</label>
                        <input
                            v-model="form.tanggal_berikutnya"
                            type="date"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Bukti / Nota</label>
                        <div v-if="pemeliharaan.bukti_foto" class="text-xs text-slate-500 mb-1">
                            <a :href="pemeliharaan.bukti_foto" target="_blank" class="text-amber-700 font-bold hover:underline">
                                <i class="bi bi-image me-1"></i>Lihat Foto Saat Ini
                            </a>
                        </div>
                        <input
                            type="file"
                            accept="image/*"
                            @change="e => selectedFoto = e.target.files[0]"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        :href="`/pemeliharaan/${pemeliharaan.id}`"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
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
    pemeliharaan: Object,
});

const form = reactive({
    tanggal_pemeliharaan: props.pemeliharaan.tanggal_pemeliharaan || '',
    jenis: props.pemeliharaan.jenis || 'preventif',
    deskripsi_kegiatan: props.pemeliharaan.deskripsi_kegiatan || '',
    temuan: props.pemeliharaan.temuan || '',
    tindakan: props.pemeliharaan.tindakan || '',
    hasil: props.pemeliharaan.hasil || 'baik',
    biaya: props.pemeliharaan.biaya || '',
    vendor: props.pemeliharaan.vendor || '',
    tanggal_berikutnya: props.pemeliharaan.tanggal_berikutnya || '',
});

const selectedFoto = ref(null);
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
    if (selectedFoto.value) {
        data.append('bukti_foto', selectedFoto.value);
    }

    router.post(`/pemeliharaan/${props.pemeliharaan.id}`, data, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
