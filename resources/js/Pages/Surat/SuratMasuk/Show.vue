<template>
    <AuthenticatedLayout :title="`Detail - ${suratMasuk.nomor_agenda}`">
        <div class="max-w-4xl mx-auto space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <a href="/surat-masuk" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                        <i class="bi bi-arrow-left text-sm"></i>
                    </a>
                    <div>
                        <h1 class="text-lg font-black text-slate-900">{{ suratMasuk.nomor_agenda }}</h1>
                        <p class="text-xs text-slate-400 mt-0.5 font-mono">{{ suratMasuk.nomor_surat }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a v-if="suratMasuk.file_url" :href="suratMasuk.file_url" target="_blank"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="bi bi-eye text-amber-500"></i> Lihat Berkas
                    </a>
                    <a :href="`/surat-masuk/${suratMasuk.id}/edit`"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="bi bi-pencil text-blue-500"></i> Edit
                    </a>
                    <a v-if="canDisposisi" :href="`/surat-masuk/${suratMasuk.id}/disposisi/create`"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition shadow-sm">
                        <i class="bi bi-send-fill"></i> Disposisikan
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Identitas -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-black text-slate-800 text-sm">Identitas Surat</h2>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase" :class="sifatClass(suratMasuk.sifat)">
                                {{ suratMasuk.sifat?.replace('_', ' ') }}
                            </span>
                        </div>
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-3.5 text-sm">
                            <InfoItem label="Perihal" :value="suratMasuk.perihal" full />
                            <InfoItem label="Pengirim" :value="suratMasuk.pengirim" />
                            <InfoItem label="Jenis Surat" :value="suratMasuk.jenis_surat" />
                            <InfoItem label="Tgl. Surat" :value="suratMasuk.tanggal_surat" />
                            <InfoItem label="Tgl. Terima" :value="suratMasuk.tanggal_terima" />
                            <InfoItem label="Prioritas" :value="suratMasuk.prioritas" />
                            <InfoItem v-if="suratMasuk.alamat_pengirim" label="Alamat Pengirim" :value="suratMasuk.alamat_pengirim" full />
                            <InfoItem v-if="suratMasuk.jumlah_lampiran" label="Lampiran" :value="`${suratMasuk.jumlah_lampiran} berkas — ${suratMasuk.keterangan_lampiran || '-'}`" full />
                            <InfoItem v-if="suratMasuk.catatan" label="Catatan" :value="suratMasuk.catatan" full />
                        </dl>
                    </div>

                    <!-- Riwayat Disposisi -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-4">Riwayat Disposisi</h2>
                        <div v-if="suratMasuk.disposisi.length === 0" class="text-center py-6">
                            <i class="bi bi-send-slash text-3xl text-slate-200 block mb-2"></i>
                            <p class="text-xs text-slate-400">Belum ada disposisi untuk surat ini</p>
                        </div>
                        <div v-else class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-px bg-slate-100"></div>
                            <div v-for="d in suratMasuk.disposisi" :key="d.id" class="relative pl-10 pb-5 last:pb-0">
                                <div class="absolute left-2.5 top-1 w-3 h-3 rounded-full border-2 border-amber-400 bg-white"></div>
                                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-bold text-slate-800">{{ d.dari_nama }} → {{ d.kepada_nama }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase" :class="statusClass(d.status)">{{ d.status }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 mb-2">{{ d.isi_disposisi }}</p>
                                    <div class="flex items-center gap-3 text-[10px] text-slate-400">
                                        <span>{{ d.created_at }}</span>
                                        <span v-if="d.batas_waktu" class="flex items-center gap-1 text-orange-500">
                                            <i class="bi bi-clock"></i>{{ d.batas_waktu }}
                                        </span>
                                    </div>
                                    <p v-if="d.catatan_tindak_lanjut" class="text-[10px] text-green-600 mt-2 font-semibold">
                                        ✓ {{ d.catatan_tindak_lanjut }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Status -->
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-3">Status Surat</h2>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Status</span>
                                <span class="px-2.5 py-1 rounded-full font-bold uppercase text-[10px]" :class="statusClass(suratMasuk.status)">
                                    {{ suratMasuk.status }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Dicatat oleh</span>
                                <span class="font-semibold text-slate-700">{{ suratMasuk.creator_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Total Disposisi</span>
                                <span class="font-bold text-amber-600">{{ suratMasuk.disposisi.length }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="suratMasuk.file_url" class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                        <h3 class="font-black text-amber-800 text-sm mb-3">Berkas Surat</h3>
                        <a :href="suratMasuk.file_url" target="_blank"
                            class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white border border-amber-200 text-xs font-bold text-amber-700 hover:bg-amber-100 transition">
                            <i class="bi bi-file-earmark-pdf text-base"></i> Buka Berkas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    suratMasuk: Object,
    canDisposisi: Boolean,
});

function sifatClass(s) {
    return { rahasia:'bg-red-100 text-red-700', segera:'bg-orange-100 text-orange-700', sangat_segera:'bg-red-100 text-red-800', biasa:'bg-slate-100 text-slate-600' }[s] || 'bg-slate-100 text-slate-500';
}

function statusClass(s) {
    return { baru:'bg-blue-100 text-blue-700', proses:'bg-amber-100 text-amber-700', selesai:'bg-green-100 text-green-700', arsip:'bg-slate-100 text-slate-500',
             pending:'bg-amber-100 text-amber-700', dibaca:'bg-blue-100 text-blue-700' }[s] || 'bg-slate-100 text-slate-500';
}

const InfoItem = {
    template: `<div :class="full ? 'col-span-2' : ''"><dt class="text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-0.5">{{ label }}</dt><dd class="text-sm font-semibold text-slate-800">{{ value || '—' }}</dd></div>`,
    props: ['label','value','full']
};
</script>
