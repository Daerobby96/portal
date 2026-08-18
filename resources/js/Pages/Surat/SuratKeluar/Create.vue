<template>
    <AuthenticatedLayout title="Buat Surat Keluar">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="flex items-center gap-3">
                <a href="/surat-keluar" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Buat Surat Keluar</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Nomor surat akan digenerate otomatis</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Jenis & Unit -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Klasifikasi Surat</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Surat <span class="text-red-500">*</span></label>
                            <select v-model="form.jenis_surat_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option v-for="j in jenisSurat" :key="j.id" :value="j.id">{{ j.kode }} — {{ j.nama }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Unit Pengelola</label>
                            <select v-model="form.unit_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option value="">-- Tanpa Unit --</option>
                                <optgroup v-if="yayasanUnits.length" label="Yayasan">
                                    <option v-for="u in yayasanUnits" :key="u.id" :value="u.id">{{ u.kode }} — {{ u.nama }}</option>
                                </optgroup>
                                <optgroup v-if="ptUnits.length" label="Perguruan Tinggi">
                                    <option v-for="u in ptUnits" :key="u.id" :value="u.id">{{ u.kode }} — {{ u.nama }}</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input v-model="form.tanggal_surat" type="date" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status</label>
                            <select v-model="form.status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                                <option value="draft">Simpan sebagai Draft</option>
                                <option value="pending">Ajukan Persetujuan</option>
                                <option value="published">Terbitkan Langsung</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Perihal & Tujuan -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Perihal & Tujuan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Perihal <span class="text-red-500">*</span></label>
                            <input v-model="form.perihal" type="text" required placeholder="Perihal surat"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tujuan <span class="text-red-500">*</span></label>
                            <input v-model="form.tujuan" type="text" required placeholder="Kepada Yth..."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Tujuan</label>
                            <input v-model="form.alamat_tujuan" type="text" placeholder="Alamat penerima"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Isi Surat</label>
                        <textarea v-model="form.isi_surat" rows="5" placeholder="Tuliskan isi surat..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition resize-none"></textarea>
                    </div>
                </div>

                <!-- Penandatangan -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Penandatangan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                            <input v-model="form.penandatangan_nama" type="text" required placeholder="Nama lengkap"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                            <input v-model="form.penandatangan_jabatan" type="text" required placeholder="Jabatan"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP</label>
                            <input v-model="form.penandatangan_nip" type="text" placeholder="NIP/NIK"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jml. Lampiran</label>
                            <input v-model="form.jumlah_lampiran" type="number" min="0" placeholder="0"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Ket. Lampiran</label>
                            <input v-model="form.keterangan_lampiran" type="text" placeholder="Berkas, halaman..."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pb-4">
                    <a href="/surat-keluar" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" :disabled="processing" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-send-fill"></i>
                        {{ statusLabel }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ jenisSurat: Array, unitPengelola: Array });

const today = new Date().toISOString().split('T')[0];

const form = reactive({
    jenis_surat_id: '', unit_id: '', perihal: '', isi_surat: '',
    tanggal_surat: today, tujuan: '', alamat_tujuan: '',
    penandatangan_nama: '', penandatangan_jabatan: '', penandatangan_nip: '',
    jumlah_lampiran: null, keterangan_lampiran: '', catatan: '',
    status: 'draft',
});

const processing = ref(false);
const errors = ref({});

const yayasanUnits = computed(() => props.unitPengelola.filter(u => u.jenis === 'yayasan'));
const ptUnits      = computed(() => props.unitPengelola.filter(u => u.jenis === 'perguruan_tinggi'));
const statusLabel  = computed(() => ({ draft: 'Simpan Draft', pending: 'Ajukan Persetujuan', published: 'Terbitkan' }[form.status] || 'Simpan'));

function submit() {
    processing.value = true;
    router.post('/surat-keluar', form, {
        onError: e => { errors.value = e; processing.value = false; },
        onFinish: () => { processing.value = false; },
    });
}
</script>
