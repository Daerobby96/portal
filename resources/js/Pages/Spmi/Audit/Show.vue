<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    audit: Object,
    statsTemuan: Object,
    statsChecklist: Object,
    indikators: Array,
});

const activeTab = ref('checklist');
const temuanModalOpen = ref(false);

const temuanForm = useForm({
    kategori: 'KTS_Minor',
    uraian_temuan: '',
    akar_penyebab: '',
    bukti_objektif: '',
    batas_tindak_lanjut: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
});

const openTemuanModal = () => {
    temuanForm.reset();
    temuanModalOpen.value = true;
};

const submitTemuan = () => {
    temuanForm.post(`/audit/${props.audit.id}/temuan`, {
        onSuccess: () => {
            temuanModalOpen.value = false;
        },
    });
};

const generateChecklist = () => {
    if (confirm('Apakah Anda ingin meng-generate otomatis butir checklist instrumen berdasarkan data monitoring?')) {
        router.post(`/audit/${props.audit.id}/generate-checklist`);
    }
};

const updateChecklistStatus = (item, newStatus) => {
    router.put(`/audit/${props.audit.id}/checklist/${item.id}`, {
        status: newStatus,
        catatan: item.catatan || '',
        bukti_objektif: item.bukti_objektif || '',
    }, {
        preserveScroll: true,
    });
};

