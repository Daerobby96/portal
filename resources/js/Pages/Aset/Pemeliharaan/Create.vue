<template>
    <AuthenticatedLayout :title="`Catat Servis - ${aset.nama_aset}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    :href="`/aset/${aset.id}`"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Catat Pemeliharaan / Servis Aset</h1>
                    <p class="text-xs text-slate-400 mt-0.5">{{ aset.kode_aset }} — {{ aset.nama_aset }}</p>
                </div>
            </div>

            <!-- Target Asset Banner -->
            <div class="p-4 rounded-2xl bg-slate-900 text-white flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <span class="font-mono text-[10px] text-amber-400 font-bold uppercase">{{ aset.kode_aset }}</span>
                        <h3 class="font-bold text-sm text-white">{{ aset.nama_aset }}</h3>
                    </div>
                </div>
                <div class="text-right text-xs">
                    <span class="text-slate-400 block text-[10px]">Lokasi Penempatan:</span>
                    <span class="font-semibold text-slate-200">{{ aset.lokasi }}</span>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Pemeliharaan -->
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

                    <!-- Jenis Pemeliharaan -->
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

                <!-- Deskripsi Kegiatan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Deskripsi Kegiatan Pemeliharaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        v-model="form.deskripsi_kegiatan"
                        required
                        rows="3"
                        placeholder="Uraikan rincian pembersihan, perbaikan sparepart, pengecekan fungsi, dll..."
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none resize-none leading-relaxed"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Temuan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Temuan Masalah</label>
                        <textarea
                            v-model="form.temuan"
                            rows="2"
                            placeholder="Kondisi atau gejala kerusakan yang ditemukan..."
                            class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none resize-none"
                        ></textarea>
                    </div>

                    <!-- Tindakan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tindakan Perbaikan</label>
                        <textarea
                            v-model="form.tindakan"
                            rows="2"
                            placeholder="Langkah teknis yang telah dikerjakan..."
                            class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none resize-none"
                        ></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Hasil -->
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

                    <!-- Biaya -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Biaya Perbaikan (Rp)</label>
                        <input
                            v-model="form.biaya"
                            type="number"
                            min="0"
                            placeholder="0 jika internal"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>

                    <!-- Vendor -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Vendor / Teknisi</label>
                        <input
                            v-model="form.vendor"
                            type="text"
                            placeholder="Contoh: Internal IT / PT Servis Jaya"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Pemeliharaan Berikutnya -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jadwal Servis Berikutnya</label>
                        <input
                            v-model="form.tanggal_berikutnya"
                            type="date"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ada jadwal rutin.</p>
                    </div>

                    <!-- Bukti Foto -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Bukti / Nota Servis</label>
                        <input
                            type="file"
                            accept="image/*"
                            @change="e => selectedFoto = e.target.files[0]"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-500/30 outline-none"
                        />
                        <p class="text-[10px] text-slate-400 mt-1">Maksimal 2MB (JPG/PNG).</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        :href="`/aset/${aset.id}`"
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
                        <span>Simpan Catatan Pemeliharaan</span>
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
    aset: Object,
});

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    tanggal_pemeliharaan: today,
    jenis: 'preventif',
    deskripsi_kegiatan: '',
    temuan: '',
    tindakan: '',
    hasil: 'baik',
    biaya: '',
    vendor: '',
    tanggal_berikutnya: '',
});

const selectedFoto = ref(null);
const processing = ref(false);

function submit() {
    processing.value = true;
    const data = new FormData();
    Object.entries(form).forEach(([key, val]) => {
        if (val !== null && val !== '') {
            data.append(key, val);
        }
    });
    if (selectedFoto.value) {
        data.append('bukti_foto', selectedFoto.value);
    }

    router.post(`/aset/${props.aset.id}/pemeliharaan`, data, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
