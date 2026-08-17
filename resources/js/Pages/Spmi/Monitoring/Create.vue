<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    indikators: Array,
    periodes: Array,
});

const urlParams = new URLSearchParams(window.location.search);
const defaultIndikatorId = urlParams.get('indikator_id') || props.indikators?.[0]?.id || '';
const defaultPeriodeId = urlParams.get('periode_id') || props.periodes?.[0]?.id || '';

const form = useForm({
    periode_id: defaultPeriodeId,
    indikator_id: defaultIndikatorId,
    nilai_capaian: '',
    tanggal_input: new Date().toISOString().split('T')[0],
    keterangan: '',
    bukti_dokumen: null,
});

const handleFileChange = (e) => {
    form.bukti_dokumen = e.target.files[0];
};

const submit = () => {
    form.post('/monitoring', {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Input Realisasi Capaian Indikator Mutu" />

        <div class="max-w-3xl mx-auto space-y-6">
            <div>
                <a href="/monitoring" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Monitoring
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Input Capaian Indikator Mutu</h1>
                <p class="text-xs text-slate-500 mt-0.5">Catat bukti dan realisasi numerik indikator kinerja institusi.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Periode Mutu <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.periode_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="p in periodes" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.tahun }})</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tanggal Pengukuran <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal_input"
                                type="date"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pilih Indikator Kinerja <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.indikator_id"
                            required
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                        >
                            <option v-for="ind in indikators" :key="ind.id" :value="ind.id">
                                {{ ind.kode }} - {{ ind.nama || ind.nama_indikator }} (Target: {{ ind.target }} {{ ind.satuan }})
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nilai Realisasi Capaian <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.nilai_capaian"
                            type="number"
                            step="any"
                            required
                            placeholder="Contoh: 85.5"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-indigo-700 text-base"
                        />
                        <p v-if="form.errors.nilai_capaian" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nilai_capaian }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Unggah Dokumen Bukti Pendukung (PDF/Word/Gambar - Max 10MB)
                        </label>
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept=".pdf,.doc,.docx,.jpg,.png"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Keterangan / Analisa Pelaksanaan
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            placeholder="Penjelasan metode pengukuran atau catatan capaian..."
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/monitoring"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Capaian' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
