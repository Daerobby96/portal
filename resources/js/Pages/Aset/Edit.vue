<template>
    <AuthenticatedLayout :title="`Edit - ${aset.nama_aset}`">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    :href="`/aset/${aset.id}`"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Data Aset</h1>
                    <p class="text-xs text-slate-400 mt-0.5">{{ aset.kode_aset }} — {{ aset.nama_aset }}</p>
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
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kode Register Aset <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.kode_aset"
                                type="text"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none font-mono uppercase"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kategori Aset <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.kategori_id"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                            >
                                <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Barang / Aset <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_aset"
                                type="text"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Merk / Brand</label>
                            <input
                                v-model="form.merk"
                                type="text"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe / Seri Model</label>
                            <input
                                v-model="form.tipe"
                                type="text"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Seri Pabrik</label>
                            <input
                                v-model="form.nomor_seri"
                                type="text"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none font-mono"
                            />
                        </div>
                    </div>

                    <!-- Foto Current + Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Barang / Aset</label>
                        <div v-if="aset.foto" class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200 mb-2.5">
                            <img :src="aset.foto" alt="Current foto" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0" />
                            <div class="text-xs">
                                <span class="font-bold text-slate-800 block">Foto Saat Ini</span>
                                <span class="text-[11px] text-slate-400">Pilih berkas baru di bawah jika ingin mengganti.</span>
                            </div>
                        </div>
                        <input
                            type="file"
                            accept="image/*"
                            @change="e => selectedFoto = e.target.files[0]"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                        />
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

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Gedung / Lokasi <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.lokasi"
                                type="text"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Ruangan Spesifik</label>
                            <input
                                v-model="form.ruangan"
                                type="text"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Penanggung Jawab (PIC)</label>
                            <input
                                v-model="form.penanggung_jawab"
                                type="text"
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
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Perolehan</label>
                            <input
                                v-model="form.tanggal_perolehan"
                                type="date"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Sumber Dana / Asal</label>
                            <input
                                v-model="form.sumber_perolehan"
                                type="text"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga Perolehan (Rp)</label>
                            <input
                                v-model="form.harga_perolehan"
                                type="number"
                                min="0"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Umur Ekonomis (Tahun)</label>
                            <input
                                v-model="form.umur_ekonomis"
                                type="number"
                                min="1"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Spesifikasi Detail</label>
                        <textarea
                            v-model="form.spesifikasi"
                            rows="3"
                            class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none resize-none leading-relaxed"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tambahan</label>
                        <textarea
                            v-model="form.keterangan"
                            rows="2"
                            class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none resize-none leading-relaxed"
                        ></textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link
                        :href="`/aset/${aset.id}`"
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
    aset: Object,
    kategoris: Array,
    prodis: Array,
});

const form = reactive({
    kode_aset: props.aset.kode_aset || '',
    kategori_id: props.aset.kategori_id || '',
    prodi_id: props.aset.prodi_id || '',
    nama_aset: props.aset.nama_aset || '',
    merk: props.aset.merk || '',
    tipe: props.aset.tipe || '',
    nomor_seri: props.aset.nomor_seri || '',
    kondisi: props.aset.kondisi || 'baik',
    status: props.aset.status || 'aktif',
    lokasi: props.aset.lokasi || '',
    ruangan: props.aset.ruangan || '',
    tanggal_perolehan: props.aset.tanggal_perolehan || '',
    sumber_perolehan: props.aset.sumber_perolehan || '',
    harga_perolehan: props.aset.harga_perolehan || '',
    umur_ekonomis: props.aset.umur_ekonomis || '',
    penanggung_jawab: props.aset.penanggung_jawab || '',
    spesifikasi: props.aset.spesifikasi || '',
    keterangan: props.aset.keterangan || '',
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
        data.append('foto', selectedFoto.value);
    }

    router.post(`/aset/${props.aset.id}`, data, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
