<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    penilaian: Object,
});

const verifyPenilaian = () => {
    if (confirm('Verifikasi resmi hasil penilaian kinerja ini?')) {
        router.post(`/sdm/penilaian-kinerja/${props.penilaian.id}/verify`);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Rincian Penilaian Kinerja Pegawai" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/penilaian-kinerja" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Penilaian
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Tahun {{ penilaian.tahun }} ({{ penilaian.periode }})
                        </span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase"
                            :class="penilaian.status === 'verified' ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : 'bg-slate-500/30 text-slate-200 border border-slate-400/30'"
                        >
                            {{ penilaian.status === 'verified' ? 'Terverifikasi' : 'Draft' }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Penilaian Kinerja Pegawai
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Evaluasi capaian sasaran kinerja {{ penilaian.pegawai?.nama }}.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        v-if="penilaian.status !== 'verified'"
                        @click="verifyPenilaian"
                        class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-emerald-600/30 cursor-pointer"
                    >
                        <i class="bi bi-patch-check"></i>
                        <span>Verifikasi Resmi</span>
                    </button>
                    <Link
                        v-if="penilaian.status !== 'verified'"
                        :href="`/sdm/penilaian-kinerja/${penilaian.id}/edit`"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold transition flex items-center gap-2"
                    >
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit</span>
                    </Link>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Details Card -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Pegawai Yang Dinilai</span>
                                <p class="font-bold text-slate-900 text-sm">{{ penilaian.pegawai?.nama }}</p>
                                <span class="text-[11px] text-slate-400 font-mono">{{ penilaian.pegawai?.nip || 'No NIP' }} | {{ penilaian.pegawai?.unit_kerja }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Atasan Penilai</span>
                                <p class="font-bold text-indigo-700 text-sm">{{ penilaian.penilai?.name || 'Administrator' }}</p>
                                <span class="text-[11px] text-slate-500">{{ penilaian.penilai?.jabatan || 'Pimpinan Unit' }}</span>
                            </div>
                        </div>

                        <!-- 5 Breakdown Nilai -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Rincian Skor Tiap Aspek</h4>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="text-slate-500 block mb-1">Kedisiplinan</span>
                                    <span class="text-lg font-black font-mono text-slate-900">{{ penilaian.nilai_disiplin }}</span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="text-slate-500 block mb-1">Kinerja</span>
                                    <span class="text-lg font-black font-mono text-slate-900">{{ penilaian.nilai_kinerja }}</span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="text-slate-500 block mb-1">Loyalitas</span>
                                    <span class="text-lg font-black font-mono text-slate-900">{{ penilaian.nilai_loyalitas }}</span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="text-slate-500 block mb-1">Kreativitas</span>
                                    <span class="text-lg font-black font-mono text-slate-900">{{ penilaian.nilai_kreativitas }}</span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center sm:col-span-2">
                                    <span class="text-slate-500 block mb-1">Kerjasama Tim</span>
                                    <span class="text-lg font-black font-mono text-slate-900">{{ penilaian.nilai_kerjasama }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-if="penilaian.catatan_atasan">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Catatan & Evaluasi Atasan</span>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-800 leading-relaxed">
                                {{ penilaian.catatan_atasan }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4 text-center">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hasil Rata-rata Skor</span>
                        <p class="text-5xl font-black text-indigo-900 font-mono">{{ penilaian.nilai_total }}</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-600 text-white uppercase tracking-wider">
                            {{ penilaian.predikat?.replace('_', ' ') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
