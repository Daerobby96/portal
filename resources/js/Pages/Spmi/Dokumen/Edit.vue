<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    dokumen: Object,
    kategoris: Array,
    standars: Array,
    selectedStandars: Array,
});

const form = useForm({
    kategori_id: props.dokumen.kategori_id,
    standar_ids: props.selectedStandars || [],
    judul: props.dokumen.judul,
    unit_pemilik: props.dokumen.unit_pemilik,
    versi: props.dokumen.versi || '1.0',
    tanggal_terbit: props.dokumen.tanggal_terbit,
    tanggal_kadaluarsa: props.dokumen.tanggal_kadaluarsa || '',
    status: props.dokumen.status || 'draft',
    is_public: Boolean(props.dokumen.is_public),
    keterangan: props.dokumen.keterangan || '',
    file: null,
});

const handleFileChange = (e) => {
    form.file = e.target.files[0];
};

const submit = () => {
    form.post(`/dokumen/${props.dokumen.id}`, {
        _method: 'put',
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Dokumen: ${dokumen.judul}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/dokumen" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Dokumen
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            {{ dokumen.kode }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/20 text-white">
                            {{ dokumen.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Dokumen Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Perbarui metadata, versi dokumen, status persetujuan, atau unggah revisi berkas terbaru.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Judul Dokumen <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.judul"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Kategori <span class="text-rose-500">*</span>
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
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono font-bold"
                                    />
                                </div>
                            </div>

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

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Tautkan ke Standar Mutu SN-Dikti
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

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Ganti Berkas Dokumen (Opsional - Kosongkan jika tidak diganti)
                                </label>
                                <input
                                    type="file"
                                    @change="handleFileChange"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Keterangan / Ringkasan Revisi
                                </label>
                                <textarea
                                    v-model="form.keterangan"
                                    rows="3"
                                    placeholder="Catatan alasan perubahan atau revisi..."
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                ></textarea>
                            </div>

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
                                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Dokumen Mutu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Metadata Dokumen</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-2">
                            <p><strong>Kode:</strong> <span class="font-mono text-indigo-600">{{ dokumen.kode }}</span></p>
                            <p><strong>Dibuat:</strong> {{ dokumen.created_at || '-' }}</p>
                            <p><strong>Terakhir Diperbarui:</strong> {{ dokumen.updated_at || '-' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
