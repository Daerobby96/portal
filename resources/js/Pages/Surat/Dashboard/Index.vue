<template>
    <AuthenticatedLayout title="Dashboard Persuratan">
        <div class="space-y-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">Dashboard Persuratan</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Tata Naskah Dinas & Disposisi Digital</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/surat-masuk/create" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold transition shadow-sm">
                        <i class="bi bi-inbox-fill"></i> Catat Surat Masuk
                    </a>
                    <a href="/surat-keluar/create" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-bold transition shadow-sm">
                        <i class="bi bi-send-fill text-amber-600"></i> Buat Surat Keluar
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <StatCard icon="bi-inbox-fill" color="amber" :value="stats.total_surat_masuk" label="Total Surat Masuk" :sub="`${stats.surat_masuk_baru} baru`" href="/surat-masuk" />
                <StatCard icon="bi-send-fill" color="blue" :value="stats.total_surat_keluar" label="Total Surat Keluar" :sub="`${stats.total_surat_keluar_bulan} bulan ini`" href="/surat-keluar" />
                <StatCard icon="bi-send-check-fill" color="violet" :value="stats.my_disposisi_pending" label="Disposisi Menunggu" :sub="`${stats.my_disposisi_total} total`" href="/disposisi/my-disposisi" />
                <StatCard icon="bi-hourglass-split" color="red" :value="stats.pending_approval" label="Pending Persetujuan" :sub="stats.my_disposisi_overdue > 0 ? `${stats.my_disposisi_overdue} overdue` : 'Tepat waktu'" href="/surat-keluar?status=pending" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-black text-slate-900 text-base">Tren Volume Surat</h2>
                            <p class="text-xs text-slate-400 mt-0.5">6 bulan terakhir</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-semibold">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-amber-500 inline-block"></span>Masuk</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-blue-500 inline-block"></span>Keluar</span>
                        </div>
                    </div>
                    <div class="relative h-52">
                        <svg class="w-full h-full" viewBox="0 0 600 200" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="masukGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.02"/>
                                </linearGradient>
                                <linearGradient id="keluarGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.02"/>
                                </linearGradient>
                            </defs>
                            <!-- Grid lines -->
                            <line x1="0" y1="40" x2="600" y2="40" stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="80" x2="600" y2="80" stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="120" x2="600" y2="120" stroke="#f1f5f9" stroke-width="1"/>
                            <line x1="0" y1="160" x2="600" y2="160" stroke="#f1f5f9" stroke-width="1"/>
                            <!-- Masuk line -->
                            <polyline :points="masukPoints" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <!-- Keluar line -->
                            <polyline :points="keluarPoints" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <!-- Dots Masuk -->
                            <circle v-for="(pt, i) in masukDots" :key="'m'+i" :cx="pt.x" :cy="pt.y" r="4" fill="#f59e0b" stroke="white" stroke-width="2"/>
                            <!-- Dots Keluar -->
                            <circle v-for="(pt, i) in keluarDots" :key="'k'+i" :cx="pt.x" :cy="pt.y" r="4" fill="#3b82f6" stroke="white" stroke-width="2"/>
                        </svg>
                        <!-- X Labels -->
                        <div class="absolute bottom-0 left-0 right-0 flex justify-between px-2">
                            <span v-for="label in chartData.labels" :key="label" class="text-[10px] text-slate-400 font-medium">{{ label }}</span>
                        </div>
                    </div>
                </div>

                <!-- My Disposisi -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-black text-slate-900 text-sm">Disposisi Saya</h2>
                        <a href="/disposisi/my-disposisi" class="text-xs text-amber-600 font-bold hover:underline">Lihat Semua</a>
                    </div>
                    <div v-if="myDisposisi.length === 0" class="text-center py-8">
                        <i class="bi bi-send-check text-3xl text-slate-200"></i>
                        <p class="text-xs text-slate-400 mt-2">Tidak ada disposisi aktif</p>
                    </div>
                    <div v-else class="space-y-3">
                        <a v-for="d in myDisposisi" :key="d.id" :href="`/disposisi/${d.id}`"
                            class="block p-3 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/40 transition group">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-amber-700 transition">{{ d.perihal }}</span>
                                <PrioritasBadge :value="d.prioritas" />
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-slate-400">
                                <i class="bi bi-person-fill"></i><span>{{ d.dari_nama }}</span>
                                <span v-if="d.batas_waktu" class="ml-auto text-red-500 font-semibold flex items-center gap-1">
                                    <i class="bi bi-clock"></i>{{ d.batas_waktu }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Surat Masuk -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h2 class="font-black text-slate-900 text-sm">Surat Masuk Terbaru</h2>
                        <a href="/surat-masuk" class="text-xs text-amber-600 font-bold hover:underline">Lihat Semua</a>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <a v-for="s in recentSuratMasuk" :key="s.id" :href="`/surat-masuk/${s.id}`"
                            class="flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 transition group">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                                :class="sifatColor(s.sifat)">
                                <i class="bi bi-envelope-fill text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate group-hover:text-amber-700">{{ s.perihal }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ s.pengirim }} · {{ s.tanggal_terima }}</p>
                            </div>
                            <StatusBadge :value="s.status" type="masuk" />
                        </a>
                        <div v-if="recentSuratMasuk.length === 0" class="px-5 py-8 text-center text-xs text-slate-400">Belum ada surat masuk</div>
                    </div>
                </div>

                <!-- Recent Surat Keluar -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <h2 class="font-black text-slate-900 text-sm">Surat Keluar Terbaru</h2>
                        <a href="/surat-keluar" class="text-xs text-amber-600 font-bold hover:underline">Lihat Semua</a>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <a v-for="s in recentSuratKeluar" :key="s.id" :href="`/surat-keluar/${s.id}`"
                            class="flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 transition group">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0 mt-0.5">
                                <i class="bi bi-send-fill text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate group-hover:text-blue-700">{{ s.perihal }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ s.nomor_surat }} · {{ s.tanggal_surat }}</p>
                            </div>
                            <StatusBadge :value="s.status" type="keluar" />
                        </a>
                        <div v-if="recentSuratKeluar.length === 0" class="px-5 py-8 text-center text-xs text-slate-400">Belum ada surat keluar</div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals (pimpinan/super_admin only) -->
            <div v-if="pendingApprovals.length > 0" class="bg-amber-50 border border-amber-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-amber-100">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-amber-900 text-sm">Menunggu Persetujuan Anda</h2>
                        <p class="text-[10px] text-amber-600">Surat keluar yang perlu ditindaklanjuti</p>
                    </div>
                </div>
                <div class="divide-y divide-amber-100">
                    <div v-for="s in pendingApprovals" :key="s.id" class="flex items-center gap-4 px-5 py-3.5">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ s.perihal }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Oleh {{ s.creator_name }} · {{ s.tanggal_surat }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a :href="`/surat-keluar/${s.id}`" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    stats: Object,
    recentSuratMasuk: Array,
    recentSuratKeluar: Array,
    myDisposisi: Array,
    pendingApprovals: Array,
    chartData: Object,
});

