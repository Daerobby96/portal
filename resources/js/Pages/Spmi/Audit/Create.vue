<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    periodes: Array,
    auditors: Array,
    kodeAudit: String,
});

const form = useForm({
    periode_id: props.periodes?.[0]?.id || '',
    nama_audit: 'Audit Mutu Internal Semester Ini',
    unit_yang_diaudit: '',
    ketua_auditor_id: props.auditors?.[0]?.id || '',
    tanggal_audit: new Date().toISOString().split('T')[0],
    opening_meeting: '',
    closing_meeting: '',
    tanggal_selesai: '',
    lingkup_audit: '',
    tujuan_audit: '',
    catatan: '',
    nomor_surat_tugas: '',
    tgl_surat_tugas: '',
    penandatangan_surat_tugas: '',
    jabatan_penandatangan: '',
    anggota_auditor: [],
});

const submit = () => {
    form.post('/audit');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat Pelaksanaan Audit Mutu Baru" />

        <div class="space-y-6">
            <!-- Top Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/audit" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Audit
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Pilar Evaluasi (P3) - Audit Mutu Internal
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Penugasan & Rencana Pelaksanaan AMI
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Tunjuk Lead Auditor, tetapkan unit auditee, jadwalkan opening/closing meeting, dan terbitkan surat tugas resmi.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Section: Data Utama -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="bi bi-card-checklist text-indigo-600"></i>
                                    <span>Informasi Pokok Audit</span>
                                </h3>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Nama Pelaksanaan Audit <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.nama_audit"
                                            type="text"
                                            required
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                        />
                                        <p v-if="form.errors.nama_audit" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama_audit }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Unit Auditee yang Diaudit <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.unit_yang_diaudit"
                                            type="text"
                                            required
                                            placeholder="Contoh: Program Studi S1 Informatika"
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                        />
                                        <p v-if="form.errors.unit_yang_diaudit" class="text-rose-500 text-[11px] mt-1">{{ form.errors.unit_yang_diaudit }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Periode Mutu <span class="text-rose-500">*</span>
                                        </label>
                                        <select
                                            v-model="form.periode_id"
                                            required
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                        >
                                            <option v-for="p in periodes" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.tahun }})</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Ketua Auditor (Lead Auditor) <span class="text-rose-500">*</span>
                                        </label>
                                        <select
                                            v-model="form.ketua_auditor_id"
                                            required
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                        >
                                            <option v-for="u in auditors" :key="u.id" :value="u.id">
                                                {{ u.name }} {{ u.prodi ? `(${u.prodi.nama})` : '' }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Jadwal Meeting -->
                            <div class="space-y-4">
                                <h3 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="bi bi-calendar-range text-indigo-600"></i>
                                    <span>Jadwal & Agenda Lapangan</span>
                                </h3>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Tanggal Audit Lapangan <span class="text-rose-500">*</span>
                                        </label>
                                        <input
                                            v-model="form.tanggal_audit"
                                            type="date"
                                            required
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Opening Meeting
                                        </label>
                                        <input
                                            v-model="form.opening_meeting"
                                            type="date"
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                            Closing Meeting
                                        </label>
                                        <input
                                            v-model="form.closing_meeting"
                                            type="date"
                                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Tim Auditor -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Pilih Anggota Tim Auditor Tambahan
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-48 overflow-y-auto p-3 rounded-2xl bg-slate-50 border border-slate-200">
                                    <label
                                        v-for="u in auditors"
                                        :key="u.id"
                                        class="flex items-center gap-2 p-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium cursor-pointer hover:border-indigo-300"
                                    >
                                        <input
                                            v-model="form.anggota_auditor"
                                            type="checkbox"
                                            :value="u.id"
                                            class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="truncate font-semibold text-slate-800">{{ u.name }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Lingkup & Tujuan -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Lingkup Audit (Kriteria Standar)
                                    </label>
                                    <textarea
                                        v-model="form.lingkup_audit"
                                        rows="3"
                                        placeholder="Contoh: Standar Kompetensi Lulusan, Pembelajaran, dan Dosen Tendik"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tujuan Audit
                                    </label>
                                    <textarea
                                        v-model="form.tujuan_audit"
                                        rows="3"
                                        placeholder="Contoh: Memastikan kesesuaian implementasi sistem pembelajaran OBE..."
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- Surat Tugas Info -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Surat Tugas & Legalitas AMI</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Surat Tugas</label>
                                        <input
                                            v-model="form.nomor_surat_tugas"
                                            type="text"
                                            placeholder="123/ST/LPM/2026"
                                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Tanggal Surat</label>
                                        <input
                                            v-model="form.tgl_surat_tugas"
                                            type="date"
                                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Penandatangan</label>
                                        <input
                                            v-model="form.penandatangan_surat_tugas"
                                            type="text"
                                            placeholder="Direktur / Ketua LPM"
                                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Actions -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/audit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan & Terbitkan Jadwal AMI' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-shield-check text-emerald-600"></i>
                            <span>Prinsip Utama Auditor AMI</span>
                        </h4>
                        <ul class="text-[11px] text-slate-600 space-y-2 leading-relaxed">
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Independensi & Objektivitas</strong>
                                Auditor dilarang mengaudit program studi / unit asalnya sendiri (*Conflict of Interest*).
                            </li>
                            <li class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <strong class="text-slate-900 block">Berbasis Bukti Nyata</strong>
                                Setiap temuan KTS wajib didasarkan pada dokumen rekaman fisik atau digital yang valid.
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
