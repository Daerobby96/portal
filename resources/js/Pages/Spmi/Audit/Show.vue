<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
    audit: Object,
    statsTemuan: Object,
    statsChecklist: Object,
    indikators: Array,
});

const activeTab = ref('checklist');
const temuanModalOpen = ref(false);
const editTemuanModalOpen = ref(false);
const editingTemuan = ref(null);
const checklistModalOpen = ref(false);
const editingChecklistItem = ref(null);
const isGeneratingAiSummary = ref(false);
const currentAiSummary = ref(props.audit.ai_summary || '');

const temuanForm = useForm({
    kategori: 'KTS_Minor',
    uraian_temuan: '',
    akar_penyebab: '',
    bukti_objektif: '',
    batas_tindak_lanjut: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
});

const editTemuanForm = useForm({
    kategori: 'KTS_Minor',
    klausul_standar: '',
    uraian_temuan: '',
    bukti_objektif: '',
    batas_tindak_lanjut: '',
    status: 'open',
});

const checklistForm = useForm({
    status: 'belum_diisi',
    catatan: '',
    bukti_objektif: '',
});

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    try {
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return d.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    } catch {
        return dateStr;
    }
};

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

const openEditTemuanModal = (t) => {
    editingTemuan.value = t;
    editTemuanForm.kategori = t.kategori || 'KTS_Minor';
    editTemuanForm.klausul_standar = t.klausul_standar || '';
    editTemuanForm.uraian_temuan = t.uraian_temuan || '';
    editTemuanForm.bukti_objektif = t.bukti_objektif || '';
    editTemuanForm.batas_tindak_lanjut = t.batas_tindak_lanjut ? t.batas_tindak_lanjut.split('T')[0] : '';
    editTemuanForm.status = t.status || 'open';
    editTemuanModalOpen.value = true;
};

const submitEditTemuan = () => {
    if (!editingTemuan.value) return;
    editTemuanForm.put(`/audit/${props.audit.id}/temuan/${editingTemuan.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            editTemuanModalOpen.value = false;
        },
    });
};

const deleteTemuan = (t) => {
    if (confirm(`Apakah Anda yakin ingin menghapus temuan [${t.kode_temuan}]?`)) {
        router.delete(`/audit/${props.audit.id}/temuan/${t.id}`, {
            preserveScroll: true,
        });
    }
};

const openChecklistEditModal = (item) => {
    editingChecklistItem.value = item;
    checklistForm.status = item.status || 'belum_diisi';
    checklistForm.catatan = item.catatan || '';
    checklistForm.bukti_objektif = item.bukti_objektif || '';
    checklistModalOpen.value = true;
};

const submitChecklistEdit = () => {
    if (!editingChecklistItem.value) return;
    checklistForm.put(`/audit/${props.audit.id}/checklist/${editingChecklistItem.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            checklistModalOpen.value = false;
        },
    });
};

const generateChecklist = () => {
    if (confirm('Apakah Anda ingin meng-generate otomatis butir checklist instrumen berdasarkan data monitoring?')) {
        router.post(`/audit/${props.audit.id}/generate-checklist`);
    }
};

