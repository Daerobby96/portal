<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    standars: Array,
    tipeOptions: Object,
});

const form = useForm({
    standar_id: props.standars?.[0]?.id || '',
    kode: '',
    nama: '',
    deskripsi: '',
    tipe: 'IKU',
    target: '',
    satuan: '%',
    unit_penanggung_jawab: '',
    baseline: '',
    is_aktif: true,
});

const submit = () => {
    form.post('/indikator-kinerja');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Indikator Kinerja SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/indikator-kinerja" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Indikator
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Penetapan (P1) - Indikator Mutu
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tambah Indikator Kinerja (IKU / IKT)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Definisikan tolok ukur kuantitatif, target capaian berkala, serta unit kerja penanggung jawab pencapaian standar.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Kode Indikator <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.kode"
                                        type="text"
                                        required
                                        placeholder="IKU-01"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    <p v-if="form.errors.kode" class="text-rose-500 text-[11px] mt-1">{{ form.errors.kode }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nama Indikator Kinerja <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        placeholder="Contoh: Persentase lulusan yang langsung bekerja < 6 bulan"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.nama" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Standar Mutu Acuan <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.standar_id"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option v-for="s in standars" :key="s.id" :value="s.id">{{ s.kode }} - {{ s.nama }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tipe Indikator <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.tipe"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="IKU">Indikator Kinerja Utama (IKU)</option>
                                        <option value="IKT">Indikator Kinerja Tambahan (IKT)</option>
                                        <option value="custom">Custom / Spesifik</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Target Mutu <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.target"
                                        type="text"
                                        required
                                        placeholder="Contoh: 80"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-slate-900"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Satuan Ukuran <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.satuan"
                                        type="text"
                                        required
                                        placeholder="%, Orang, Judul, dsb."
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Unit Penanggung Jawab
                                    </label>
                                    <input
                                        v-model="form.unit_penanggung_jawab"
                                        type="text"
                                        placeholder="Prodi, BAAK, LPPM"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Definisi Operasional & Metode Perhitungan
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="4"
                                    placeholder="Jelaskan formula perhitungan (Numerator / Denominator) atau cara pengukuran data..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/indikator-kinerja"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Indikator Kinerja' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-bullseye text-indigo-600"></i>
                            <span>Prinsip SMART pada Indikator</span>
                        </h4>
                        <ul class="text-[11px] text-slate-600 space-y-2 leading-relaxed">
                            <li class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <strong>Specific:</strong> Rumusan indikator jelas dan tidak multitafsir.
                            </li>
                            <li class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <strong>Measurable:</strong> Memiliki target numerik dan satuan yang pasti.
                            </li>
                            <li class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <strong>Achievable & Relevant:</strong> Realistis dicapai dan selaras dengan Renstra.
                            </li>
                            <li class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                                <strong>Time-bound:</strong> Diukur dalam periode berkala (Semester/Tahunan).
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
