<template>
    <AuthenticatedLayout title="Tambah Publikasi Ilmiah">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/publikasi"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Tambah Data Publikasi Ilmiah</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Pencatatan artikel jurnal, prosiding, buku, dan karya ilmiah dosen.</p>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Judul Artikel / Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        v-model="form.judul"
                        required
                        rows="3"
                        placeholder="Tuliskan judul lengkap artikel ilmiah atau karya publikasi..."
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none resize-none leading-relaxed font-semibold"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Penulis (Dosen) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.pegawai_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="">-- Pilih Dosen Penulis --</option>
                            <option v-for="d in dosens" :key="d.id" :value="d.id">
                                {{ d.nama }} ({{ d.nip || 'No NIP' }}) {{ d.unit_kerja ? `— ${d.unit_kerja}` : '' }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Program Studi</label>
                        <select
                            v-model="form.prodi_id"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">-- Pilih Program Studi --</option>
                            <option v-for="pr in prodis" :key="pr.id" :value="pr.id">{{ pr.nama }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tahun Terbit <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tahun"
                            type="number"
                            required
                            min="2000"
                            :max="currentYear + 1"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none font-semibold"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jenis Publikasi <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.jenis"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="Jurnal Nasional">Jurnal Nasional</option>
                            <option value="Jurnal Internasional">Jurnal Internasional</option>
                            <option value="Prosiding">Prosiding Seminar</option>
                            <option value="Buku">Buku / Monograf</option>
                            <option value="HKI">HKI</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Akreditasi SINTA</label>
                        <select
                            v-model="form.tingkat_sinta"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white"
                        >
                            <option value="">-- Pilih Jika Terakreditasi --</option>
                            <option value="SINTA 1">SINTA 1</option>
                            <option value="SINTA 2">SINTA 2</option>
                            <option value="SINTA 3">SINTA 3</option>
                            <option value="SINTA 4">SINTA 4</option>
                            <option value="SINTA 5">SINTA 5</option>
                            <option value="SINTA 6">SINTA 6</option>
                            <option value="Non SINTA">Non SINTA</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Jurnal / Penerbit</label>
                        <input
                            v-model="form.nama_jurnal_penerbit"
                            type="text"
                            placeholder="Contoh: Jurnal Teknologi Informasi & Komputer"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Volume, Nomor & Halaman</label>
                        <input
                            v-model="form.volume_nomor"
                            type="text"
                            placeholder="Contoh: Vol. 12 No. 2, Hal. 45-56"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none font-mono"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">URL Tautan Artikel / DOI</label>
                    <input
                        v-model="form.url_tautan"
                        type="url"
                        placeholder="https://doi.org/10.xxxx/xxxxx"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none font-mono text-blue-700"
                    />
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/publikasi"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        <span>Simpan Data Publikasi</span>
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
    dosens: Array,
});

const currentYear = new Date().getFullYear();

const form = reactive({
    judul: '',
    pegawai_id: '',
    prodi_id: '',
    tahun: currentYear,
    jenis: 'Jurnal Nasional',
    nama_jurnal_penerbit: '',
    volume_nomor: '',
    url_tautan: '',
    tingkat_sinta: '',
});

const processing = ref(false);

function submit() {
    processing.value = true;
    router.post('/publikasi', form, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
