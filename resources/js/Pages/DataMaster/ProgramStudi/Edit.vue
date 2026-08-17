<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    program_studi: Object,
});

const form = useForm({
    kode: props.program_studi.kode,
    nama: props.program_studi.nama,
    jenjang: props.program_studi.jenjang,
    akreditasi: props.program_studi.akreditasi || '',
    deskripsi: props.program_studi.deskripsi || '',
    is_aktif: Boolean(props.program_studi.is_aktif),
});

const submit = () => {
    form.put(`/program-studi/${props.program_studi.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Program Studi: ${program_studi.nama}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/program-studi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Master Program Studi
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 font-mono">
                            {{ program_studi.kode }}
                        </span>
                        <span v-if="program_studi.akreditasi" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/30 text-emerald-200 border border-emerald-400/30">
                            Akreditasi {{ program_studi.akreditasi }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Program Studi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Perbarui profil program studi, penyesuaian peringkat akreditasi, dan status keaktifan institusi.
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
                                        Kode Prodi (PDDikti) <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.kode"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-slate-900 font-mono"
                                    />
                                    <p v-if="form.errors.kode" class="text-rose-500 text-[11px] mt-1">{{ form.errors.kode }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nama Program Studi <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.nama" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Jenjang Pendidikan <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.jenjang"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="D3">Diploma 3 (D3)</option>
                                        <option value="D4">Diploma 4 / Sarjana Terapan (D4)</option>
                                        <option value="S1">Sarjana (S1)</option>
                                        <option value="S2">Magister (S2)</option>
                                        <option value="Profesi">Profesi</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Peringkat Akreditasi BAN-PT / LAM
                                    </label>
                                    <select
                                        v-model="form.akreditasi"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="">Belum Terakreditasi</option>
                                        <option value="Unggul">Unggul</option>
                                        <option value="Baik Sekali">Baik Sekali</option>
                                        <option value="Baik">Baik</option>
                                        <option value="A">A (Instrumen 7 Standar)</option>
                                        <option value="B">B (Instrumen 7 Standar)</option>
                                        <option value="C">C (Instrumen 7 Standar)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Profil & Deskripsi Singkat
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="3"
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="pt-2">
                                <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-indigo-50/60 border border-indigo-100 cursor-pointer hover:bg-indigo-50 transition">
                                    <input
                                        v-model="form.is_aktif"
                                        type="checkbox"
                                        class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">Status Aktif Operasional</span>
                                        <span class="text-[10px] text-slate-500">Program studi aktif menerima mahasiswa dan masuk dalam evaluasi SPMI & SIAKAD</span>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/program-studi"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Program Studi' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Info Ringkas</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-1.5">
                            <p><strong>Kode:</strong> {{ program_studi.kode }}</p>
                            <p><strong>Nama:</strong> {{ program_studi.nama }}</p>
                            <p><strong>Jenjang:</strong> {{ program_studi.jenjang }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
