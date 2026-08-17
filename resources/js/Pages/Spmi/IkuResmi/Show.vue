<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    iku: Object,
    dataInputs: Array,
    hasil: Object,
    periodes: Array,
    periodeId: [String, Number],
    triwulan: String,
    triwulanOptions: Object,
});

const selectedPeriode = ref(props.periodeId || '');
const selectedTriwulan = ref(props.triwulan || 'TAHUNAN');

const filterData = () => {
    router.get(`/iku-resmi/${props.iku.id}`, {
        periode_id: selectedPeriode.value,
        triwulan: selectedTriwulan.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const calculate = () => {
    router.post(`/iku-resmi/${props.iku.id}/calculate`, {
        periode_id: selectedPeriode.value,
        triwulan: selectedTriwulan.value,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Detail IKU-${iku.nomor_iku}: ${iku.nama_indikator || iku.nama}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/iku-resmi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar IKU
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            IKU-{{ iku.nomor_iku }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100/10 text-slate-200 border border-white/10">
                            {{ iku.sifat || 'WAJIB' }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        {{ iku.nama_indikator || iku.nama }}
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <select
                        v-model="selectedPeriode"
                        @change="filterData"
                        class="px-3 py-2 text-xs rounded-xl bg-white/15 text-white border border-white/20 focus:bg-slate-900 font-semibold"
                    >
                        <option v-for="p in periodes" :key="p.id" :value="p.id" class="text-slate-900">
                            {{ p.nama }} ({{ p.tahun }})
                        </option>
                    </select>

                    <button
                        @click="calculate"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-calculator"></i>
                        <span>Hitung Ulang</span>
                    </button>
                </div>
            </div>

            <!-- Score Summary Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase">Target Mutu</p>
                    <p class="text-2xl font-black text-slate-900">{{ iku.target_default || '-' }} {{ iku.satuan }}</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase">Capaian Realisasi</p>
                    <p class="text-2xl font-black text-indigo-600">{{ hasil?.nilai_hasil || 0 }} {{ iku.satuan }}</p>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-1">
                    <p class="text-[11px] font-bold text-slate-400 uppercase">Status Capaian</p>
                    <p class="text-lg font-black" :class="hasil?.status_capaian === 'Tercapai' ? 'text-emerald-600' : 'text-amber-600'">
                        {{ hasil?.status_capaian || 'Belum Dihitung' }}
                    </p>
                </div>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Deskripsi & Definisi Operasional IKU</h3>
                <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ iku.definisi || iku.deskripsi || 'Standar indikator mutu kinerja utama berdasarkan Kepmendiktisaintek No 358/2025.' }}
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