const generateAiSummary = async () => {
    isGeneratingAiSummary.value = true;
    try {
        const res = await axios.post('/ai/audit-summary', {
            audit_id: props.audit.id,
        });

        if (res.data.status === 'success') {
            currentAiSummary.value = res.data.data;
        } else {
            alert(res.data.message || 'Gagal menghasilkan Ringkasan Eksekutif AI.');
        }
    } catch (err) {
        alert('Gagal terhubung dengan layanan AI.');
    } finally {
        isGeneratingAiSummary.value = false;
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
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`${audit.kode_audit} - ${audit.unit_yang_diaudit}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <Link href="/audit" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2 transition">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Daftar Audit</span>
                    </Link>
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
                        :href="`/cetak/berita-acara-ami/${audit.id}`"
                        target="_blank"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-indigo-600/30"
                        title="Cetak Dokumen Resmi Berita Acara AMI"
                    >
                        <i class="bi bi-printer-fill"></i>
                        <span>Cetak Berita Acara AMI</span>
                    </a>
                    <Link
                        :href="`/audit/${audit.id}/edit`"
                        class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="bi bi-pencil"></i>
                        <span>Edit Audit</span>
                    </Link>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase">Checklist Terisi</p>
                    <p class="text-xl font-bold text-slate-900 mt-0.5">
                        {{ (statsChecklist?.sesuai || 0) + (statsChecklist?.tidak_sesuai || 0) }} / {{ statsChecklist?.total || audit.checklists?.length || 0 }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-emerald-600 uppercase">Kesesuaian (KS)</p>
                    <p class="text-xl font-bold text-emerald-700 mt-0.5">{{ statsChecklist?.sesuai || 0 }}</p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-rose-600 uppercase">Temuan KTS</p>
                    <p class="text-xl font-bold text-rose-700 mt-0.5">
                        {{ audit.temuans?.length || 0 }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-amber-600 uppercase">Observasi (OB)</p>
                    <p class="text-xl font-bold text-amber-700 mt-0.5">{{ statsTemuan?.observasi || 0 }}</p>
                </div>
            </div>

            <!-- Tabs Nav -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                        <button
                            @click="activeTab = 'checklist'"
                            class="px-4 py-2 rounded-2xl text-xs font-bold transition cursor-pointer border"
                            :class="activeTab === 'checklist' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/20' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                        >
                            <i class="bi bi-list-check me-1"></i>
                            Daftar Tilik / Checklist AMI ({{ audit.checklists?.length || 0 }})
                        </button>

                        <button
                            @click="activeTab = 'temuan'"
                            class="px-4 py-2 rounded-2xl text-xs font-bold transition cursor-pointer border"
                            :class="activeTab === 'temuan' ? 'bg-rose-600 text-white border-rose-600 shadow-md shadow-rose-600/20' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                        >
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            Temuan Ketidaksesuaian ({{ audit.temuans?.length || 0 }})
                        </button>

                        <button
                            @click="activeTab = 'ai_summary'"
                            class="px-4 py-2 rounded-2xl text-xs font-bold transition cursor-pointer border"
                            :class="activeTab === 'ai_summary' ? 'bg-purple-600 text-white border-purple-600 shadow-md shadow-purple-600/20' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                        >
                            <i class="bi bi-stars me-1"></i>
                            Ringkasan Eksekutif AI
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            v-if="activeTab === 'checklist'"
                            @click="generateChecklist"
                            class="px-3.5 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                        >
                            <i class="bi bi-magic"></i>
                            <span>Generate Otomatis Checklist</span>
                        </button>

                        <button
                            v-if="activeTab === 'temuan'"
                            @click="openTemuanModal"
                            class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Temuan</span>
                        </button>
                    </div>
                </div>

                <!-- Tab 1: Checklist -->
                <div v-if="activeTab === 'checklist'" class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-700">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100 text-[11px]">
                                <tr>
                                    <th class="py-3.5 px-4 w-1/3">Pernyataan Indikator & Standar Mutu</th>
                                    <th class="py-3.5 px-4 text-center w-28">Status AMI</th>
                                    <th class="py-3.5 px-4">Bukti Objektif & Catatan Auditor</th>
                                    <th class="py-3.5 px-4 text-right w-44">Penilaian & Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr
                                    v-for="item in audit.checklists"
                                    :key="item.id"
                                    class="hover:bg-slate-50/70 transition"
                                >
                                    <td class="py-4 px-4">
                                        <span class="font-mono font-black text-indigo-600 block text-xs">{{ item.indikator?.kode || 'STD' }}</span>
                                        <p class="font-bold text-slate-900 text-xs mt-0.5">{{ item.indikator?.nama || item.pertanyaan }}</p>
                                        <span v-if="item.indikator?.standar" class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 mt-1">
                                            [{{ item.indikator.standar.kode }}] {{ item.indikator.standar.nama }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-4 text-center">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border whitespace-nowrap"
                                            :class="{
                                                'bg-emerald-50 text-emerald-700 border-emerald-200': item.status === 'sesuai',
                                                'bg-rose-50 text-rose-700 border-rose-200': item.status === 'tidak_sesuai' || item.status === 'kts_minor' || item.status === 'kts_mayor',
                                                'bg-amber-50 text-amber-700 border-amber-200': item.status === 'observasi',
                                                'bg-slate-100 text-slate-600 border-slate-200': item.status === 'belum_diisi' || !item.status,
                                            }"
                                        >
                                            {{ item.status === 'sesuai' ? 'Sesuai' : (item.status === 'tidak_sesuai' ? 'KTS (Tidak Sesuai)' : (item.status === 'observasi' ? 'Observasi' : 'Belum Diisi')) }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-4 text-xs text-slate-600">
                                        <div v-if="item.bukti_objektif" class="mb-1">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Bukti Objektif:</span>
                                            <p class="font-semibold text-slate-800">{{ item.bukti_objektif }}</p>
                                        </div>
                                        <div v-if="item.catatan">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Catatan Auditor:</span>
                                            <p class="text-slate-600 text-[11px] leading-relaxed">{{ item.catatan }}</p>
                                        </div>
                                        <span v-if="!item.bukti_objektif && !item.catatan" class="text-slate-400 italic">
                                            Belum ada catatan & bukti audit.
                                        </span>
                                    </td>

                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button
                                                @click="updateChecklistStatus(item, 'sesuai')"
                                                class="px-2 py-1 rounded-lg text-[10px] font-bold transition cursor-pointer"
                                                :class="item.status === 'sesuai' ? 'bg-emerald-600 text-white' : 'bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700'"
                                                title="Tandai Sesuai Standar (KS)"
                                            >
                                                KS
                                            </button>
                                            <button
                                                @click="updateChecklistStatus(item, 'observasi')"
                                                class="px-2 py-1 rounded-lg text-[10px] font-bold transition cursor-pointer"
                                                :class="item.status === 'observasi' ? 'bg-amber-500 text-white' : 'bg-slate-100 hover:bg-amber-50 text-slate-600 hover:text-amber-700'"
                                                title="Tandai Observasi (OB)"
                                            >
                                                OB
                                            </button>
                                            <button
                                                @click="updateChecklistStatus(item, 'tidak_sesuai')"
                                                class="px-2 py-1 rounded-lg text-[10px] font-bold transition cursor-pointer"
                                                :class="item.status === 'tidak_sesuai' ? 'bg-rose-600 text-white' : 'bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700'"
                                                title="Tandai Tidak Sesuai (KTS)"
                                            >
                                                KTS
                                            </button>
                                            <button
                                                @click="openChecklistEditModal(item)"
                                                class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition cursor-pointer flex items-center gap-1 text-[11px] font-bold"
                                                title="Edit Catatan & Bukti Objektif"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                <span>Catatan</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!audit.checklists || audit.checklists.length === 0">
                                    <td colspan="4" class="py-12 text-center text-slate-400">
                                        Belum ada butir instrumen checklist. Klik tombol <strong>"Generate Otomatis Checklist"</strong> di atas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Temuan Audit (Redesigned & Clear) -->
                <div v-if="activeTab === 'temuan'" class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-700">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100 text-[11px]">
                                <tr>
                                    <th class="py-3.5 px-4 w-32">Kode & Kategori</th>
                                    <th class="py-3.5 px-4 w-60">Indikator & Standar Acuan</th>
                                    <th class="py-3.5 px-4">Uraian Ketidaksesuaian (Temuan)</th>
                                    <th class="py-3.5 px-4 w-44">Bukti Objektif</th>
                                    <th class="py-3.5 px-4 text-center w-28">Batas Tindak Lanjut</th>
                                    <th class="py-3.5 px-4 text-center w-24">Status PTK</th>
                                    <th class="py-3.5 px-4 text-right w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr
                                    v-for="t in audit.temuans"
                                    :key="t.id"
                                    class="hover:bg-slate-50/70 transition"
                                >
                                    <!-- Kode & Kategori -->
                                    <td class="py-4 px-4 font-mono whitespace-nowrap">
                                        <span class="font-black text-rose-600 block text-xs">{{ t.kode_temuan }}</span>
                                        <span
                                            class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider mt-1 border"
                                            :class="{
                                                'bg-rose-50 text-rose-700 border-rose-200': t.kategori === 'KTS_Mayor',
                                                'bg-amber-50 text-amber-700 border-amber-200': t.kategori === 'KTS_Minor',
                                                'bg-blue-50 text-blue-700 border-blue-200': t.kategori === 'OB' || t.kategori === 'Observasi',
                                                'bg-emerald-50 text-emerald-700 border-emerald-200': t.kategori === 'Rekomendasi',
                                            }"
                                        >
                                            {{ t.kategori?.replace('_', ' ') }}
                                        </span>
                                    </td>

                                    <!-- Indikator & Standar -->
                                    <td class="py-4 px-4">
                                        <div v-if="t.checklist?.indikator">
                                            <span class="font-mono font-bold text-indigo-600 text-xs block">
                                                {{ t.checklist.indikator.kode }}
                                            </span>
                                            <p class="font-semibold text-slate-900 text-xs mt-0.5 line-clamp-2">
                                                {{ t.checklist.indikator.nama }}
                                            </p>
                                            <span v-if="t.checklist.indikator.standar" class="text-[10px] text-slate-500 block mt-0.5">
                                                Standar: {{ t.checklist.indikator.standar.kode }} - {{ t.checklist.indikator.standar.nama }}
                                            </span>
                                        </div>
                                        <div v-else class="text-slate-400 italic text-[11px]">
                                            {{ t.klausul_standar || 'Standar Institusi' }}
                                        </div>
                                    </td>

                                    <!-- Uraian Ketidaksesuaian -->
                                    <td class="py-4 px-4">
                                        <p class="font-medium text-slate-900 text-xs leading-relaxed">
                                            {{ t.uraian_temuan }}
                                        </p>
                                    </td>

                                    <!-- Bukti Objektif -->
                                    <td class="py-4 px-4 text-slate-700 font-medium">
                                        <p class="text-xs">{{ t.bukti_objektif || '-' }}</p>
                                    </td>

                                    <!-- Batas Tindak Lanjut -->
                                    <td class="py-4 px-4 text-center whitespace-nowrap font-medium text-slate-700 text-xs">
                                        <span class="px-2 py-1 bg-slate-100 rounded-lg text-slate-800">
                                            {{ formatDate(t.batas_tindak_lanjut) }}
                                        </span>
                                    </td>

                                    <!-- Status PTK -->
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                            :class="{
                                                'bg-rose-50 text-rose-700 border-rose-200': t.status === 'open',
                                                'bg-amber-50 text-amber-700 border-amber-200': t.status === 'in_progress' || t.status === 'proses',
                                                'bg-emerald-50 text-emerald-700 border-emerald-200': t.status === 'closed' || t.status === 'verified' || t.status === 'selesai',
                                            }"
                                        >
                                            {{ t.status }}
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            <button
                                                @click="openEditTemuanModal(t)"
                                                class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition cursor-pointer"
                                                title="Edit Uraian Temuan"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button
                                                @click="deleteTemuan(t)"
                                                class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 border border-rose-200 transition cursor-pointer"
                                                title="Hapus Temuan"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!audit.temuans || audit.temuans.length === 0">
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        Tidak ada temuan ketidaksesuaian pada audit ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: AI Executive Summary -->
                <div v-if="activeTab === 'ai_summary'" class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-purple-50/70 border border-purple-200/80">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center text-lg font-bold">
                                <i class="bi bi-stars"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">AI Ringkasan Eksekutif Hasil Audit</h4>
                                <p class="text-xs text-slate-500">Analisis otomatis tingkat kepatuhan, temuan kritis, dan saran strategis bagi pimpinan.</p>
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="generateAiSummary"
                            :disabled="isGeneratingAiSummary"
                            class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-purple-600/25 cursor-pointer disabled:opacity-50"
                        >
                            <i class="bi bi-arrow-repeat" :class="{ 'animate-spin': isGeneratingAiSummary }"></i>
                            <span>{{ isGeneratingAiSummary ? 'AI Sedang Merangkum...' : 'Generate Ringkasan AI' }}</span>
                        </button>
                    </div>

                    <div v-if="currentAiSummary" class="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 text-slate-800 text-xs leading-relaxed space-y-3 prose prose-xs max-w-none" v-html="currentAiSummary">
                    </div>
                    <div v-else class="py-12 text-center text-xs text-slate-400">
                        Belum ada ringkasan eksekutif AI. Klik tombol <strong>"Generate Ringkasan AI"</strong> di atas.
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Catatan & Bukti Auditor -->
        <div
            v-if="checklistModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="checklistModalOpen = false"
        >
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Catatan & Bukti Objektif Auditor</h3>
                        <p class="text-[11px] text-indigo-600 font-mono font-bold">{{ editingChecklistItem?.indikator?.kode || 'STD' }}</p>
                    </div>
                    <button @click="checklistModalOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div v-if="editingChecklistItem" class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs">
                    <p class="font-bold text-slate-900">{{ editingChecklistItem.indikator?.nama || editingChecklistItem.pertanyaan }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Standar: {{ editingChecklistItem.indikator?.standar?.nama || '-' }}</p>
                </div>

                <form @submit.prevent="submitChecklistEdit" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Status Penilaian AMI <span class="text-rose-500">*</span></label>
                        <select
                            v-model="checklistForm.status"
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold bg-white"
                        >
                            <option value="sesuai">✅ Sesuai Standar (KS)</option>
                            <option value="observasi">⚠️ Observasi (OB / Potensi Perbaikan)</option>
                            <option value="tidak_sesuai">❌ Tidak Sesuai (KTS - Ketidaksesuaian)</option>
                            <option value="tidak_terkait">⚪ Tidak Terkait</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Bukti Objektif yang Ditemukan</label>
                        <input
                            v-model="checklistForm.bukti_objektif"
                            type="text"
                            placeholder="Contoh: Dokumen RPS Gasal 2025, Logbook 16 Pertemuan, SK No. 12/2025"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 font-medium"
                        />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Catatan & Telaah Auditor Lapangan</label>
                        <textarea
                            v-model="checklistForm.catatan"
                            rows="4"
                            placeholder="Tuliskan hasil verifikasi dokumen, wawancara, atau kondisi faktual di lapangan..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 leading-relaxed font-medium"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="checklistModalOpen = false"
                            class="px-4 py-2 rounded-xl font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="checklistForm.processing"
                            class="px-5 py-2 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-xs"
                        >
                            {{ checklistForm.processing ? 'Menyimpan...' : 'Simpan Catatan & Bukti' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah Temuan -->
        <div
            v-if="temuanModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="temuanModalOpen = false"
        >
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Temuan Ketidaksesuaian (KTS)</h3>
                    <button @click="temuanModalOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitTemuan" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Kategori Temuan</label>
                        <select
                            v-model="temuanForm.kategori"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold bg-white"
                        >
                            <option value="KTS_Mayor">KTS Mayor (Ketidaksesuaian Berat/Sistemik)</option>
                            <option value="KTS_Minor">KTS Minor (Ketidaksesuaian Parsial)</option>
                            <option value="OB">Observasi (Potensi Peningkatan)</option>
                            <option value="Rekomendasi">Rekomendasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Uraian Temuan <span class="text-rose-500">*</span></label>
                        <textarea
                            v-model="temuanForm.uraian_temuan"
                            rows="3"
                            required
                            placeholder="Deskripsikan kondisi ketidaksesuaian yang ditemukan..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 font-medium"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Bukti Objektif</label>
                        <input
                            v-model="temuanForm.bukti_objektif"
                            type="text"
                            placeholder="Dokumen SK, catatan log, dsb."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 font-medium"
                        />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Batas Waktu Penyelesaian</label>
                        <input
                            v-model="temuanForm.batas_tindak_lanjut"
                            type="date"
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200"
                        />
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="temuanModalOpen = false"
                            class="px-4 py-2 rounded-xl font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="temuanForm.processing"
                            class="px-5 py-2 rounded-xl font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-xs"
                        >
                            Simpan Temuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Temuan -->
        <div
            v-if="editTemuanModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="editTemuanModalOpen = false"
        >
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Edit Temuan Ketidaksesuaian</h3>
                        <p class="text-[11px] font-mono font-bold text-rose-600">{{ editingTemuan?.kode_temuan }}</p>
                    </div>
                    <button @click="editTemuanModalOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitEditTemuan" class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Kategori</label>
                            <select
                                v-model="editTemuanForm.kategori"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold bg-white"
                            >
                                <option value="KTS_Mayor">KTS Mayor</option>
                                <option value="KTS_Minor">KTS Minor</option>
                                <option value="OB">Observasi</option>
                                <option value="Rekomendasi">Rekomendasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Status PTK</label>
                            <select
                                v-model="editTemuanForm.status"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 font-bold bg-white"
                            >
                                <option value="open">Open (Belum Selesai)</option>
                                <option value="in_progress">In Progress (Tindak Lanjut)</option>
                                <option value="closed">Closed (Tuntas/Selesai)</option>
                                <option value="verified">Verified (Terverifikasi)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Uraian Temuan <span class="text-rose-500">*</span></label>
                        <textarea
                            v-model="editTemuanForm.uraian_temuan"
                            rows="4"
                            required
                            placeholder="Deskripsikan secara detail ketidaksesuaian yang ditemukan..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 leading-relaxed font-medium"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Bukti Objektif</label>
                        <input
                            v-model="editTemuanForm.bukti_objektif"
                            type="text"
                            placeholder="Dokumen SK, berkas pendukung, dll."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 font-medium"
                        />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Batas Waktu Tindak Lanjut</label>
                        <input
                            v-model="editTemuanForm.batas_tindak_lanjut"
                            type="date"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200"
                        />
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="editTemuanModalOpen = false"
                            class="px-4 py-2 rounded-xl font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="editTemuanForm.processing"
                            class="px-5 py-2 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-xs"
                        >
                            {{ editTemuanForm.processing ? 'Menyimpan...' : 'Perbarui Temuan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
