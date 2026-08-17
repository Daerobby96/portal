<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    bidangOptions: Object,
    jenisOptions: Object,
});

const form = useForm({
    kode: '',
    nama: '',
    deskripsi: '',
    bidang: 'pendidikan',
    jenis: 'inti',
    nomor: '',
    is_aktif: true,
});

const submit = () => {
    form.post('/standar');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Standar Mutu Baru" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/standar" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Standar
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Penetapan Standar Mutu (P1)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tambah Standar Mutu SPMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Definisikan standar mutu turunan Standar Nasional Pendidikan Tinggi (SN-Dikti) atau Standar Tambahan Perguruan Tinggi.
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
                                        Kode Standar <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.kode"
                                        type="text"
                                        required
                                        placeholder="STD-PEND-01"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    <p v-if="form.errors.kode" class="text-rose-500 text-[11px] mt-1">{{ form.errors.kode }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nama Standar <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        placeholder="Contoh: Standar Kompetensi Lulusan"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.nama" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Bidang Standar <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.bidang"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none capitalize font-semibold"
                                    >
                                        <option v-for="(label, key) in bidangOptions" :key="key" :value="key">{{ label }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Jenis Standar <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.jenis"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none capitalize font-semibold"
                                    >
                                        <option v-for="(label, key) in jenisOptions" :key="key" :value="key">{{ label }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nomor Urut
                                    </label>
                                    <input
                                        v-model="form.nomor"
                                        type="number"
                                        placeholder="1"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Deskripsi & Sasaran Mutu Standar
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="4"
                                    placeholder="Tuliskan pernyataan atau sasaran mutu standar ini secara terukur..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/standar"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Standar Mutu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-bookmarks text-indigo-600"></i>
                            <span>Klasifikasi Standar Mutu</span>
                        </h4>
                        <ul class="text-[11px] text-slate-600 space-y-2 leading-relaxed">
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Standar Inti (SN-Dikti)</strong>
                                Standar wajib nasional (Pendidikan, Penelitian, dan Pengabdian kepada Masyarakat).
                            </li>
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Standar Tambahan (IKT)</strong>
                                Standar keunggulan khas institusi (Tata Kelola, Kerjasama, AI & Teknologi).
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
