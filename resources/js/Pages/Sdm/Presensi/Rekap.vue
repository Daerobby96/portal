<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rekapData: Array,
    bulan: Number,
    tahun: Number,
});

const selectedBulan = ref(props.bulan);
const selectedTahun = ref(props.tahun);

const months = [
    { id: 1, name: 'Januari' }, { id: 2, name: 'Februari' }, { id: 3, name: 'Maret' },
    { id: 4, name: 'April' }, { id: 5, name: 'Mei' }, { id: 6, name: 'Juni' },
    { id: 7, name: 'Juli' }, { id: 8, name: 'Agustus' }, { id: 9, name: 'September' },
    { id: 10, name: 'Oktober' }, { id: 11, name: 'November' }, { id: 12, name: 'Desember' }
];

const handleFilter = () => {
    router.get('/sdm/presensi/rekap', {
        bulan: selectedBulan.value,
        tahun: selectedTahun.value,
    }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Rekapitulasi Kehadiran Pegawai" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/presensi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Presensi Harian
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Rekapitulasi Bulanan
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Rekapitulasi Presensi Pegawai
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Akumulasi hari hadir, cuti, izin sakit, dan dinas luar seluruh civitas akademika untuk evaluasi kedisiplinan dan perhitungan tunjangan.
                    </p>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Month / Year Selector -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3">
                    <select
                        v-model="selectedBulan"
                        @change="handleFilter"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option v-for="m in months" :key="m.id" :value="m.id">{{ m.name }}</option>
                    </select>

                    <input
                        v-model="selectedTahun"
                        @change="handleFilter"
                        type="number"
                        class="w-24 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold font-mono text-slate-900 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Nama Pegawai</th>
                                <th class="py-3.5 px-4">Unit Kerja</th>
                                <th class="py-3.5 px-3 text-center text-emerald-700">Hadir</th>
                                <th class="py-3.5 px-3 text-center text-amber-700">Izin</th>
                                <th class="py-3.5 px-3 text-center text-blue-700">Sakit</th>
                                <th class="py-3.5 px-3 text-center text-purple-700">Cuti</th>
                                <th class="py-3.5 px-3 text-center text-indigo-700">Dinas Luar</th>
                                <th class="py-3.5 px-3 text-center text-rose-700">Alpa</th>
                                <th class="py-3.5 px-6 text-center">Total Entri</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr
                                v-for="r in rekapData"
                                :key="r.pegawai.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ r.pegawai.nama }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ r.pegawai.nip || '-' }}</div>
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ r.pegawai.unit_kerja || '-' }}
                                </td>
                                <td class="py-4 px-3 text-center font-bold text-emerald-600 font-mono">
                                    {{ r.hadir }}
                                </td>
                                <td class="py-4 px-3 text-center font-bold text-amber-600 font-mono">
                                    {{ r.izin }}
                                </td>
                                <td class="py-4 px-3 text-center font-bold text-blue-600 font-mono">
                                    {{ r.sakit }}
                                </td>
                                <td class="py-4 px-3 text-center font-bold text-purple-600 font-mono">
                                    {{ r.cuti }}
                                </td>
                                <td class="py-4 px-3 text-center font-bold text-indigo-600 font-mono">
                                    {{ r.dinas_luar }}
                                </td>
                                <td class="py-4 px-3 text-center font-bold text-rose-600 font-mono">
                                    {{ r.alpa }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-slate-900 font-mono">
                                    {{ r.total }}
                                </td>
                            </tr>

                            <tr v-if="!rekapData || rekapData.length === 0">
                                <td colspan="9" class="py-12 text-center text-slate-400">
                                    Belum ada data rekapitulasi pegawai aktif.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
