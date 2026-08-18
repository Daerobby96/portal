<template>
    <AuthenticatedLayout :title="`Disposisi #${disposisi.id}`">
        <div class="max-w-3xl mx-auto space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <a href="/disposisi/my-disposisi" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                        <i class="bi bi-arrow-left text-sm"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-black text-slate-900">Detail Disposisi</h1>
                        <p class="text-xs text-slate-400 mt-0.5">{{ disposisi.dari_nama }} → {{ disposisi.kepada_nama }}</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase" :class="statusClass(disposisi.status)">
                    {{ disposisi.status }}
                </span>
            </div>

            <!-- Surat Asal -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 shrink-0 mt-0.5">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-amber-900">{{ disposisi.surat_masuk?.perihal }}</p>
                        <p class="text-[10px] text-amber-600 mt-1 font-mono">{{ disposisi.surat_masuk?.nomor_agenda }} · {{ disposisi.surat_masuk?.nomor_surat }}</p>
                        <div class="flex flex-wrap items-center gap-3 mt-1.5 text-[10px] text-amber-600">
                            <span>Dari: <b>{{ disposisi.surat_masuk?.pengirim }}</b></span>
                            <span>Diterima: {{ disposisi.surat_masuk?.tanggal_terima }}</span>
                            <span v-if="disposisi.surat_masuk?.jenis_surat">{{ disposisi.surat_masuk.jenis_surat }}</span>
                        </div>
                    </div>
                    <a :href="`/surat-masuk/${disposisi.surat_masuk?.id}`" class="shrink-0 text-[10px] font-bold text-amber-700 hover:text-amber-900 flex items-center gap-1">
                        Lihat Surat <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Isi Disposisi -->
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-4">Isi Disposisi</h2>
                        <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ disposisi.isi_disposisi }}</div>

                        <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1"><i class="bi bi-person-fill"></i>Dari: <b class="text-slate-700">{{ disposisi.dari_nama }}</b></span>
                            <span class="flex items-center gap-1"><i class="bi bi-arrow-right-circle-fill text-amber-500"></i>Kepada: <b class="text-slate-700">{{ disposisi.kepada_nama }}</b></span>
                            <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i>{{ disposisi.created_at }}</span>
                            <span v-if="disposisi.batas_waktu" class="flex items-center gap-1 text-orange-600 font-semibold">
                                <i class="bi bi-clock-fill"></i>Batas: {{ disposisi.batas_waktu }}
                            </span>
                            <PrioritasBadge :value="disposisi.prioritas" />
                        </div>
                    </div>

                    <!-- Tindak Lanjut -->
                    <div v-if="disposisi.catatan_tindak_lanjut" class="bg-green-50 border border-green-200 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
                            <h2 class="font-black text-green-800 text-sm">Catatan Tindak Lanjut</h2>
                        </div>
                        <p class="text-xs text-green-700 whitespace-pre-wrap leading-relaxed">{{ disposisi.catatan_tindak_lanjut }}</p>
                        <p class="text-[10px] text-green-500 mt-2">Diselesaikan: {{ disposisi.selesai_at }}</p>
                    </div>

                    <!-- Update Status Form (only for recipient) -->
                    <div v-if="canUpdateStatus && disposisi.status !== 'selesai'" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-4">Perbarui Status Penanganan</h2>
                        <form @submit.prevent="updateStatus">
                            <div class="space-y-4">
                                <div class="flex gap-3">
                                    <button type="button" v-for="s in ['proses','selesai']" :key="s"
                                        @click="statusForm.status = s"
                                        class="flex-1 py-2.5 rounded-xl border text-xs font-bold transition"
                                        :class="statusForm.status === s ? (s === 'selesai' ? 'border-green-500 bg-green-100 text-green-800' : 'border-amber-500 bg-amber-100 text-amber-800') : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                                        <i :class="['bi', s === 'proses' ? 'bi-arrow-repeat' : 'bi-check2-all', 'mr-1']"></i>
                                        {{ s === 'proses' ? 'Sedang Diproses' : 'Tandai Selesai' }}
                                    </button>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Tindak Lanjut</label>
                                    <textarea v-model="statusForm.catatan_tindak_lanjut" rows="3"
                                        placeholder="Deskripsikan tindakan yang telah diambil..."
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition resize-none"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" :disabled="processing"
                                        class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                                        <i v-else class="bi bi-check2-circle"></i>
                                        Simpan Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                        <h2 class="font-black text-slate-800 text-sm mb-3">Info Disposisi</h2>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between"><span class="text-slate-500">Status</span>
                                <span class="font-bold uppercase" :class="statusClass(disposisi.status)">{{ disposisi.status }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-slate-500">Prioritas</span>
                                <PrioritasBadge :value="disposisi.prioritas" />
                            </div>
                            <div v-if="disposisi.batas_waktu" class="flex justify-between">
                                <span class="text-slate-500">Batas Waktu</span>
                                <span class="font-semibold" :class="isOverdue ? 'text-red-600' : 'text-slate-700'">{{ disposisi.batas_waktu }}</span>
                            </div>
                            <div v-if="disposisi.dibaca_at" class="flex justify-between">
                                <span class="text-slate-500">Dibaca</span>
                                <span class="font-semibold text-slate-700 text-[10px]">{{ disposisi.dibaca_at }}</span>
                            </div>
                            <div v-if="disposisi.selesai_at" class="flex justify-between">
                                <span class="text-slate-500">Selesai</span>
                                <span class="font-semibold text-green-600 text-[10px]">{{ disposisi.selesai_at }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="isOverdue" class="bg-red-50 border border-red-200 rounded-2xl p-4 text-xs text-red-700">
                        <i class="bi bi-exclamation-triangle-fill text-red-500 block text-xl mb-1.5"></i>
                        <p class="font-bold">Disposisi Overdue!</p>
                        <p class="text-red-500 mt-0.5">Batas waktu telah terlewati. Segera tindaklanjuti.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    disposisi: Object,
    canUpdateStatus: Boolean,
    updateStatusUrl: String,
});

const statusForm = reactive({ status: 'proses', catatan_tindak_lanjut: '' });
const processing = ref(false);

const isOverdue = computed(() => {
    if (!props.disposisi.batas_waktu || props.disposisi.status === 'selesai') return false;
    return new Date(props.disposisi.batas_waktu) < new Date();
});

function statusClass(s) {
    return { pending:'text-amber-600', dibaca:'text-blue-600', proses:'text-amber-700', selesai:'text-green-700' }[s] || 'text-slate-500';
}

function updateStatus() {
    processing.value = true;
    router.post(props.updateStatusUrl, statusForm, {
        onFinish: () => { processing.value = false; },
    });
}

const PrioritasBadge = {
    template: `<span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase"
        :class="{'bg-red-100 text-red-700': value==='tinggi','bg-amber-100 text-amber-700': value==='sedang','bg-slate-100 text-slate-500': value==='rendah'}">
        {{ value }}</span>`,
    props: ['value']
};
</script>
