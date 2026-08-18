<template>
    <AuthenticatedLayout title="Tambah HKI & Paten">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/hki"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Tambah Data HKI & Paten</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Pencatatan karya hak cipta, paten, merek, atau desain industri.</p>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Judul Karya HKI / Ciptaan / Paten <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        v-model="form.judul_hki"
                        required
                        rows="3"
                        placeholder="Tuliskan judul lengkap karya ciptaan, penemuan paten, atau merek..."
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none resize-none leading-relaxed font-semibold"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Inventor / Pemegang Hak (Dosen/Tendik) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.pegawai_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="">-- Pilih Inventor / Pegawai --</option>
                            <option v-for="p in pegawais" :key="p.id" :value="p.id">
                                {{ p.nama }} ({{ p.nip || 'No NIP' }}) {{ p.unit_kerja ? `— ${p.unit_kerja}` : '' }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jenis HKI <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.jenis_hki"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option v-for="j in jenisOptions" :key="j" :value="j">{{ j }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Pencatatan / Registrasi</label>
                        <input
                            v-model="form.nomor_pencatatan"
                            type="text"
                            placeholder="Contoh: EC00202412345"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none font-mono"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tahun Terbit <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.tahun_terbit"
                            type="number"
                            required
                            min="2000"
                            :max="currentYear + 1"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none font-semibold"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Status HKI <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.status"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option v-for="s in statusOptions" :key="s" :value="s">{{ s }}</option>
                        </select>
                    </div>
                </div>

                <!-- File Sertifikat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Dokumen Sertifikat HKI (Format PDF, Maks 5MB)</label>
                    <input
                        type="file"
                        accept=".pdf"
                        @change="handleFileUpload"
                        class="w-full p-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer"
                    />
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan Tambahan</label>
                    <textarea
                        v-model="form.keterangan"
                        rows="2"
                        placeholder="Tuliskan catatan atau deskripsi ringkas..."
                        class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none resize-none"
                    ></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/hki"
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
                        <span>Simpan Data HKI</span>
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
    pegawais: Array,
    jenisOptions: Array,
    statusOptions: Array,
});

const currentYear = new Date().getFullYear();

const form = reactive({
    judul_hki: '',
    pegawai_id: '',
    jenis_hki: props.jenisOptions?.[0] || 'Hak Cipta',
    nomor_pencatatan: '',
    tahun_terbit: currentYear,
    status: props.statusOptions?.[0] || 'Granted/Sertifikat',
    keterangan: '',
    sertifikat: null,
});

function handleFileUpload(e) {
    form.sertifikat = e.target.files[0];
}

const processing = ref(false);

function submit() {
    processing.value = true;
    router.post('/hki', form, {
        forceFormData: true,
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
