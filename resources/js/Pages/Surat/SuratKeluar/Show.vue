<template>
    <AuthenticatedLayout :title="`Detail - ${suratKeluar.nomor_surat}`">
        <div class="max-w-4xl mx-auto space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <a href="/surat-keluar" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                        <i class="bi bi-arrow-left text-sm"></i>
                    </a>
                    <div>
                        <h1 class="text-lg font-black text-slate-900 font-mono">{{ suratKeluar.nomor_surat }}</h1>
                        <p class="text-xs text-slate-400 mt-0.5">{{ suratKeluar.jenis_surat }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a :href="suratKeluar.pdf_url" target="_blank"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="bi bi-eye text-amber-500"></i> Preview PDF
                    </a>
                    <a :href="suratKeluar.download_url"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="bi bi-download text-green-500"></i> Download PDF
                    </a>
                    <a v-if="canEdit" :href="`/surat-keluar/${suratKeluar.id}/edit`"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <i class="bi bi-pencil text-blue-500"></i> Edit
                    </a>
                    <form v-if="canApprove && suratKeluar.status === 'pending'" :action="`/surat-keluar/${suratKeluar.id}/approve`" method="POST" class="inline">
                        <input type="hidden" name="_token" :value="csrf">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">
                            <i class="bi bi-check2-circle"></i> Setujui & Terbitkan
                        </button>
                    </form>
                    <form v-if="canApprove && suratKeluar.status === 'pending'" :action="`/surat-keluar/${suratKeluar.id}/reject`" method="POST" class="inline">
                        <input type="hidden" name="_token" :value="csrf">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold transition">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-4">Identitas Surat</h2>
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-3.5">
                            <InfoItem label="Perihal" :value="suratKeluar.perihal" full />
                            <InfoItem label="Tujuan" :value="suratKeluar.tujuan" />
                            <InfoItem label="Tanggal Surat" :value="suratKeluar.tanggal_surat" />
                            <InfoItem v-if="suratKeluar.alamat_tujuan" label="Alamat Tujuan" :value="suratKeluar.alamat_tujuan" full />
                            <InfoItem label="Jenis Surat" :value="suratKeluar.jenis_surat" />
                            <InfoItem label="Dibuat oleh" :value="suratKeluar.creator_name" />
                        </dl>
                    </div>

                    <div v-if="suratKeluar.isi_surat" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-3">Isi Surat</h2>
                        <div class="prose prose-sm max-w-none text-slate-700 bg-slate-50 rounded-xl p-4 text-sm whitespace-pre-wrap">{{ suratKeluar.isi_surat }}</div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-3">Penandatangan</h2>
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-3">
                            <InfoItem label="Nama" :value="suratKeluar.penandatangan_nama" />
                            <InfoItem label="Jabatan" :value="suratKeluar.penandatangan_jabatan" />
                            <InfoItem v-if="suratKeluar.penandatangan_nip" label="NIP" :value="suratKeluar.penandatangan_nip" />
                        </dl>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-3">Status Surat</h2>
                        <div class="space-y-3 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Status</span>
                                <span class="px-2.5 py-1 rounded-full font-bold uppercase text-[10px]" :class="statusClass(suratKeluar.status)">
                                    {{ { draft: 'Draft', pending: 'Menunggu', published: 'Diterbitkan' }[suratKeluar.status] }}
                                </span>
                            </div>
                            <div v-if="suratKeluar.approver_name" class="flex items-center justify-between">
                                <span class="text-slate-500">Disetujui oleh</span>
                                <span class="font-semibold text-slate-700">{{ suratKeluar.approver_name }}</span>
                            </div>
                            <div v-if="suratKeluar.approved_at" class="flex items-center justify-between">
                                <span class="text-slate-500">Tgl. Persetujuan</span>
                                <span class="font-semibold text-slate-700">{{ suratKeluar.approved_at }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Dibuat</span>
                                <span class="font-semibold text-slate-700">{{ suratKeluar.created_at }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pending warning -->
                    <div v-if="suratKeluar.status === 'pending' && !canApprove" class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-700">
                        <i class="bi bi-hourglass-split block text-xl text-amber-500 mb-2"></i>
                        <p class="font-bold">Menunggu Persetujuan Pimpinan</p>
                        <p class="text-amber-600 mt-1">Surat ini sedang dalam proses verifikasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    suratKeluar: Object,
    canApprove: Boolean,
    canEdit: Boolean,
});

const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

function statusClass(s) {
    return { draft:'bg-slate-100 text-slate-500', pending:'bg-amber-100 text-amber-700', published:'bg-green-100 text-green-700' }[s] || 'bg-slate-100 text-slate-500';
}

const InfoItem = {
    template: `<div :class="full ? 'col-span-2' : ''"><dt class="text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-0.5">{{ label }}</dt><dd class="text-sm font-semibold text-slate-800">{{ value || '—' }}</dd></div>`,
    props: ['label','value','full']
};
</script>
