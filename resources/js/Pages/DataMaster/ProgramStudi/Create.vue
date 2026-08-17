<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    kode: '',
    nama: '',
    jenjang: 'S1',
    akreditasi: '',
    deskripsi: '',
    is_aktif: true,
});

const submit = () => {
    form.post('/program-studi');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Program Studi" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/program-studi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Master Program Studi
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Struktur Akademik
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tambah Program Studi Baru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Daftarkan program studi tridharma baru beserta kode resmi PDDikti dan peringkat akreditasi.
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
                                        placeholder="Contoh: 55201"
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
                                        placeholder="Contoh: Teknik Informatika"
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
                                    placeholder="Fokus keilmuan prodi, visi keunggulan, atau catatan SK pendirian..."
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
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Program Studi' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-info-circle text-indigo-600"></i>
                            <span>Pedoman Master Prodi</span>
                        </h4>
                        <div class="space-y-2 text-xs text-slate-600 leading-relaxed">
                            <p><strong>Kode PDDikti:</strong> Pastikan kode program studi sesuai dengan nomor registrasi resmi di PDDikti Kemdiktisaintek.</p>
                            <p><strong>Akreditasi:</strong> Akreditasi Unggul/Baik Sekali mengacu pada instrumen 9 kriteria LAM/BAN-PT.</p>
                            <p class="text-[11px] text-slate-400">Data prodi akan otomatis menjadi target unit audit AMI pada modul SPMI.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
