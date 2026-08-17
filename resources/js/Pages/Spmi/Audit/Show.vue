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
const isGeneratingAiSummary = ref(false);
const currentAiSummary = ref(props.audit.ai_summary || '');

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
                        :href="`/audit/${audit.id}/bapa-pdf`"
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
                        {{ statsChecklist?.terisi || 0 }} / {{ audit.checklists?.length || 0 }}
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-semibold text-emerald-600 uppercase">Kesesuaian (KS)</p>
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
                            @click="activeTab = 'ai_summary'"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5"
                            :class="activeTab === 'ai_summary' ? 'bg-purple-50 text-purple-700 border border-purple-200 shadow-xs' : 'text-slate-500 hover:bg-slate-100'"
                        >
                            <i class="bi bi-stars text-purple-600"></i>
                            <span>AI Ringkasan Eksekutif</span>
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
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Pernyataan Standar / Indikator</th>
                                    <th class="py-3 px-4 text-center">Status AMI</th>
                                    <th class="py-3 px-4">Bukti Objektif & Catatan Auditor</th>
                                    <th class="py-3 px-4 text-right">Penilaian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr
                                    v-for="item in audit.checklists"
                                    :key="item.id"
                                    class="hover:bg-slate-50/70 transition"
                                >
                                    <td class="py-4 px-4 max-w-sm">
                                        <p class="font-bold text-slate-900">{{ item.indikator?.kode || 'STD' }} - {{ item.indikator?.nama || 'Indikator Standar' }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ item.standar?.nama }}</p>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                            :class="{
                                                'bg-emerald-50 text-emerald-700 border border-emerald-200': item.status === 'sesuai',
                                                'bg-rose-50 text-rose-700 border border-rose-200': item.status === 'kts_minor' || item.status === 'kts_mayor',
                                                'bg-amber-50 text-amber-700 border border-amber-200': item.status === 'observasi',
                                                'bg-slate-100 text-slate-600': item.status === 'belum_diisi',
                                            }"
                                        >
                                            {{ item.status?.replace('_', ' ') || 'Belum Diisi' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-xs text-slate-600">
                                        <p v-if="item.bukti_objektif" class="font-semibold text-slate-800">Bukti: {{ item.bukti_objektif }}</p>
                                        <p v-if="item.catatan" class="text-slate-500 text-[11px]">{{ item.catatan }}</p>
                                        <span v-if="!item.bukti_objektif && !item.catatan" class="text-slate-400 italic">Belum ada catatan</span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex items-center gap-1">
                                            <button
                                                @click="updateChecklistStatus(item, 'sesuai')"
                                                class="px-2 py-1 rounded-lg text-[11px] font-bold transition cursor-pointer"
                                                :class="item.status === 'sesuai' ? 'bg-emerald-600 text-white' : 'bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700'"
                                                title="Sesuai Standar"
                                            >
                                                KS
                                            </button>
                                            <button
                                                @click="updateChecklistStatus(item, 'observasi')"
                                                class="px-2 py-1 rounded-lg text-[11px] font-bold transition cursor-pointer"
                                                :class="item.status === 'observasi' ? 'bg-amber-500 text-white' : 'bg-slate-100 hover:bg-amber-50 text-slate-600 hover:text-amber-700'"
                                                title="Observasi"
                                            >
                                                OB
                                            </button>
                                            <button
                                                @click="updateChecklistStatus(item, 'kts_minor')"
                                                class="px-2 py-1 rounded-lg text-[11px] font-bold transition cursor-pointer"
                                                :class="item.status === 'kts_minor' ? 'bg-rose-600 text-white' : 'bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700'"
                                                title="KTS Minor"
                                            >
                                                KTS-M
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

                <!-- Tab 2: Temuan Audit -->
                <div v-if="activeTab === 'temuan'" class="space-y-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-700">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4">Kode & Kategori</th>
                                    <th class="py-3 px-4">Uraian Ketidaksesuaian (Temuan)</th>
                                    <th class="py-3 px-4">Bukti Objektif</th>
                                    <th class="py-3 px-4">Batas Tindak Lanjut</th>
                                    <th class="py-3 px-4 text-center">Status PTK</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr
                                    v-for="t in audit.temuans"
                                    :key="t.id"
                                    class="hover:bg-slate-50/70 transition"
                                >
                                    <td class="py-4 px-4 font-mono">
                                        <span class="font-bold text-rose-600 block">{{ t.kode_temuan }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-rose-100 text-rose-800">
                                            {{ t.kategori }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-900 max-w-sm">
                                        {{ t.uraian_temuan }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ t.bukti_objektif || '-' }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-800 font-semibold">
                                        {{ t.batas_tindak_lanjut || '-' }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                            {{ t.status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!audit.temuans || audit.temuans.length === 0">
                                    <td colspan="5" class="py-12 text-center text-slate-400">
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

                <!-- Tab 4: Info Tim -->
                <div v-if="activeTab === 'info'" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-700">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold text-slate-400 uppercase">Jadwal Opening Meeting</p>
                            <p class="font-bold text-slate-900 mt-1">{{ audit.tanggal_mulai || '-' }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[11px] font-bold text-slate-400 uppercase">Jadwal Closing Meeting</p>
                            <p class="font-bold text-slate-900 mt-1">{{ audit.tanggal_selesai || '-' }}</p>
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
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Temuan Ketidaksesuaian (KTS)</h3>
                    <button @click="temuanModalOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitTemuan" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kategori Temuan</label>
                        <select
                            v-model="temuanForm.kategori"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 font-bold"
                        >
                            <option value="KTS_Mayor">KTS Mayor (Ketidaksesuaian Berat/Sistemik)</option>
                            <option value="KTS_Minor">KTS Minor (Ketidaksesuaian Parsial)</option>
                            <option value="Observasi">Observasi (Potensi Peningkatan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Uraian Temuan <span class="text-rose-500">*</span></label>
                        <textarea
                            v-model="temuanForm.uraian_temuan"
                            rows="3"
                            required
                            placeholder="Deskripsikan kondisi ketidaksesuaian yang ditemukan..."
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
