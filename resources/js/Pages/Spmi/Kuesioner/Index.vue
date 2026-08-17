<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioners: Object,
});

const deleteKuesioner = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus kuesioner ini?')) {
        router.delete(`/kuesioner/${id}`);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Manajemen Survei & Kuesioner" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-ui-checks"></i>
                        <span>Umpan Balik Kepuasan</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Manajemen Kuesioner & Survei
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Survei kepuasan mahasiswa, tracer study lulusan, evaluasi dosen (EDOM), dan kepuasan tendik/mitra kerja.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a
                        href="/kuesioner/create"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat Kuesioner</span>
                    </a>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Judul Kuesioner</th>
                                <th class="py-3.5 px-6">Periode</th>
                                <th class="py-3.5 px-6">Target Responden</th>
                                <th class="py-3.5 px-6 text-center">Responden Masuk</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="k in kuesioners.data"
                                :key="k.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <a :href="`/kuesioner/${k.id}`" class="hover:text-indigo-600 transition">
                                        {{ k.judul }}
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ k.periode?.nama || '-' }}
                                </td>
                                <td class="py-4 px-6 capitalize">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ k.target_role || 'Semua Role' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[11px]">
                                        {{ k.jawabans_count || 0 }} Respon
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            :href="`/kuesioner/${k.id}`"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition"
                                        >
                                            Hasil
                                        </a>
                                        <a
                                            :href="`/kuesioner/${k.id}/edit`"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                                            title="Edit Pertanyaan"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button
                                            @click="deleteKuesioner(k.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!kuesioners.data || kuesioners.data.length === 0">
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    Belum ada kuesioner atau survei yang dibuat.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="kuesioners.links && kuesioners.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Menampilkan {{ kuesioners.from || 0 }} - {{ kuesioners.to || 0 }} dari {{ kuesioners.total }} data
                    </div>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, idx) in kuesioners.links"
                            :key="idx"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 rounded-lg font-medium transition"
                            :class="link.active ? 'bg-indigo-600 text-white font-bold' : (link.url ? 'hover:bg-slate-100 text-slate-700' : 'text-slate-300 pointer-events-none')"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
