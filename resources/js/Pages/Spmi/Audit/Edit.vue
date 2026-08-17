<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    audit: Object,
    periodes: Array,
    auditors: Array,
    selectedAnggota: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    periode_id: props.audit.periode_id,
    nama_audit: props.audit.nama_audit,
    unit_yang_diaudit: props.audit.unit_yang_diaudit,
    ketua_auditor_id: props.audit.ketua_auditor_id,
    tanggal_audit: props.audit.tanggal_audit,
    opening_meeting: props.audit.opening_meeting || '',
    closing_meeting: props.audit.closing_meeting || '',
    tanggal_selesai: props.audit.tanggal_selesai || '',
    status: props.audit.status,
    lingkup_audit: props.audit.lingkup_audit || '',
    tujuan_audit: props.audit.tujuan_audit || '',
    catatan: props.audit.catatan || '',
    nomor_surat_tugas: props.audit.nomor_surat_tugas || '',
    tgl_surat_tugas: props.audit.tgl_surat_tugas || '',
    penandatangan_surat_tugas: props.audit.penandatangan_surat_tugas || '',
    jabatan_penandatangan: props.audit.jabatan_penandatangan || '',
    anggota_auditor: props.selectedAnggota,
});

const submit = () => {
    form.put(`/audit/${props.audit.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Audit: ${audit.kode_audit}`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <a :href="`/audit/${audit.id}`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Detail Audit
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Pelaksanaan Audit AMI</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui jadwal, status audit, atau tim penugasan auditor.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
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
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Unit Auditee yang Diaudit <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.unit_yang_diaudit"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                                Ketua Auditor <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.ketua_auditor_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="u in auditors" :key="u.id" :value="u.id">{{ u.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Status Audit <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold capitalize"
                            >
                                <option value="draft">Draft</option>
                                <option value="aktif">Aktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditutup">Ditutup</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tanggal Audit <span class="text-rose-500">*</span>
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Lingkup Audit
                            </label>
                            <textarea
                                v-model="form.lingkup_audit"
                                rows="3"
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
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            :href="`/audit/${audit.id}`"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Perbarui Audit' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