const deleteAudit = () => {
    if (confirm('Apakah Anda yakin ingin menghapus audit ini?')) {
        router.delete(`/audit/${props.audit.id}`);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`${audit.kode_audit} - ${audit.unit_yang_diaudit}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/audit" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Audit
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 font-mono font-bold text-xs">
                            {{ audit.kode_audit }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/20 text-white">
                            {{ audit.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1">
                        {{ audit.nama_audit }}
                    </h1>
                    <p class="text-xs text-slate-300 mt-1">
                        Unit Auditee: <span class="font-bold text-white">{{ audit.unit_yang_diaudit }}</span> | Lead Auditor: <span class="font-bold text-white">{{ audit.ketua_auditor?.name || '-' }}</span> | Periode: <span class="font-bold text-white">{{ audit.periode?.nama || '-' }}</span>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a
                        :href="`/audit/${audit.id}/cetak-bapa`"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-indigo-600/30"
                    >
                        <i class="bi bi-file-earmark-pdf"></i>
                        <span>Cetak BAPA</span>
                    </a>
                    <a
                        :href="`/audit/${audit.id}/edit`"
                        class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="bi bi-pencil"></i>
                        <span>Edit Audit</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase">Checklist Terisi</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">
                        {{ statsChecklist?.sesuai + statsChecklist?.tidak_sesuai || 0 }} / {{ statsChecklist?.total || 0 }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-emerald-600 uppercase">Sesuai Standar</p>
                    <p class="text-xl font-bold text-emerald-700 mt-0.5">{{ statsChecklist?.sesuai || 0 }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-rose-600 uppercase">Temuan KTS</p>
                    <p class="text-xl font-bold text-rose-700 mt-0.5">
                        {{ (statsTemuan?.kts_mayor || 0) + (statsTemuan?.kts_minor || 0) }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-amber-600 uppercase">Observasi (OB)</p>
                    <p class="text-xl font-bold text-amber-700 mt-0.5">{{ statsTemuan?.observasi || 0 }}</p>
                </div>
            </div>

            <!-- Main Working Area Card with Tabs -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-6">
                <!-- Tab Headers -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <button
                            @click="activeTab = 'checklist'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
                            :class="activeTab === 'checklist' ? 'bg-indigo-50 text-indigo-700 shadow-xs' : 'text-slate-500 hover:bg-slate-100'"
                        >
                            Instrumen Checklist ({{ audit.checklists?.length || 0 }})
                        </button>
                        <button
                            @click="activeTab = 'temuan'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
                            :class="activeTab === 'temuan' ? 'bg-rose-50 text-rose-700 shadow-xs' : 'text-slate-500 hover:bg-slate-100'"
                        >
                            Daftar Temuan Audit ({{ audit.temuans?.length || 0 }})
                        </button>
                        <button
                            @click="activeTab = 'info'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer"
                            :class="activeTab === 'info' ? 'bg-slate-100 text-slate-800 shadow-xs' : 'text-slate-500 hover:bg-slate-100'"
                        >
                            Info Tim & Jadwal
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            v-if="activeTab === 'checklist'"
                            @click="generateChecklist"
                            class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                        >
                            <i class="bi bi-magic"></i>
                            <span>Generate Checklist AI</span>
                        </button>
                        <button
                            v-if="activeTab === 'temuan'"
                            @click="openTemuanModal"
                            class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Temuan</span>
                        </button>
                    </div>
                </div>

                <!-- Tab 1: Checklist Instrumen -->
                <div v-if="activeTab === 'checklist'" class="space-y-4">
                    <div
                        v-for="(item, idx) in audit.checklists"
                        :key="item.id"
                        class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition space-y-3"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="font-mono font-bold text-xs text-indigo-600 mr-2">
                                    {{ item.indikator?.kode || `Item #${idx+1}` }}
                                </span>
                                <span class="text-xs font-bold text-slate-800">
                                    {{ item.indikator?.nama_indikator || item.pertanyaan }}
                                </span>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    Standar: {{ item.indikator?.standar?.nama || '-' }}
                                </p>
                            </div>

                            <!-- Status Dropdown / Button Group -->
                            <div class="flex items-center gap-2 shrink-0">
                                <button
                                    @click="updateChecklistStatus(item, 'sesuai')"
                                    class="px-3 py-1 rounded-lg text-xs font-bold transition cursor-pointer"
                                    :class="item.status === 'sesuai' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                >
                                    Sesuai
                                </button>
                                <button
                                    @click="updateChecklistStatus(item, 'tidak_sesuai')"
                                    class="px-3 py-1 rounded-lg text-xs font-bold transition cursor-pointer"
                                    :class="item.status === 'tidak_sesuai' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                >
                                    KTS
                                </button>
                                <button
                                    @click="updateChecklistStatus(item, 'observasi')"
                                    class="px-3 py-1 rounded-lg text-xs font-bold transition cursor-pointer"
                                    :class="item.status === 'observasi' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                >
                                    OB
                                </button>
                            </div>
                        </div>

                        <div v-if="item.catatan || item.bukti_objektif" class="p-3 rounded-xl bg-slate-50 text-xs text-slate-600 space-y-1">
                            <p v-if="item.bukti_objektif"><strong>Bukti Objektif:</strong> {{ item.bukti_objektif }}</p>
                            <p v-if="item.catatan"><strong>Catatan Auditor:</strong> {{ item.catatan }}</p>
                        </div>
                    </div>

                    <div v-if="!audit.checklists || audit.checklists.length === 0" class="text-center py-12 text-slate-400 text-xs">
                        Belum ada instrumen checklist audit. Klik tombol "Generate Checklist AI" untuk mengimpor dari data standar & monev.
                    </div>
                </div>

                <!-- Tab 2: Daftar Temuan Audit -->
                <div v-if="activeTab === 'temuan'" class="space-y-4">
                    <div
                        v-for="tem in audit.temuans"
                        :key="tem.id"
                        class="p-4 rounded-2xl border border-rose-100 bg-rose-50/20 space-y-3"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-xs text-rose-700">{{ tem.kode_temuan }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-rose-100 text-rose-800">
                                    {{ tem.kategori }}
                                </span>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">
                                Batas: {{ tem.batas_tindak_lanjut || '-' }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ tem.uraian_temuan }}</p>
                            <p class="text-[11px] text-slate-600 mt-1"><strong>Bukti:</strong> {{ tem.bukti_objektif }}</p>
                        </div>
                    </div>

                    <div v-if="!audit.temuans || audit.temuans.length === 0" class="text-center py-12 text-slate-400 text-xs">
                        Belum ada temuan ketidaksesuaian yang dicatat pada audit ini.
                    </div>
                </div>

                <!-- Tab 3: Info Tim & Jadwal -->
                <div v-if="activeTab === 'info'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl bg-slate-50 space-y-3 text-xs">
                        <h4 class="font-bold text-slate-800 text-sm">Tim Auditor AMI</h4>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Ketua Auditor</span>
                            <span class="font-bold text-slate-900">{{ audit.ketua_auditor?.name || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Anggota Auditor</span>
                            <div class="space-y-1 mt-1">
                                <span
                                    v-for="u in audit.auditors"
                                    :key="u.id"
                                    class="inline-block px-2 py-1 rounded-md bg-white border border-slate-200 font-semibold mr-1 mb-1 text-slate-700"
                                >
                                    {{ u.name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 space-y-3 text-xs">
                        <h4 class="font-bold text-slate-800 text-sm">Surat Tugas & Jadwal</h4>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Nomor Surat Tugas</span>
                            <span class="font-bold text-slate-900">{{ audit.nomor_surat_tugas || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Tanggal Pelaksanaan</span>
                            <span class="font-bold text-slate-900">{{ audit.tanggal_audit }} s/d {{ audit.tanggal_selesai || 'Selesai' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Temuan -->
        <div
            v-if="temuanModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="temuanModalOpen = false"
        >
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Catat Temuan Audit Baru</h3>
                    <button @click="temuanModalOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitTemuan" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Temuan</label>
                        <select
                            v-model="temuanForm.kategori"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 font-semibold"
                        >
                            <option value="KTS_Mayor">KTS Mayor (Ketidaksesuaian Mayor)</option>
                            <option value="KTS_Minor">KTS Minor (Ketidaksesuaian Minor)</option>
                            <option value="OB">Observasi (OB)</option>
                            <option value="Rekomendasi">Rekomendasi Peningkatan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Uraian Temuan Ketidaksesuaian <span class="text-rose-500">*</span></label>
                        <textarea
                            v-model="temuanForm.uraian_temuan"
                            required
                            rows="3"
                            placeholder="Deskripsikan fakta temuan di lapangan..."
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bukti Objektif</label>
                        <input
                            v-model="temuanForm.bukti_objektif"
                            type="text"
                            placeholder="Dokumen SK, catatan log, dsb."
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Waktu Penyelesaian</label>
                        <input
                            v-model="temuanForm.batas_tindak_lanjut"
                            type="date"
                            required
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200"
                        />
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="temuanModalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="temuanForm.processing"
                            class="px-5 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-xs"
                        >
                            Simpan Temuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
