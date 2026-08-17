<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    integratedData: Object,
    periode: Object,
    periodes: Array,
});

const selectedPeriodeId = ref(props.periode?.id || '');

const filterPeriode = () => {
    router.get('/integrasi', {
        periode_id: selectedPeriodeId.value,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Integrasi Data Lintas Modul" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-diagram-3"></i>
                        <span>Single Source of Truth</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Integrasi Data Lintas Modul ERP
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Konsolidasi data otomatis dari Modul Kemahasiswaan, SDM Pegawai, Penelitian & Pengabdian (Tridharma), Kerjasama, dan Tracer Study ke instrumen SPMI.
                    </p>
                </div>

                <div>
                    <select
                        v-model="selectedPeriodeId"
                        @change="filterPeriode"
                        class="px-4 py-2.5 rounded-2xl bg-white/15 text-white text-xs font-bold border border-white/20 focus:outline-none focus:bg-slate-900"
                    >
                        <option v-for="p in periodes" :key="p.id" :value="p.id" class="text-slate-900">
                            Periode: {{ p.nama }} ({{ p.tahun }})
                        </option>
                    </select>
                </div>
            </div>

            <!-- Integrated Modules Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Modul 1: Kemahasiswaan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <i class="bi bi-people"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Terhubung</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Kemahasiswaan & Prestasi</h3>
                    <div class="text-xs text-slate-600 space-y-1 pt-2 border-t border-slate-100">
                        <p>Total Mahasiswa: <span class="font-bold text-slate-900">{{ integratedData?.mahasiswa?.total || 0 }}</span></p>
                        <p>Prestasi Nasional/Intl: <span class="font-bold text-slate-900">{{ integratedData?.mahasiswa?.prestasi || 0 }}</span></p>
                    </div>
                </div>

                <!-- Modul 2: SDM Pegawai -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Terhubung</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">SDM & Dosen</h3>
                    <div class="text-xs text-slate-600 space-y-1 pt-2 border-t border-slate-100">
                        <p>Total Dosen Tetap: <span class="font-bold text-slate-900">{{ integratedData?.pegawai?.total_dosen || 0 }}</span></p>
                        <p>Dosen Bergelar S3/Doktor: <span class="font-bold text-slate-900">{{ integratedData?.pegawai?.doktor || 0 }}</span></p>
                    </div>
                </div>

                <!-- Modul 3: Penelitian & PkM -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">Terhubung</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Tridharma (Riset & PkM)</h3>
                    <div class="text-xs text-slate-600 space-y-1 pt-2 border-t border-slate-100">
                        <p>Judul Penelitian: <span class="font-bold text-slate-900">{{ integratedData?.penelitian?.total || 0 }}</span></p>
                        <p>Publikasi Scopus/Sinta: <span class="font-bold text-slate-900">{{ integratedData?.publikasi?.total || 0 }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
