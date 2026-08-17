<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    dokumen: Object,
    kategoris: Array,
    standars: Array,
});

const form = useForm({
    _method: 'PUT',
    kategori_id: props.dokumen.kategori_id,
    standar_ids: props.dokumen.standars ? props.dokumen.standars.map(s => s.id) : [],
    judul: props.dokumen.judul,
    unit_pemilik: props.dokumen.unit_pemilik,
    versi: props.dokumen.versi,
    tanggal_terbit: props.dokumen.tanggal_terbit,
    tanggal_kadaluarsa: props.dokumen.tanggal_kadaluarsa || '',
    status: props.dokumen.status,
    is_public: Boolean(props.dokumen.is_public),
    keterangan: props.dokumen.keterangan || '',
    file: null,
});

const handleFileChange = (e) => {
    form.file = e.target.files[0];
};

const submit = () => {
    form.post(`/dokumen/${props.dokumen.id}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Dokumen: ${dokumen.kode_dokumen}`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <a :href="`/dokumen/${dokumen.id}`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Detail Dokumen
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Dokumen Mutu</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui metadata dokumen atau unggah revisi berkas terbaru.</p>
            </div>

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
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                        />
                        <p v-if="form.errors.judul" class="text-rose-500 text-[11px] mt-1">{{ form.errors.judul }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.kategori_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                            Tautkan ke Standar Mutu
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-40 overflow-y-auto p-3 rounded-2xl bg-slate-50 border border-slate-200">
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
                            Ganti Berkas Lampiran (Opsional)
                        </label>
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                        />
                        <p v-if="dokumen.file_path" class="text-xs text-slate-400 mt-1">
                            Berkas saat ini: <a :href="`/dokumen/${dokumen.id}/download`" class="text-indigo-600 font-semibold underline">Unduh Berkas Tersimpan</a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Keterangan / Catatan
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            :href="`/dokumen/${dokumen.id}`"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Perbarui Dokumen' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
