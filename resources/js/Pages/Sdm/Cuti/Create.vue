<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pegawais: Array,
});

const form = useForm({
    pegawai_id: '',
    jenis_cuti: 'tahunan',
    tanggal_mulai: new Date().toISOString().split('T')[0],
    tanggal_selesai: '',
    alasan: '',
    file_pendukung: null,
});

const submit = () => {
    form.post('/sdm/cuti');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pengajuan Cuti Pegawai" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/cuti" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Cuti
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Permohonan Cuti & Izin
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Formulir Pengajuan Cuti
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Lengkapi rincian jenis cuti, rentang tanggal pelaksanaan, dan alasan pengajuan untuk diverifikasi oleh pimpinan.
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
                                        Pilih Pegawai <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.pegawai_id"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="">-- Pilih Pegawai --</option>
                                        <option v-for="p in pegawais" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.nip || 'No NIP' }})</option>
                                    </select>
                                    <p v-if="form.errors.pegawai_id" class="text-rose-500 text-[11px] mt-1">{{ form.errors.pegawai_id }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Jenis Cuti <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.jenis_cuti"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="tahunan">Cuti Tahunan</option>
                                        <option value="sakit">Cuti Sakit</option>
                                        <option value="melahirkan">Cuti Melahirkan</option>
                                        <option value="besar">Cuti Besar</option>
                                        <option value="alasan_penting">Cuti Alasan Penting</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Mulai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_mulai"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    />
                                    <p v-if="form.errors.tanggal_mulai" class="text-rose-500 text-[11px] mt-1">{{ form.errors.tanggal_mulai }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Selesai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_selesai"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    />
                                    <p v-if="form.errors.tanggal_selesai" class="text-rose-500 text-[11px] mt-1">{{ form.errors.tanggal_selesai }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Alasan & Keperluan Cuti <span class="text-rose-500">*</span>
                                </label>
                                <textarea
                                    v-model="form.alasan"
                                    rows="4"
                                    required
                                    placeholder="Jelaskan alasan permohonan cuti secara rinci..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                                <p v-if="form.errors.alasan" class="text-rose-500 text-[11px] mt-1">{{ form.errors.alasan }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Unggah Berkas Pendukung (Surat Dokter / Dokumen Resmi)
                                </label>
                                <input
                                    type="file"
                                    @change="e => form.file_pendukung = e.target.files[0]"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/sdm/cuti"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Mengajukan...' : 'Ajukan Cuti' }}
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
                            <span>Ketentuan Cuti Pegawai</span>
                        </h4>
                        <div class="space-y-2 text-xs text-slate-600 leading-relaxed">
                            <p><strong>Cuti Tahunan:</strong> Kuota 12 hari kerja per tahun setelah masa kerja minimal 1 tahun.</p>
                            <p><strong>Cuti Sakit:</strong> Diwajibkan mengunggah surat keterangan dokter jika lebih dari 2 hari.</p>
                            <p><strong>Cuti Melahirkan:</strong> Diberikan 3 bulan sesuai peraturan ketenagakerjaan.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
