<template>
    <AuthenticatedLayout title="Buat Surat Keputusan">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="flex items-center gap-3">
                <a href="/surat-keputusan" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Buat Surat Keputusan</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Nomor SK digenerate otomatis setelah disimpan</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Jenis SK -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Jenis Surat Keputusan</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="form.jenis_sk = 'yayasan'"
                            class="flex flex-col items-center gap-2 p-5 rounded-2xl border-2 transition"
                            :class="form.jenis_sk === 'yayasan' ? 'border-violet-500 bg-violet-50' : 'border-slate-200 hover:border-violet-300'">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl"
                                :class="form.jenis_sk === 'yayasan' ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-400'">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-black text-sm" :class="form.jenis_sk === 'yayasan' ? 'text-violet-900' : 'text-slate-700'">SK Yayasan</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">SK-YYS</p>
                            </div>
                        </button>
                        <button type="button" @click="form.jenis_sk = 'pt'"
                            class="flex flex-col items-center gap-2 p-5 rounded-2xl border-2 transition"
                            :class="form.jenis_sk === 'pt' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:border-blue-300'">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl"
                                :class="form.jenis_sk === 'pt' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-400'">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div class="text-center">
                                <p class="font-black text-sm" :class="form.jenis_sk === 'pt' ? 'text-blue-900' : 'text-slate-700'">SK Perguruan Tinggi</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">SK-PT</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Tentang & Isi -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Konten SK</h2>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tentang (Perihal SK) <span class="text-red-500">*</span></label>
                        <input v-model="form.tentang" type="text" required placeholder="Contoh: Pengangkatan Karyawan Tetap..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition"
                            :class="errors.tentang ? 'border-red-400' : ''" />
                        <p v-if="errors.tentang" class="text-red-500 text-[10px] mt-1">{{ errors.tentang }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Ditetapkan <span class="text-red-500">*</span></label>
                        <input v-model="form.tanggal_ditetapkan" type="date" required
                            class="w-full md:w-60 px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Isi SK / Konsideran <span class="text-red-500">*</span></label>
                        <textarea v-model="form.isi_sk" required rows="10"
                            placeholder="Menimbang: ...&#10;Mengingat: ...&#10;Memutuskan/Menetapkan: ..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition resize-none font-mono"
                            :class="errors.isi_sk ? 'border-red-400' : ''"></textarea>
                        <p v-if="errors.isi_sk" class="text-red-500 text-[10px] mt-1">{{ errors.isi_sk }}</p>
                    </div>
                </div>

                <!-- Penandatangan -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Penandatangan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Penandatangan <span class="text-red-500">*</span></label>
                            <input v-model="form.penandatangan_nama" type="text" required placeholder="Nama lengkap beserta gelar"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                            <input v-model="form.penandatangan_jabatan" type="text" required placeholder="Jabatan resmi"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                        </div>
                    </div>
                </div>

                <!-- Info box -->
                <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-2xl text-xs text-blue-700">
                    <i class="bi bi-info-circle-fill text-blue-500 text-base mt-0.5 shrink-0"></i>
                    <div>
                        <p class="font-bold">Penomoran Otomatis</p>
                        <p class="text-blue-600 mt-0.5">Nomor SK akan digenerate otomatis mengikuti format penomoran yang berlaku sesuai jenis SK dan tahun penetapan.</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pb-4">
                    <a href="/surat-keputusan" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" :disabled="processing || !form.jenis_sk"
                        class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-file-earmark-ruled-fill"></i>
                        Buat & Generate PDF
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
    jenis_sk: 'yayasan',
    tentang: '',
    isi_sk: '',
    tanggal_ditetapkan: today,
    penandatangan_nama: '',
    penandatangan_jabatan: '',
});

const processing = ref(false);
const errors = ref({});

function submit() {
    processing.value = true;
    router.post('/surat-keputusan', form, {
        onError: e => { errors.value = e; processing.value = false; },
        onFinish: () => { processing.value = false; },
    });
}
</script>
