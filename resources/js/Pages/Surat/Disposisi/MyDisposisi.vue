<template>
    <AuthenticatedLayout title="Disposisi Saya">
        <div class="space-y-5">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-black text-slate-900">Disposisi Saya</h1>
                <p class="text-xs text-slate-400 mt-0.5">Tugas dan pendelegasian yang diterima</p>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button @click="setFilter('')" class="text-left p-4 rounded-2xl border transition hover:shadow-md"
                    :class="!filters.status && !filters.overdue ? 'border-amber-400 bg-amber-50' : 'border-slate-200 bg-white'">
                    <div class="text-2xl font-black" :class="!filters.status && !filters.overdue ? 'text-amber-700' : 'text-slate-900'">
                        {{ stats.pending + stats.proses + stats.selesai }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">Total</div>
                </button>
                <button @click="setFilter('pending')" class="text-left p-4 rounded-2xl border transition hover:shadow-md"
                    :class="filters.status === 'pending' ? 'border-amber-400 bg-amber-50' : 'border-slate-200 bg-white'">
                    <div class="text-2xl font-black" :class="filters.status === 'pending' ? 'text-amber-700' : 'text-slate-900'">
                        {{ stats.pending }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">Menunggu</div>
                </button>
                <button @click="setFilter('proses')" class="text-left p-4 rounded-2xl border transition hover:shadow-md"
                    :class="filters.status === 'proses' ? 'border-blue-400 bg-blue-50' : 'border-slate-200 bg-white'">
                    <div class="text-2xl font-black" :class="filters.status === 'proses' ? 'text-blue-700' : 'text-slate-900'">
                        {{ stats.proses }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">Diproses</div>
                </button>
                <button @click="setFilter('selesai')" class="text-left p-4 rounded-2xl border transition hover:shadow-md"
                    :class="filters.status === 'selesai' ? 'border-green-400 bg-green-50' : 'border-slate-200 bg-white'">
                    <div class="text-2xl font-black" :class="filters.status === 'selesai' ? 'text-green-700' : 'text-slate-900'">
                        {{ stats.selesai }}
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-0.5">Selesai</div>
                </button>
            </div>

            <!-- Overdue Alert -->
            <div v-if="stats.overdue > 0" class="flex items-center gap-3 px-5 py-3 bg-red-50 border border-red-200 rounded-2xl text-xs">
                <i class="bi bi-exclamation-triangle-fill text-red-500 text-base shrink-0"></i>
                <span class="text-red-700 font-semibold">{{ stats.overdue }} disposisi melewati batas waktu!</span>
                <button @click="setOverdue" class="ml-auto px-3 py-1 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-bold transition text-[10px]">
                    Tampilkan
                </button>
            </div>

            <!-- List -->
            <div class="space-y-3">
                <div v-if="disposisi.data.length === 0" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-12 text-center">
                    <i class="bi bi-send-check text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-sm font-semibold text-slate-500">Tidak ada disposisi {{ filters.status ? `dengan status "${filters.status}"` : '' }}</p>
                </div>

                <a v-for="d in disposisi.data" :key="d.id" :href="`/disposisi/${d.id}`"
                    class="block bg-white rounded-2xl border shadow-xs p-5 hover:shadow-md transition hover:-translate-y-0.5 group"
                    :class="d.is_overdue ? 'border-red-200' : 'border-slate-200/80'">
                    <div class="flex items-start gap-4">
                        <!-- Priority Icon -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                            :class="{'bg-red-100 text-red-600': d.prioritas==='tinggi','bg-amber-100 text-amber-600': d.prioritas==='sedang','bg-slate-100 text-slate-500': d.prioritas==='rendah'}">
                            <i class="bi bi-send-fill text-sm"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-1.5">
                                <p class="text-sm font-black text-slate-800 truncate group-hover:text-amber-700 transition">{{ d.perihal }}</p>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span v-if="d.is_overdue" class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-red-100 text-red-700 animate-pulse">Overdue</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase"
                                        :class="statusClass(d.status)">{{ d.status }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 truncate mb-2">{{ d.isi_disposisi }}</p>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[10px] text-slate-400">
                                <span class="flex items-center gap-1"><i class="bi bi-person-fill"></i>{{ d.dari_nama }}</span>
                                <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i>{{ d.created_at }}</span>
                                <span v-if="d.batas_waktu" class="flex items-center gap-1" :class="d.is_overdue ? 'text-red-500 font-bold' : 'text-orange-500'">
                                    <i class="bi bi-clock-fill"></i>{{ d.batas_waktu }}
                                </span>
                                <span v-if="d.jenis_surat" class="flex items-center gap-1 text-amber-500">
                                    <i class="bi bi-file-earmark-text"></i>{{ d.jenis_surat }}
                                </span>
                                <span class="ml-auto flex items-center gap-1 text-amber-600 font-bold">
                                    Lihat Detail <i class="bi bi-arrow-right text-xs"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pagination -->
            <div v-if="disposisi.last_page > 1" class="flex items-center justify-between text-xs text-slate-500">
                <span>{{ disposisi.from }}–{{ disposisi.to }} dari {{ disposisi.total }}</span>
                <div class="flex gap-1">
                    <a v-for="link in disposisi.links" :key="link.label" :href="link.url"
                        class="px-2.5 py-1.5 rounded-lg font-semibold transition"
                        :class="link.active ? 'bg-amber-600 text-white' : 'hover:bg-slate-100 text-slate-500'"
                        v-html="link.label" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    disposisi: Object,
    filters: Object,
    stats: Object,
});

const filters = reactive({ ...props.filters });

function applyFilters() {
    router.get('/disposisi/my-disposisi', filters, { preserveState: true, replace: true });
}

function setFilter(status) {
    filters.status = status;
    filters.overdue = '';
    applyFilters();
}

function setOverdue() {
    filters.status = '';
    filters.overdue = '1';
    applyFilters();
}

function statusClass(s) {
    return {
        pending: 'bg-amber-100 text-amber-700',
        dibaca: 'bg-blue-100 text-blue-700',
        proses: 'bg-blue-100 text-blue-700',
        selesai: 'bg-green-100 text-green-700',
    }[s] || 'bg-slate-100 text-slate-500';
}
</script>
