<template>
    <AuthenticatedLayout title="Tambah Aset Baru">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/aset"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Tambah Aset Baru</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Daftarkan barang inventaris dan sarana prasarana baru institusi.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section 1: Identitas Aset -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Identitas Aset & Klasifikasi</h2>
                            <p class="text-[11px] text-slate-400">Kode register, nama barang, merk, dan klasifikasi kategori.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Kode Aset -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kode Register Aset <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.kode_aset"
                                type="text"
                                required
                                placeholder="Contoh: AST-LAB-001"
                                class="w-full px-3.5 py-2.5 rounded-xl border text-xs focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition font-mono uppercase"
                                :class="errors.kode_aset ? 'border-rose-400' : 'border-slate-200'"
                            />
                            <p v-if="errors.kode_aset" class="text-rose-500 text-[10px] mt-1">{{ errors.kode_aset }}</p>
                        </div>

                        <!-- Kategori Aset -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kategori Aset <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.kategori_id"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                            >
                                <option value="">-- Pilih Kategori --</option>
                                <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                            </select>
                            <p v-if="errors.kategori_id" class="text-rose-500 text-[10px] mt-1">{{ errors.kategori_id }}</p>
                        </div>

                        <!-- Nama Aset -->
                        <div class="sm:col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Barang / Aset <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_aset"
                                type="text"
                                required
                                placeholder="Contoh: Laptop Asus ROG Strix"
                                class="w-full px-3.5 py-2.5 rounded-xl border text-xs focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 outline-none transition"
                                :class="errors.nama_aset ? 'border-rose-400' : 'border-slate-200'"
                            />
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Merk / Brand</label>
                            <input
                                v-model="form.merk"
                                type="text"
                                placeholder="Contoh: Asus / Dell / Epson"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Tipe / Model -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe / Seri Model</label>
                            <input
                                v-model="form.tipe"
                                type="text"
                                placeholder="Contoh: Core i7 16GB SSD 512GB"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Nomor Seri -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Seri Pabrik</label>
                            <input
                                v-model="form.nomor_seri"
                                type="text"
                                placeholder="Contoh: SN-849204928"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none font-mono"
                            />
                        </div>
                    </div>

                    <!-- Upload Foto Aset -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Barang / Aset</label>
                        <input
                            type="file"
                            accept="image/*"
                            @change="e => selectedFoto = e.target.files[0]"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                        />
                        <p class="text-[10px] text-slate-400 mt-1">Format gambar JPG, PNG, atau WEBP (maksimal 2MB).</p>
                    </div>
                </div>

                <!-- Section 2: Lokasi, Status & Penanggung Jawab -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">2</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Status, Kondisi & Penempatan</h2>
                            <p class="text-[11px] text-slate-400">Lokasi fisik penempatan, ruangan, dan penanggung jawab aset.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Kondisi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kondisi Fisik <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.kondisi"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white font-semibold"
                            >
                                <option value="baik">Baik (Berfungsi Normal)</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>

                        <!-- Status Operasional -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Status Operasional <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white font-semibold"
                            >
                                <option value="aktif">Aktif Digunakan</option>
                                <option value="non_aktif">Non Aktif / Disimpan</option>
                                <option value="dalam_perbaikan">Dalam Perbaikan (Servis)</option>
                                <option value="dihapuskan">Dihapuskan / Afkir</option>
                            </select>
                        </div>

                        <!-- Unit / Prodi Pemilik -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Unit / Prodi Pemilik</label>
                            <select
                                v-model="form.prodi_id"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                            >
                                <option value="">-- Institusi (Umum) --</option>
                                <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }}</option>
                            </select>
                        </div>

                        <!-- Lokasi Gedung/Area -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Gedung / Lokasi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.lokasi"
                                type="text"
                                required
                                placeholder="Contoh: Gedung Rektorat Lt. 2"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Ruangan Spesifik -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Ruangan Spesifik</label>
                            <input
                                v-model="form.ruangan"
                                type="text"
                                placeholder="Contoh: Lab Komputer 1 / Ruang Rapat A"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Penanggung Jawab -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Penanggung Jawab (PIC)</label>
                            <input
                                v-model="form.penanggung_jawab"
                                type="text"
                                placeholder="Contoh: Kepala Lab / Staff IT"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>
                    </div>
                </div>

                <!-- Section 3: Nilai Perolehan & Spesifikasi -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">3</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Nilai Pengadaan & Spesifikasi Teknis</h2>
                            <p class="text-[11px] text-slate-400">Tanggal beli, harga perolehan, umur ekonomis, dan deskripsi teknis.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Tanggal Perolehan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Perolehan</label>
                            <input
                                v-model="form.tanggal_perolehan"
                                type="date"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Sumber Perolehan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Sumber Dana / Asal</label>
                            <input
                                v-model="form.sumber_perolehan"
                                type="text"
                                placeholder="Contoh: APBN / Hibah / Yayasan"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Harga Perolehan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga Perolehan (Rp)</label>
                            <input
                                v-model="form.harga_perolehan"
                                type="number"
                                min="0"
                                placeholder="Contoh: 15000000"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <!-- Umur Ekonomis -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Umur Ekonomis (Tahun)</label>
                            <input
                                v-model="form.umur_ekonomis"
                                type="number"
                                min="1"
                                placeholder="Contoh: 5"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <!-- Spesifikasi Teknis -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Spesifikasi Detail</label>
                        <textarea
                            v-model="form.spesifikasi"
                            rows="3"
                            placeholder="Detail spesifikasi perangkat keras, kelengkapan aksesoris, lisensi perangkat lunak, dll..."
                            class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none resize-none leading-relaxed"
                        ></textarea>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan</label>
                        <textarea
                            v-model="form.keterangan"
                            rows="2"
                            placeholder="Catatan riwayat garansi, histori pemindahan, atau instruksi khusus..."
                            class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none resize-none leading-relaxed"
                        ></textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link
                        href="/aset"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        <span>Simpan Data Aset</span>
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
    kategoris: Array,
    prodis: Array,
});

const form = reactive({
    kode_aset: '',
    kategori_id: '',
    prodi_id: '',
    nama_aset: '',
    merk: '',
    tipe: '',
    nomor_seri: '',
    kondisi: 'baik',
    status: 'aktif',
    lokasi: '',
    ruangan: '',
    tanggal_perolehan: '',
    sumber_perolehan: '',
    harga_perolehan: '',
    umur_ekonomis: '',
    penanggung_jawab: '',
    spesifikasi: '',
    keterangan: '',
});

const selectedFoto = ref(null);
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
    if (selectedFoto.value) {
        data.append('foto', selectedFoto.value);
    }

    router.post('/aset', data, {
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
