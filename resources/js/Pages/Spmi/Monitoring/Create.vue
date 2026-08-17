<script setup>
import { computed } from 'vue';
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

const selectedIndikator = computed(() => {
    return props.indikators?.find(i => String(i.id) === String(form.indikator_id));
});

const isTercapai = computed(() => {
    if (!selectedIndikator.value || form.nilai_capaian === '' || form.nilai_capaian === null) return null;
    const target = parseFloat(selectedIndikator.value.target || selectedIndikator.value.target_nilai || 0);
    const capaian = parseFloat(form.nilai_capaian || 0);
    return capaian >= target;
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

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/monitoring" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Monitoring
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Pelaksanaan & Pemantauan (P2)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Input Capaian Indikator Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Catat realisasi data numerik, lampirkan dokumen bukti fisik/digital, dan verifikasi ketercapaian target standar mutu institusi.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
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
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
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
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
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
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                >
                                    <option v-for="ind in indikators" :key="ind.id" :value="ind.id">
                                        {{ ind.kode }} - {{ ind.nama || ind.nama_indikator }} (Target: {{ ind.target || ind.target_nilai }} {{ ind.satuan }})
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Nilai Realisasi Capaian Numerik <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        v-model="form.nilai_capaian"
                                        type="number"
                                        step="any"
                                        required
                                        placeholder="Contoh: 85.50"
                                        class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold text-indigo-700 bg-indigo-50/20"
                                    />
                                    <span v-if="selectedIndikator" class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">
                                        {{ selectedIndikator.satuan }}
                                    </span>
                                </div>
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
                                    placeholder="Penjelasan metode pengukuran, sumber data, atau catatan pelaksanaan..."
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/monitoring"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Capaian Indikator' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    
                    <!-- Live Target vs Achievement Card -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-speedometer2 text-indigo-600"></i>
                            <span>Evaluasi Ketercapaian Target</span>
                        </h4>

                        <div v-if="selectedIndikator" class="space-y-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1.5">
                                <p class="text-[11px] text-slate-400 font-bold uppercase">Target Standar</p>
                                <p class="text-lg font-black text-slate-900">
                                    {{ selectedIndikator.target || selectedIndikator.target_nilai }} {{ selectedIndikator.satuan }}
                                </p>
                            </div>

                            <div class="p-3.5 rounded-2xl border text-xs space-y-1.5" :class="isTercapai === true ? 'bg-emerald-50 border-emerald-200' : isTercapai === false ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-100'">
                                <p class="text-[11px] font-bold uppercase" :class="isTercapai === true ? 'text-emerald-600' : isTercapai === false ? 'text-rose-600' : 'text-slate-400'">
                                    Status Proyeksi
                                </p>
                                <p class="text-base font-extrabold" :class="isTercapai === true ? 'text-emerald-700' : isTercapai === false ? 'text-rose-700' : 'text-slate-600'">
                                    {{ isTercapai === true ? '✓ TARGET TERCAPAI' : isTercapai === false ? '⚠ BELUM TERCAPAI' : 'Masukkan nilai capaian' }}
                                </p>
                            </div>
                        </div>

                        <div v-else class="text-center py-6 text-xs text-slate-400">
                            Pilih salah satu indikator di form untuk melihat rincian target mutu.
                        </div>
                    </div>

                    <!-- Ketentuan Bukti Dokumen -->
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-950/20 border border-white/10 space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-200 flex items-center gap-2">
                            <i class="bi bi-file-earmark-check"></i>
                            <span>Ketentuan Bukti Sah</span>
                        </h4>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Pastikan berkas dokumen yang diunggah memuat stempel, tanda tangan pejabat berwenang, atau data analitik resmi sistem kampus.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
