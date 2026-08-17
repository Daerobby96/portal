<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    lembur: Object,
});

const approveLembur = () => {
    const catatan = prompt('Catatan approval lembur (opsional):', 'Disetujui');
    if (catatan !== null) {
        router.post(`/sdm/lembur/${props.lembur.id}/approve`, { catatan_approval: catatan });
    }
};

const rejectLembur = () => {
    const alasan = prompt('Alasan penolakan:');
    if (alasan) {
        router.post(`/sdm/lembur/${props.lembur.id}/reject`, { alasan_penolakan: alasan });
    }
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const cleanStr = String(dateStr).split('T')[0];
    const parts = cleanStr.split('-');
    if (parts.length === 3) {
        const year = parts[0];
        const month = parseInt(parts[1], 10);
        const day = parseInt(parts[2], 10);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return `${day < 10 ? '0' + day : day} ${months[month - 1] || ''} ${year}`;
    }
    return dateStr;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Rincian Pengajuan Lembur" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/lembur" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Lembur
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Lembur ID #{{ lembur.id }}
                        </span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase"
                            :class="lembur.status === 'approved' ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : (lembur.status === 'pending' ? 'bg-amber-500/30 text-amber-200 border border-amber-400/30' : 'bg-rose-500/30 text-rose-200 border border-rose-400/30')"
                        >
                            {{ lembur.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Rincian Kerja Lembur
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Pengajuan lembur pegawai {{ lembur.pegawai?.nama }} ({{ lembur.jumlah_jam }} Jam kerja).
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        v-if="lembur.status === 'pending'"
                        @click="approveLembur"
                        class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-emerald-600/30 cursor-pointer"
                    >
                        <i class="bi bi-check-lg text-base"></i>
                        <span>Setujui (Approve)</span>
                    </button>
                    <button
                        v-if="lembur.status === 'pending'"
                        @click="rejectLembur"
                        class="px-5 py-2.5 rounded-2xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-rose-600/30 cursor-pointer"
                    >
                        <i class="bi bi-x-lg text-base"></i>
                        <span>Tolak (Reject)</span>
                    </button>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Details Card -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Pegawai</span>
                                <p class="font-bold text-slate-900 text-sm">{{ lembur.pegawai?.nama }}</p>
                                <span class="text-[11px] text-slate-400 font-mono">{{ lembur.pegawai?.nip || 'No NIP' }} | {{ lembur.pegawai?.unit_kerja }}</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Tanggal & Waktu Lembur</span>
                                <p class="font-bold text-indigo-700 text-sm font-mono">{{ formatDate(lembur.tanggal) }} ({{ lembur.jam_mulai }} - {{ lembur.jam_selesai }})</p>
                                <span class="text-[11px] text-slate-500 font-mono">Total {{ lembur.jumlah_jam }} Jam</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Uraian Tugas Lembur</span>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-800 leading-relaxed">
                                {{ lembur.keperluan }}
                            </div>
                        </div>

                        <div v-if="lembur.catatan_approval">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Catatan Approval Atasan</span>
                            <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900 leading-relaxed">
                                {{ lembur.catatan_approval }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Attachment Info -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Berkas Lampiran</h4>
                        <div v-if="lembur.file_pendukung">
                            <a
                                :href="`/storage/${lembur.file_pendukung}`"
                                target="_blank"
                                class="p-3.5 rounded-2xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold flex items-center justify-between transition"
                            >
                                <span class="flex items-center gap-2"><i class="bi bi-paperclip"></i> Lihat Berkas Lampiran</span>
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                        <div v-else class="text-xs text-slate-400">
                            Tidak ada berkas pendukung yang dilampirkan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
