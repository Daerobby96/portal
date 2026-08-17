<script setup>
import { ref, reactive, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
    indikators: Array,
    periodes: Array,
    stats: Object,
    periodeSel: Object,
});

const searchQuery = ref('');
const selectedPeriodeId = ref(props.periodeSel?.id || '');

// Local reactive state for inline input values & saving indicators
const inlineValues = reactive({});
const savingState = reactive({});
const savedState = reactive({});

// Initialize inline values from props
if (props.indikators) {
    props.indikators.forEach((ind) => {
        const val = ind.monitorings?.[0]?.nilai_capaian ?? '';
        inlineValues[ind.id] = val !== '' && val !== null ? parseFloat(val) : '';
    });
}

const filterData = () => {
    router.get('/monitoring', {
        search: searchQuery.value,
        periode_id: selectedPeriodeId.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const getTargetValue = (ind) => {
    return parseFloat(ind.target || ind.target_nilai || 0);
};

const isTercapai = (ind) => {
    const val = inlineValues[ind.id];
    if (val === '' || val === null || isNaN(val)) return null;

    const capaian = parseFloat(val);
    const target = getTargetValue(ind);

    if (ind.nama && ind.nama.toLowerCase().includes('waktu tunggu')) {
        return capaian <= target;
    }
    return capaian >= target;
};

const saveInline = async (ind) => {
    const val = inlineValues[ind.id];
    if (val === '' || val === null || isNaN(val)) return;

    savingState[ind.id] = true;
    savedState[ind.id] = false;

    try {
        const res = await axios.post('/monitoring/inline', {
            indikator_id: ind.id,
            periode_id: selectedPeriodeId.value,
            field: 'nilai_capaian',
            value: val,
        });

        if (res.data.success) {
            savedState[ind.id] = true;
            if (!ind.monitorings || ind.monitorings.length === 0) {
                ind.monitorings = [{}];
            }
            ind.monitorings[0].nilai_capaian = val;
            ind.monitorings[0].is_tercapai = res.data.is_tercapai;

            setTimeout(() => {
                savedState[ind.id] = false;
            }, 2500);
        }
    } catch (err) {
        alert(err.response?.data?.message || 'Gagal menyimpan nilai capaian.');
    } finally {
        savingState[ind.id] = false;
    }
};

const syncSiakad = () => {
    if (confirm('Apakah Anda ingin menyinkronkan data indikator akademik dari SIAKAD sekarang?')) {
        router.post('/monitoring/sync-siakad');
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Monitoring Capaian IKU/IKT SPMI" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Pelaksanaan & Pengukuran (P2)</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Monitoring Indikator Mutu (IKU / IKT)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Pengisian capaian realisasi secara <strong>Live Inline</strong> langsung pada tabel instrumen mutu.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <button
                        @click="syncSiakad"
                        class="px-4 py-2.5 rounded-2xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Sync SIAKAD</span>
                    </button>
                    <a
                        href="/monitoring/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Input Form Lengkap</span>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Total Indikator</p>
                        <p class="text-xl font-bold text-slate-900">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-emerald-600 uppercase">Tercapai</p>
                        <p class="text-xl font-bold text-emerald-700">{{ stats?.tercapai || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-rose-600 uppercase">Belum Tercapai</p>
                        <p class="text-xl font-bold text-rose-700">{{ stats?.tidak || 0 }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase">Belum Dievaluasi</p>
                        <p class="text-xl font-bold text-slate-800">{{ stats?.belum_eval || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card with Live Inline Input -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex-1 max-w-md relative flex items-center">
                        <i class="bi bi-search absolute left-3.5 text-slate-400 text-xs pointer-events-none"></i>
                        <input
                            v-model="searchQuery"
                            @keyup.enter="filterData"
                            type="text"
                            placeholder="Cari indikator kinerja atau kode..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex items-center gap-3">
                        <select
                            v-model="selectedPeriodeId"
                            @change="filterData"
                            class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                        >
                            <option v-for="p in periodes" :key="p.id" :value="p.id">
                                Periode: {{ p.nama }} ({{ p.tahun }})
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode & Nama Indikator</th>
                                <th class="py-3.5 px-6">Standar Mutu</th>
                                <th class="py-3.5 px-6">Target Mutu</th>
                                <th class="py-3.5 px-6 w-56">Realisasi</th>
                                <th class="py-3.5 px-6 whitespace-nowrap">Status Capaian</th>
                                <th class="py-3.5 px-6 text-right">Lampiran Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="ind in indikators"
                                :key="ind.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <span class="font-mono font-bold text-indigo-600 block">{{ ind.kode }}</span>
                                    <span class="font-semibold text-slate-900">{{ ind.nama || ind.nama_indikator }}</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Unit: {{ ind.unit_kerja || ind.unit_penanggung_jawab || '-' }}</p>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ ind.standar?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ ind.target || ind.target_nilai }} {{ ind.satuan || ind.unit_pengukuran }}
                                </td>

                                <!-- Inline Input Cell -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <input
                                                type="number"
                                                step="any"
                                                v-model="inlineValues[ind.id]"
                                                @blur="saveInline(ind)"
                                                @keyup.enter="saveInline(ind)"
                                                placeholder="0.00"
                                                class="w-full px-3 py-1.5 text-xs rounded-xl border font-bold text-slate-900 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                :class="savedState[ind.id] ? 'bg-emerald-50 border-emerald-400' : 'bg-white border-slate-200'"
                                            />
                                        </div>
                                        <span class="text-[11px] font-semibold text-slate-400">
                                            {{ ind.satuan || ind.unit_pengukuran }}
                                        </span>
                                        <span v-if="savingState[ind.id]" class="text-indigo-600 text-xs animate-spin">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </span>
                                        <span v-else-if="savedState[ind.id]" class="text-emerald-600 text-xs font-bold">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    </div>
                                    <span class="text-[9px] text-slate-400 block mt-0.5">Tekan Enter / Klik luar untuk simpan</span>
                                </td>

                                <!-- Status Capaian -->
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span
                                        v-if="isTercapai(ind) === true"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-emerald-50 text-emerald-700 border-emerald-200 inline-flex items-center gap-1"
                                    >
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Tercapai</span>
                                    </span>
                                    <span
                                        v-else-if="isTercapai(ind) === false"
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-rose-50 text-rose-700 border-rose-200 inline-flex items-center gap-1"
                                    >
                                        <i class="bi bi-x-circle-fill"></i>
                                        <span>Belum Tercapai</span>
                                    </span>
                                    <span
                                        v-else
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 inline-flex items-center"
                                    >
                                        Belum Diisi
                                    </span>
                                </td>

                                <!-- Form Lengkap / Bukti -->
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/monitoring/create?indikator_id=${ind.id}&periode_id=${selectedPeriodeId}`"
                                        class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold transition inline-flex items-center gap-1"
                                        title="Unggah berkas bukti atau catatan analisa"
                                    >
                                        <i class="bi bi-paperclip"></i>
                                        <span>Unggah Bukti</span>
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!indikators || indikators.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    Tidak ada indikator mutu ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
