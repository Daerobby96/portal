<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    siklusSpmi: Object,
    ppepp: Object,
    availablePeriodes: Array,
    previousCycles: Array,
});

const closeCycle = () => {
    if (confirm('Apakah Anda yakin ingin menutup siklus mutu ini secara resmi? Snapshot capaian PPEPP akan dikunci.')) {
        router.post(`/siklus-spmi/${props.siklusSpmi.id}/close`);
    }
};

const ppeppPillars = computed(() => [
    { name: 'Penetapan (P1)', desc: 'Standar & Dokumen SPMI', percent: props.ppepp?.penetapan || 0, color: 'from-blue-600 to-indigo-600' },
    { name: 'Pelaksanaan (P2)', desc: 'Implementasi & Monitoring Kinerja', percent: props.ppepp?.pelaksanaan || 0, color: 'from-sky-500 to-blue-600' },
    { name: 'Evaluasi (P3)', desc: 'Audit Mutu Internal (AMI)', percent: props.ppepp?.evaluasi || 0, color: 'from-amber-500 to-orange-600' },
    { name: 'Pengendalian (P4)', desc: 'RTM & Tindak Lanjut Temuan', percent: props.ppepp?.pengendalian || 0, color: 'from-purple-500 to-indigo-600' },
    { name: 'Peningkatan (P5)', desc: 'Peningkatan Berkelanjutan (Kaizen)', percent: props.ppepp?.peningkatan || 0, color: 'from-emerald-500 to-teal-600' },
]);
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="siklusSpmi.nama" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
                <div class="relative z-10">
                    <a href="/siklus-spmi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Siklus
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                            {{ siklusSpmi.nama }}
                        </h1>
                        <span v-if="siklusSpmi.is_aktif" class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-500 text-white">
                            Aktif
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">
                        Tahun Siklus: {{ siklusSpmi.tahun_siklus }} | Penanggung Jawab: {{ siklusSpmi.penanggung_jawab?.name || '-' }}
                    </p>
                </div>

                <div class="relative z-10 flex flex-wrap items-center gap-2.5 shrink-0">
                    <button
                        v-if="siklusSpmi.status !== 'ditutup'"
                        @click="closeCycle"
                        class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-lock-fill"></i>
                        <span>Tutup Siklus Resmi</span>
                    </button>
                    <a
                        :href="`/siklus-spmi/${siklusSpmi.id}/edit`"
                        class="px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="bi bi-pencil"></i>
                        <span>Edit Siklus</span>
                    </a>
                </div>
            </div>

            <!-- PPEPP Aggregate Visual Progress -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 mb-6">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">
                            Agregasi Capaian PPEPP Siklus
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Rata-rata kumulatif seluruh periode yang ditautkan ke siklus ini.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider border"
                            :class="ppepp?.is_loop_closed ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200'"
                        >
                            {{ ppepp?.is_loop_closed ? 'Loop Lengkap (Kaizen)' : 'Sedang Berjalan' }}
                        </span>
                        <div class="text-2xl font-black text-indigo-600">
                            {{ ppepp?.overall || 0 }}%
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div
                        v-for="p in ppeppPillars"
                        :key="p.name"
                        class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 flex flex-col justify-between"
                    >
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">{{ p.name }}</span>
                            <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">{{ p.desc }}</span>
                        </div>
                        <div class="mt-4 pt-2 border-t border-slate-200/60">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1">
                                <span>Skor</span>
                                <span class="text-indigo-600">{{ p.percent }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div
                                    class="h-2 rounded-full bg-gradient-to-r"
                                    :class="p.color"
                                    :style="{ width: `${p.percent}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linked Periods & Cycle Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Linked Periods -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Periode Akademik Tertaut</h3>
                    <p class="text-xs text-slate-400 mb-4">Daftar semester yang masuk ke dalam penghitungan siklus ini.</p>

                    <div class="space-y-2.5">
                        <div
                            v-for="p in siklusSpmi.periodes"
                            :key="p.id"
                            class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between"
                        >
                            <div>
                                <span class="text-xs font-bold text-slate-800">{{ p.nama }}</span>
                                <p class="text-[11px] text-slate-400">{{ p.tahun }} - Semester {{ p.semester }}</p>
                            </div>
                            <span v-if="p.is_aktif" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                Aktif
                            </span>
                        </div>
                        <div v-if="!siklusSpmi.periodes || siklusSpmi.periodes.length === 0" class="py-8 text-center text-xs text-slate-400">
                            Belum ada periode yang ditautkan ke siklus ini.
                        </div>
                    </div>
                </div>

                <!-- Description & Cycle Metadata -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Informasi & Ruang Lingkup</h3>
                    <p class="text-xs text-slate-400 mb-4">Catatan operasional dan target mutu siklus.</p>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed min-h-[140px]">
                        {{ siklusSpmi.deskripsi || 'Tidak ada catatan deskripsi tambahan untuk siklus mutu ini.' }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
