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
        <Head title="Tambah Indikator Mutu" />

        <div class="max-w-3xl mx-auto space-y-6">
            <div>
                <a href="/indikator-kinerja" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Indikator
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Indikator Kinerja</h1>
                <p class="text-xs text-slate-500 mt-0.5">Definisikan target numerik dan standar acuan SPMI.</p>
            </div>

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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"
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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
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
                            Deskripsi / Definisi Operasional
                        </label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="3"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/indikator-kinerja"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Indikator' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
