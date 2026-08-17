<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioner: Object,
    results: Object,
    groupedQuestions: Object,
    topThree: Array,
    bottomThree: Array,
});

const calculateTotalRespondents = () => {
    return props.kuesioner.jawabans?.length || 0;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Hasil Kuesioner: ${kuesioner.judul}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/kuesioner" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Manajemen Kuesioner
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            {{ kuesioner.periode?.nama || 'Periode SPMI' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-500/30 text-emerald-200 border border-emerald-400/30">
                            {{ kuesioner.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Hasil & Analisis Kuesioner Layanan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        {{ kuesioner.judul }} (Target: <span class="capitalize">{{ kuesioner.target_role || 'Semua' }}</span>)
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="px-5 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-center">
                        <p class="text-[10px] text-indigo-200 uppercase font-semibold">Total Responden</p>
                        <p class="text-2xl font-black text-white">{{ calculateTotalRespondents() }}</p>
                    </div>
                </div>
            </div>

            <!-- Top Strengths & Areas for Improvement (Insights) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top 3 Kepuasan Tertinggi -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 text-emerald-600">
                        <i class="bi bi-hand-thumbs-up text-lg"></i>
                        <h3 class="text-sm font-bold text-slate-900">3 Aspek Kepuasan Tertinggi (Kekuatan)</h3>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="(item, idx) in topThree"
                            :key="idx"
                            class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex items-center justify-between gap-4"
                        >
                            <div class="text-xs font-semibold text-slate-800">
                                {{ item.pertanyaan }}
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-sm font-black text-emerald-700">{{ item.avg }}</span>
                                <span class="text-[10px] text-slate-400 block">/ 4.00</span>
                            </div>
                        </div>
                        <p v-if="!topThree || topThree.length === 0" class="text-xs text-slate-400 italic">Belum cukup data skor.</p>
                    </div>
                </div>

                <!-- Top 3 Perlu Ditingkatkan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 text-amber-600">
                        <i class="bi bi-exclamation-triangle text-lg"></i>
                        <h3 class="text-sm font-bold text-slate-900">3 Aspek Perlu Perbaikan (Prioritas SPMI)</h3>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="(item, idx) in bottomThree"
                            :key="idx"
                            class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-100 flex items-center justify-between gap-4"
                        >
                            <div class="text-xs font-semibold text-slate-800">
                                {{ item.pertanyaan }}
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-sm font-black text-amber-700">{{ item.avg }}</span>
                                <span class="text-[10px] text-slate-400 block">/ 4.00</span>
                            </div>
                        </div>
                        <p v-if="!bottomThree || bottomThree.length === 0" class="text-xs text-slate-400 italic">Belum cukup data skor.</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Breakdown per Question -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <h3 class="text-base font-bold text-slate-900">Rincian Hasil Setiap Butir Pertanyaan</h3>

                <div class="space-y-6 divide-y divide-slate-100">
                    <div
                        v-for="p in kuesioner.pertanyaans"
                        :key="p.id"
                        class="pt-5 first:pt-0 space-y-3"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <span v-if="p.kategori" class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-slate-100 text-slate-600 mr-2">
                                    {{ p.kategori }}
                                </span>
                                <span class="text-xs font-bold text-slate-900">{{ p.pertanyaan }}</span>
                            </div>

                            <div v-if="p.tipe === 'likert' && results[p.id]" class="shrink-0">
                                <span class="text-xs font-semibold text-slate-500 mr-1.5">Skor Rerata:</span>
                                <span class="px-3 py-1 rounded-xl bg-indigo-50 text-indigo-700 font-black text-xs border border-indigo-200">
                                    {{ results[p.id]?.avg || '0.00' }} / 4.00
                                </span>
                            </div>
                        </div>

                        <!-- Likert Distribution Bar -->
                        <div v-if="p.tipe === 'likert' && results[p.id]" class="space-y-1.5">
                            <div class="grid grid-cols-4 gap-2 text-center text-[11px] font-semibold text-slate-600">
                                <div class="p-2 rounded-xl bg-rose-50/70 border border-rose-100">
                                    <span class="block text-[10px] text-rose-600 uppercase">Sangat Kurang (1)</span>
                                    <span class="font-bold text-slate-900">{{ results[p.id]?.dist?.['1'] || 0 }} Responden</span>
                                </div>
                                <div class="p-2 rounded-xl bg-amber-50/70 border border-amber-100">
                                    <span class="block text-[10px] text-amber-600 uppercase">Kurang (2)</span>
                                    <span class="font-bold text-slate-900">{{ results[p.id]?.dist?.['2'] || 0 }} Responden</span>
                                </div>
                                <div class="p-2 rounded-xl bg-sky-50/70 border border-sky-100">
                                    <span class="block text-[10px] text-sky-600 uppercase">Baik (3)</span>
                                    <span class="font-bold text-slate-900">{{ results[p.id]?.dist?.['3'] || 0 }} Responden</span>
                                </div>
                                <div class="p-2 rounded-xl bg-emerald-50/70 border border-emerald-100">
                                    <span class="block text-[10px] text-emerald-600 uppercase">Sangat Baik (4)</span>
                                    <span class="font-bold text-slate-900">{{ results[p.id]?.dist?.['4'] || 0 }} Responden</span>
                                </div>
                            </div>
                        </div>

                        <!-- Text Answers List -->
                        <div v-else-if="results[p.id]?.answers?.length > 0" class="space-y-2">
                            <p class="text-[11px] font-bold text-slate-400 uppercase">Sampel Masukan & Saran Terbuka:</p>
                            <div class="space-y-1.5">
                                <div
                                    v-for="(ans, aIdx) in results[p.id].answers"
                                    :key="aIdx"
                                    class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700 italic"
                                >
                                    "{{ ans }}"
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
