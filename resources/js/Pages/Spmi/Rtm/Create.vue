<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    judul_rapat: 'Rapat Tinjauan Manajemen (RTM) Siklus Mutu',
    tanggal_rapat: new Date().toISOString().split('T')[0],
    agenda: '1. Evaluasi Hasil AMI\n2. Umpan Balik Kepuasan Mahasiswa & Dosen\n3. Keputusan Peningkatan Standar',
    input_audit_internal: '',
    input_umpan_balik: '',
    input_kinerja_proses: '',
    input_status_tindakan: '',
    input_perubahan_sistem: '',
    input_rekomendasi: '',
    notulensi: '',
    output_keefektifan: '',
    output_perbaikan: '',
    output_sumber_daya: '',
    keputusan_manajemen: '',
    file_absensi: null,
});

const handleFileChange = (e) => {
    form.file_absensi = e.target.files[0];
};

const submit = () => {
    form.post('/rtm', {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat RTM Baru" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <a href="/rtm" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar RTM
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rapat Tinjauan Manajemen (RTM)</h1>
                <p class="text-xs text-slate-500 mt-0.5">Dokumentasikan input, pembahasan, dan keputusan strategis pimpinan (Pilar Pengendalian P4).</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Row 1: Judul & Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Judul Rapat <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.judul_rapat"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tanggal Rapat <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal_rapat"
                                type="date"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <!-- Agenda -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Agenda Pembahasan RTM
                        </label>
                        <textarea
                            v-model="form.agenda"
                            rows="3"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <!-- Bagian Input RTM (Standar SN-Dikti / ISO) -->
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Input Tinjauan Manajemen</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Hasil Audit Mutu Internal (AMI)</label>
                                <textarea
                                    v-model="form.input_audit_internal"
                                    rows="2"
                                    placeholder="Ringkasan temuan KTS dan kepatuhan standar..."
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                ></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Umpan Balik Kepuasan (Survei / Kuesioner)</label>
                                <textarea
                                    v-model="form.input_umpan_balik"
                                    rows="2"
                                    placeholder="Indeks kepuasan mahasiswa, tendik, dan dosen..."
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                ></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kinerja Proses & Capaian IKU</label>
                                <textarea
                                    v-model="form.input_kinerja_proses"
                                    rows="2"
                                    placeholder="Indikator kinerja yang tercapai dan belum..."
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                ></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Rekomendasi Peningkatan Mutu</label>
                                <textarea
                                    v-model="form.input_rekomendasi"
                                    rows="2"
                                    placeholder="Saran perbaikan dari unit atau pimpinan..."
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Notulensi & Keputusan Manajemen -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Notulensi Jalannya Rapat
                            </label>
                            <textarea
                                v-model="form.notulensi"
                                rows="3"
                                placeholder="Catatan dinamika pembahasan..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Keputusan / Kebijakan Strategis Pimpinan <span class="text-rose-500">*</span>
                            </label>
                            <textarea
                                v-model="form.keputusan_manajemen"
                                rows="3"
                                required
                                placeholder="Instruksi dan keputusan resmi pimpinan terkait perbaikan mutu..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Lampiran Daftar Hadir / Absensi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Lampiran Berkas Absensi / Dokumentasi RTM (PDF/Gambar - Max 5MB)
                        </label>
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                        />
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/rtm"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan RTM' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