// Simple SVG chart helpers
const maxVal = computed(() => {
    const all = [...props.chartData.surat_masuk, ...props.chartData.surat_keluar];
    return Math.max(...all, 1);
});

function toPoints(data) {
    const w = 600, h = 180, pad = 20;
    return data.map((v, i) => {
        const x = pad + (i / (data.length - 1)) * (w - pad * 2);
        const y = h - pad - (v / maxVal.value) * (h - pad * 2);
        return { x: isNaN(x) ? pad : x, y: isNaN(y) ? h - pad : y };
    });
}

const masukDots  = computed(() => toPoints(props.chartData.surat_masuk));
const keluarDots = computed(() => toPoints(props.chartData.surat_keluar));
const masukPoints  = computed(() => masukDots.value.map(p => `${p.x},${p.y}`).join(' '));
const keluarPoints = computed(() => keluarDots.value.map(p => `${p.x},${p.y}`).join(' '));

function sifatColor(sifat) {
    return {
        rahasia: 'bg-red-100 text-red-600',
        segera: 'bg-orange-100 text-orange-600',
        sangat_segera: 'bg-red-100 text-red-700',
        biasa: 'bg-amber-50 text-amber-600',
    }[sifat] || 'bg-slate-100 text-slate-500';
}

// Inline mini components
const StatCard = { template: `
    <a :href="href" class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition hover:-translate-y-0.5 group block">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                :class="{'bg-amber-100 text-amber-600': color==='amber','bg-blue-100 text-blue-600': color==='blue','bg-violet-100 text-violet-600': color==='violet','bg-red-100 text-red-600': color==='red'}">
                <i :class="['bi', icon]"></i>
            </div>
        </div>
        <div class="text-2xl font-black text-slate-900 mb-0.5">{{ value }}</div>
        <div class="text-xs font-semibold text-slate-600">{{ label }}</div>
        <div class="text-[10px] text-slate-400 mt-0.5">{{ sub }}</div>
    </a>`, props: ['icon','color','value','label','sub','href'] };

const PrioritasBadge = { template: `
    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase shrink-0"
        :class="{'bg-red-100 text-red-700': value==='tinggi','bg-amber-100 text-amber-700': value==='sedang','bg-slate-100 text-slate-500': value==='rendah'}">
        {{ value }}
    </span>`, props: ['value'] };

const StatusBadge = { template: `
    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase shrink-0"
        :class="badgeClass">{{ value }}</span>`,
    props: ['value','type'],
    computed: {
        badgeClass() {
            const map = { baru:'bg-blue-100 text-blue-700', proses:'bg-amber-100 text-amber-700', selesai:'bg-green-100 text-green-700', arsip:'bg-slate-100 text-slate-500',
                          draft:'bg-slate-100 text-slate-500', pending:'bg-amber-100 text-amber-700', published:'bg-green-100 text-green-700' };
            return map[this.value] || 'bg-slate-100 text-slate-500';
        }
    }
};
</script>
