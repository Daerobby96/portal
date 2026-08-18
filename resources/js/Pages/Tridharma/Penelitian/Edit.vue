<template>
    <AuthenticatedLayout title="Edit Data Penelitian">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    href="/penelitian"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Data Penelitian</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi riset, pendanaan, dan anggota tim.</p>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Judul Penelitian <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        v-model="form.judul"
                        required
                        rows="3"
                        class="w-full p-3.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none resize-none leading-relaxed font-semibold"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Ketua Peneliti (Dosen) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.pegawai_id"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="">-- Pilih Dosen Peneliti --</option>
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
                            Tahun Pelaksanaan <span class="text-rose-500">*</span>
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
                            Tingkat Wilayah <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.tingkat"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="Lokal">Lokal / Perguruan Tinggi</option>
                            <option value="Nasional">Nasional (Kemdikbud/DRTPM)</option>
                            <option value="Internasional">Internasional (Global)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Status Penelitian <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.status"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none bg-white font-semibold"
                        >
                            <option value="Usulan">Usulan (Proposal)</option>
                            <option value="Berjalan">Berjalan (Ongoing)</option>
                            <option value="Selesai">Selesai (Completed)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Sumber Dana / Skema</label>
                        <input
                            v-model="form.sumber_dana"
                            type="text"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Dana (Rp)</label>
                        <input
                            v-model="form.jumlah_dana"
                            type="number"
                            min="0"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Anggota Peneliti</label>
                    <textarea
                        v-model="form.anggota"
                        rows="2"
                        class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/30 outline-none resize-none"
                    ></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <Link
                        href="/penelitian"
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
    penelitian: Object,
    prodis: Array,
    dosens: Array,
});

const currentYear = new Date().getFullYear();

const form = reactive({
    judul: props.penelitian.judul || '',
    pegawai_id: props.penelitian.pegawai_id || '',
    prodi_id: props.penelitian.prodi_id || '',
    tahun: props.penelitian.tahun || currentYear,
    tingkat: props.penelitian.tingkat || 'Lokal',
    status: props.penelitian.status || 'Berjalan',
    sumber_dana: props.penelitian.sumber_dana || '',
    jumlah_dana: props.penelitian.jumlah_dana || '',
    anggota: props.penelitian.anggota || '',
});

const processing = ref(false);

function submit() {
    processing.value = true;
    router.put(`/penelitian/${props.penelitian.id}`, form, {
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
