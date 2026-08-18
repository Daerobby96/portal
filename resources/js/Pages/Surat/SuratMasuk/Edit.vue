<template>
    <AuthenticatedLayout title="Edit Surat Masuk">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="flex items-center gap-3">
                <a :href="`/surat-masuk/${suratMasuk.id}`" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Surat Masuk</h1>
                    <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ suratMasuk.nomor_agenda }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Identitas Surat</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Surat</label>
                            <select v-model="form.jenis_surat_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option v-for="j in jenisSurat" :key="j.id" :value="j.id">{{ j.nama }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Surat Asal</label>
                            <input v-model="form.nomor_surat" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Surat</label>
                            <input v-model="form.tanggal_surat" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Terima</label>
                            <input v-model="form.tanggal_terima" type="date" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Pengirim</label>
                            <input v-model="form.pengirim" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Pengirim</label>
                            <input v-model="form.alamat_pengirim" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Perihal</label>
                            <input v-model="form.perihal" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Klasifikasi & Status</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Sifat</label>
                            <select v-model="form.sifat" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option value="biasa">Biasa</option>
                                <option value="segera">Segera</option>
                                <option value="sangat_segera">Sangat Segera</option>
                                <option value="rahasia">Rahasia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Prioritas</label>
                            <select v-model="form.prioritas" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option value="rendah">Rendah</option>
                                <option value="sedang">Sedang</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status</label>
                            <select v-model="form.status" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option value="baru">Baru</option>
                                <option value="proses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="arsip">Arsip</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jml. Lampiran</label>
                            <input v-model="form.jumlah_lampiran" type="number" min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan</label>
                        <textarea v-model="form.catatan" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition resize-none"></textarea>
                    </div>
                </div>

                <div v-if="suratMasuk.has_file" class="bg-amber-50 rounded-xl p-4 flex items-center gap-3 text-xs">
                    <i class="bi bi-file-earmark-check text-amber-500 text-base"></i>
                    <span class="text-amber-700 font-semibold">Sudah ada berkas terlampir. Upload berkas baru untuk menggantikannya.</span>
                </div>

                <div class="flex items-center justify-end gap-3 pb-4">
                    <a :href="`/surat-masuk/${suratMasuk.id}`" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" :disabled="processing" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ suratMasuk: Object, jenisSurat: Array });

const form = reactive({ ...props.suratMasuk });
const processing = ref(false);
const errors = ref({});

function submit() {
    processing.value = true;
    router.put(`/surat-masuk/${props.suratMasuk.id}`, form, {
        onError: e => { errors.value = e; processing.value = false; },
        onFinish: () => { processing.value = false; },
    });
}
</script>
