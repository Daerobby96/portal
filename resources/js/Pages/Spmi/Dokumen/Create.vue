<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kategoris: Array,
    standars: Array,
});

const form = useForm({
    kategori_id: props.kategoris?.[0]?.id || '',
    standar_ids: [],
    judul: '',
    unit_pemilik: '',
    versi: '1.0',
    tanggal_terbit: new Date().toISOString().split('T')[0],
    tanggal_kadaluarsa: '',
    status: 'draft',
    is_public: false,
    keterangan: '',
    file: null,
});

const handleFileChange = (e) => {
    form.file = e.target.files[0];
};

const submit = () => {
    form.post('/dokumen', {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Unggah Dokumen Mutu Baru" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/dokumen" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Dokumen
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Penetapan (P1)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Unggah Dokumen Mutu Institusi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Daftarkan Kebijakan SPMI, Manual Mutu, Standar SPMI, Prosedur Operasional Standar (SOP), dan Formulir ke repositori resmi.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            <!-- Judul Dokumen -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Judul Dokumen <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.judul"
                                    type="text"
                                    required
                                    placeholder="Contoh: Standar Operasional Prosedur Penyusunan Kurikulum MBKM"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                />
                                <p v-if="form.errors.judul" class="text-rose-500 text-[11px] mt-1">{{ form.errors.judul }}</p>
                            </div>

                            <!-- Row 1: Kategori, Unit, Versi -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Kategori Dokumen <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.kategori_id"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Unit Pemilik <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.unit_pemilik"
                                        type="text"
                                        required
                                        placeholder="Contoh: LPM, Prodi TI, BAK, dsb."
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Versi Dokumen <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.versi"
                                        type="text"
                                        required
                                        placeholder="1.0"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono font-bold"
                                    />
                                </div>
                            </div>

                            <!-- Row 2: Tanggal Terbit, Kadaluarsa, Status -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Terbit <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_terbit"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Kadaluarsa
                                    </label>
                                    <input
                                        v-model="form.tanggal_kadaluarsa"
                                        type="date"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Status Dokumen <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.status"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option value="draft">Draft</option>
                                        <option value="review">Review</option>
                                        <option value="approved">Approved</option>
                                        <option value="obsolete">Obsolete</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Standar Mutu Terkait Multi-select -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Tautkan ke Standar Mutu SN-Dikti Terkait
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-48 overflow-y-auto p-3 rounded-2xl bg-slate-50 border border-slate-200">
                                    <label
                                        v-for="s in standars"
                                        :key="s.id"
                                        class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium cursor-pointer hover:border-indigo-300"
                                    >
                                        <input
                                            v-model="form.standar_ids"
                                            type="checkbox"
                                            :value="s.id"
                                            class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="truncate">{{ s.kode }} - {{ s.nama }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- File Upload Input -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Berkas Lampiran Dokumen (PDF, Word, Excel - Max 20MB)
                                </label>
                                <input
                                    type="file"
                                    @change="handleFileChange"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                                />
                                <p v-if="form.errors.file" class="text-rose-500 text-[11px] mt-1">{{ form.errors.file }}</p>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Keterangan / Ringkasan Dokumen
                                </label>
                                <textarea
                                    v-model="form.keterangan"
                                    rows="3"
                                    placeholder="Catatan tambahan mengenai dokumen..."
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                ></textarea>
                            </div>

                            <!-- Submit Actions -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/dokumen"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Mengunggah...' : 'Simpan Dokumen Mutu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <!-- Card 1: Hierarki Dokumen SPMI -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-diagram-3 text-indigo-600"></i>
                            <span>Hierarki Dokumen Mutu SPMI</span>
                        </h4>
                        <ul class="text-[11px] text-slate-600 space-y-2 leading-relaxed">
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Level 1: Kebijakan SPMI</strong>
                                Dasar filosofis dan komitmen pimpinan perguruan tinggi.
                            </li>
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Level 2: Manual Mutu (PPEPP)</strong>
                                Petunjuk operasional pelaksanaan 5 pilar siklus SPMI.
                            </li>
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Level 3: Standar SPMI & SOP</strong>
                                Patokan ukuran mutu kuantitatif & kualitatif unit kerja.
                            </li>
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Level 4: Formulir & Rekaman</strong>
                                Bukti kerja nyata, checklist, kuesioner, dan instrumen.
                            </li>
                        </ul>
                    </div>

                    <!-- Card 2: Panduan Masa Berlaku -->
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-950/20 border border-white/10 space-y-2.5">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-200 flex items-center gap-2">
                            <i class="bi bi-clock-history"></i>
                            <span>Masa Berlaku Dokumen</span>
                        </h4>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Dokumen mutu disarankan ditinjau ulang (*review*) setidaknya sekali dalam 2–3 tahun atau setiap ada perubahan regulasi Kemendiktisaintek.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
