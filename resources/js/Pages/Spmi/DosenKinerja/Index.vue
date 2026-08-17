<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kinerjas: Array,
    periodes: Array,
    selectedPeriodeId: [String, Number],
});

const selectedPeriode = ref(props.selectedPeriodeId || '');

const filterPeriode = () => {
    router.get('/kinerja-dosen', {
        periode_id: selectedPeriode.value,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Evaluasi Dosen Oleh Mahasiswa (EDOM)" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-person-badge"></i>
                        <span>Evaluasi Kinerja Dosen</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Rapor Kinerja Dosen (EDOM)
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Rekapitulasi skor evaluasi pembelajaran dosen (Pedagogik, Profesional, Kepribadian, Sosial) oleh mahasiswa.
                    </p>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="text-sm font-bold text-slate-900">Peringkat & Skor Rerata Dosen</h3>

                    <div>
                        <select
                            v-model="selectedPeriode"
                            @change="filterPeriode"
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
                                <th class="py-3.5 px-6">Nama Dosen & NIDN</th>
                                <th class="py-3.5 px-6">Program Studi</th>
                                <th class="py-3.5 px-6 text-center">Pedagogik</th>
                                <th class="py-3.5 px-6 text-center">Profesional</th>
                                <th class="py-3.5 px-6 text-center">Kepribadian</th>
                                <th class="py-3.5 px-6 text-center">Sosial</th>
                                <th class="py-3.5 px-6 text-center">Skor Rerata</th>
                                <th class="py-3.5 px-6 text-right">Rapor PDF</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="k in kinerjas"
                                :key="k.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ k.dosen_name }}
                                    <p class="text-[10px] text-slate-400 font-normal font-mono">NIDN: {{ k.dosen_nidn || '-' }}</p>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ k.prodi || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-700">
                                    {{ k.rerata_pedagogik || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-700">
                                    {{ k.rerata_profesional || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-700">
                                    {{ k.rerata_kepribadian || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-700">
                                    {{ k.rerata_sosial || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-black text-xs border border-emerald-200">
                                        {{ k.total_rerata || 0 }} / 4.00
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a
                                        :href="`/kinerja-dosen/${k.id}/export-pdf`"
                                        class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition inline-flex items-center gap-1.5"
                                    >
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        <span>Unduh Rapor</span>
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="!kinerjas || kinerjas.length === 0">
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    Belum ada data evaluasi kinerja dosen pada periode ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
