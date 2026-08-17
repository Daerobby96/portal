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

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <a href="/audit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Audit
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Penugasan Audit AMI</h1>
                <p class="text-xs text-slate-500 mt-0.5">Rencanakan audit mutu internal, tunjuk tim auditor, dan jadwalkan evaluasi lapangan.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Row 1: Nama Audit & Unit Kerja -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nama Pelaksanaan Audit <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_audit"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                            />
                            <p v-if="form.errors.nama_audit" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama_audit }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Unit Auditee yang Diaudit <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.unit_yang_diaudit"
                                type="text"
                                required
                                placeholder="Contoh: Program Studi S1 Informatika"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                            <p v-if="form.errors.unit_yang_diaudit" class="text-rose-500 text-[11px] mt-1">{{ form.errors.unit_yang_diaudit }}</p>
                        </div>
                    </div>

                    <!-- Row 2: Periode & Ketua Auditor -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Periode Mutu <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.periode_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="p in periodes" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.tahun }})</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Ketua Auditor (Lead Auditor) <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.ketua_auditor_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="u in auditors" :key="u.id" :value="u.id">
                                    {{ u.name }} {{ u.prodi ? `(${u.prodi.nama})` : '' }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Jadwal (Tanggal Audit, Opening, Closing) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tanggal Audit Lapangan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal_audit"
                                type="date"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Opening Meeting
                            </label>
                            <input
                                v-model="form.opening_meeting"
                                type="date"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Closing Meeting
                            </label>
                            <input
                                v-model="form.closing_meeting"
                                type="date"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <!-- Anggota Auditor Multi-select -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pilih Anggota Tim Auditor
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-40 overflow-y-auto p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <label
                                v-for="u in auditors"
                                :key="u.id"
                                class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium cursor-pointer hover:border-indigo-300"
                            >
                                <input
                                    v-model="form.anggota_auditor"
                                    type="checkbox"
                                    :value="u.id"
                                    class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                />
                                <span class="truncate">{{ u.name }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Lingkup & Tujuan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Lingkup Audit
                            </label>
                            <textarea
                                v-model="form.lingkup_audit"
                                rows="3"
                                placeholder="Contoh: Standar Pendidikan, Penilaian Pembelajaran, dan Sarpras"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tujuan Audit
                            </label>
                            <textarea
                                v-model="form.tujuan_audit"
                                rows="3"
                                placeholder="Contoh: Memastikan kesesuaian implementasi kurikulum OBE..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Surat Tugas Info -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Surat Tugas Audit</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Surat</label>
                                <input
                                    v-model="form.nomor_surat_tugas"
                                    type="text"
                                    placeholder="Contoh: 123/ST/LPM/2026"
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
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Pelaksanaan Audit' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
