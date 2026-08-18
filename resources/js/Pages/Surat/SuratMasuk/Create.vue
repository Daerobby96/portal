<template>
    <AuthenticatedLayout title="Catat Surat Masuk">
        <div class="max-w-3xl mx-auto space-y-5">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <a href="/surat-masuk" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Catat Surat Masuk</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Isi formulir pencatatan naskah dinas masuk</p>
                </div>
            </div>

            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-5">
                <!-- Section Identitas -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Identitas Surat</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Jenis Surat -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Surat <span class="text-red-500">*</span></label>
                            <select v-model="form.jenis_surat_id" required class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" :class="errors.jenis_surat_id ? 'border-red-400' : 'border-slate-200'">
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option v-for="j in jenisSurat" :key="j.id" :value="j.id">{{ j.nama }}</option>
                            </select>
                            <p v-if="errors.jenis_surat_id" class="text-red-500 text-[10px] mt-1">{{ errors.jenis_surat_id }}</p>
                        </div>

                        <!-- Nomor Surat Asal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Surat Asal <span class="text-red-500">*</span></label>
                            <input v-model="form.nomor_surat" type="text" required placeholder="Contoh: 001/DIT/VIII/2026"
                                class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition"
                                :class="errors.nomor_surat ? 'border-red-400' : 'border-slate-200'" />
                            <p v-if="errors.nomor_surat" class="text-red-500 text-[10px] mt-1">{{ errors.nomor_surat }}</p>
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input v-model="form.tanggal_surat" type="date" required
                                class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition border-slate-200" />
                        </div>

                        <!-- Tanggal Terima -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Terima <span class="text-red-500">*</span></label>
                            <input v-model="form.tanggal_terima" type="date" required
                                class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition border-slate-200" />
                        </div>
                    </div>
                </div>

                <!-- Section Pengirim -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Pengirim & Perihal</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Instansi/Asal Pengirim <span class="text-red-500">*</span></label>
                            <input v-model="form.pengirim" type="text" required placeholder="Nama instansi pengirim"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Pengirim</label>
                            <input v-model="form.alamat_pengirim" type="text" placeholder="Alamat instansi"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Perihal <span class="text-red-500">*</span></label>
                            <input v-model="form.perihal" type="text" required placeholder="Perihal / pokok surat"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                    </div>
                </div>

                <!-- Section Klasifikasi -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Klasifikasi & Lampiran</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Sifat Surat <span class="text-red-500">*</span></label>
                            <select v-model="form.sifat" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition">
                                <option value="biasa">Biasa</option>
                                <option value="segera">Segera</option>
                                <option value="sangat_segera">Sangat Segera</option>
                                <option value="rahasia">Rahasia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Prioritas <span class="text-red-500">*</span></label>
                            <select v-model="form.prioritas" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition">
                                <option value="rendah">Rendah</option>
                                <option value="sedang">Sedang</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jml. Lampiran</label>
                            <input v-model="form.jumlah_lampiran" type="number" min="0" placeholder="0"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Ket. Lampiran</label>
                            <input v-model="form.keterangan_lampiran" type="text" placeholder="Halaman, berkas..."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Internal</label>
                        <textarea v-model="form.catatan" rows="2" placeholder="Catatan tambahan dari petugas..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition resize-none"></textarea>
                    </div>
                </div>

                <!-- Upload File -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100 mb-4">Berkas Surat</h2>
                    <div class="border-2 border-dashed border-slate-200 hover:border-amber-300 transition rounded-xl p-6 text-center cursor-pointer relative"
                        @dragover.prevent @drop.prevent="handleDrop" @click="$refs.fileInput.click()">
                        <input ref="fileInput" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" @change="handleFile" />
                        <div v-if="!selectedFile">
                            <i class="bi bi-cloud-upload text-3xl text-slate-200 mb-2 block"></i>
                            <p class="text-xs font-semibold text-slate-500">Klik atau seret berkas ke sini</p>
                            <p class="text-[10px] text-slate-400 mt-1">PDF, JPG, PNG — maksimal 5 MB</p>
                        </div>
                        <div v-else class="flex items-center justify-center gap-3">
                            <i class="bi bi-file-earmark-check text-2xl text-amber-500"></i>
                            <div class="text-left">
                                <p class="text-xs font-bold text-slate-800">{{ selectedFile.name }}</p>
                                <p class="text-[10px] text-slate-400">{{ (selectedFile.size / 1024).toFixed(1) }} KB</p>
                            </div>
                            <button type="button" @click.stop="selectedFile = null" class="ml-2 text-red-400 hover:text-red-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pb-4">
                    <a href="/surat-masuk" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        Simpan Surat Masuk
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

const props = defineProps({ jenisSurat: Array });

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    jenis_surat_id: '',
    nomor_surat: '',
    tanggal_surat: today,
    tanggal_terima: today,
    pengirim: '',
    alamat_pengirim: '',
    perihal: '',
    jumlah_lampiran: null,
    keterangan_lampiran: '',
    sifat: 'biasa',
    prioritas: 'sedang',
    catatan: '',
});

const errors = ref({});
const processing = ref(false);
const selectedFile = ref(null);
const fileInput = ref(null);

function handleFile(e) { selectedFile.value = e.target.files[0] || null; }
function handleDrop(e) { selectedFile.value = e.dataTransfer.files[0] || null; }

function submit() {
    processing.value = true;
    const data = new FormData();
    Object.entries(form).forEach(([k, v]) => { if (v !== null && v !== '') data.append(k, v); });
    if (selectedFile.value) data.append('file', selectedFile.value);

    router.post('/surat-masuk', data, {
        onError: (e) => { errors.value = e; processing.value = false; },
        onFinish: () => { processing.value = false; },
    });
}
</script>
