<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioners: Array,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Survei & Kuesioner Kepuasan" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-chat-square-text"></i>
                        <span>Survei Kepuasan Sivitas</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Daftar Kuesioner & Survei Aktif
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Sampaikan masukan dan evaluasi Anda untuk mendukung continuous quality improvement mutu akademik institusi.
                    </p>
                </div>
            </div>

            <!-- Survey Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="k in kuesioners"
                    :key="k.id"
                    class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between"
                >
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                {{ k.periode?.nama || 'Periode Aktif' }}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 mb-1.5">{{ k.judul }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                            {{ k.deskripsi || 'Silakan isi survei ini dengan jujur dan objektif.' }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">Target: {{ k.target_role || 'Semua' }}</span>
                        <a
                            :href="`/survei/${k.id}`"
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-xs"
                        >
                            Isi Survei &rarr;
                        </a>
                    </div>
                </div>

                <div v-if="!kuesioners || kuesioners.length === 0" class="col-span-full py-16 text-center text-slate-400 text-xs bg-white rounded-3xl border border-slate-100">
                    Tidak ada survei atau kuesioner aktif yang perlu Anda isi saat ini.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
