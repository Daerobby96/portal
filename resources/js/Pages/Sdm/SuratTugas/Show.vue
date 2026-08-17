<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    suratTugas: Object,
});

const reportModalOpen = ref(false);
const reportForm = useForm({
    laporan_hasil: props.suratTugas.laporan_hasil || '',
});

const submitReport = () => {
    reportForm.post(`/sdm/surat-tugas/${props.suratTugas.id}/complete`, {
        onSuccess: () => {
            reportModalOpen.value = false;
        }
    });
};

const approveSurat = () => {
    const catatan = prompt('Catatan approval (opsional):', 'Disetujui');
    if (catatan !== null) {
        router.post(`/sdm/surat-tugas/${props.suratTugas.id}/approve`, { catatan_approval: catatan });
    }
};

const rejectSurat = () => {
    const alasan = prompt('Alasan penolakan:');
    if (alasan) {
        router.post(`/sdm/surat-tugas/${props.suratTugas.id}/reject`, { alasan_penolakan: alasan });
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

const getPeranLabel = (peran) => {
    const map = {
        'ketua': 'Ketua Tim',
        'anggota': 'Anggota Tim',
        'penanggung_jawab': 'Penanggung Jawab',
    };
    return map[peran] || peran || 'Anggota';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Rincian Surat Tugas Kedinasan" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/surat-tugas" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Surat Tugas
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 font-mono">
                            {{ suratTugas.nomor_surat }}
                        </span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase"
                            :class="suratTugas.status === 'approved' || suratTugas.status === 'completed' ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : (suratTugas.status === 'pending' ? 'bg-amber-500/30 text-amber-200 border border-amber-400/30' : 'bg-rose-500/30 text-rose-200 border border-rose-400/30')"
                        >
                            {{ suratTugas.status }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                        {{ suratTugas.perihal }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Tujuan: {{ suratTugas.tempat_tujuan }} ({{ formatDate(suratTugas.tanggal_mulai) }} s.d. {{ formatDate(suratTugas.tanggal_selesai) }}).
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 flex-wrap">
                    <a
                        :href="`/sdm/surat-tugas/${suratTugas.id}/pdf`"
                        target="_blank"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/20 cursor-pointer backdrop-blur-sm"
                    >
                        <i class="bi bi-file-earmark-pdf text-rose-300 text-sm"></i>
                        <span>Surat Tugas (PDF)</span>
                    </a>

                    <a
                        :href="`/sdm/surat-tugas/${suratTugas.id}/sppd-pdf`"
                        target="_blank"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/20 cursor-pointer backdrop-blur-sm"
                    >
                        <i class="bi bi-car-front text-amber-300 text-sm"></i>
                        <span>SPPD (PDF)</span>
                    </a>

                    <button
                        v-if="suratTugas.status === 'pending'"
                        @click="approveSurat"
                        class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-emerald-600/30 cursor-pointer"
                    >
                        <i class="bi bi-check-lg text-base"></i>
                        <span>Setujui (Approve)</span>
                    </button>
                    <button
                        v-if="suratTugas.status === 'approved' || suratTugas.status === 'completed'"
                        @click="reportModalOpen = true"
                        class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-file-earmark-check"></i>
                        <span>{{ suratTugas.status === 'completed' ? 'Lihat/Edit Laporan' : 'Input Laporan Tugas' }}</span>
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
                                <span class="text-slate-400 font-medium">Jenis Penugasan</span>
                                <p class="font-bold text-slate-900 text-sm capitalize">{{ suratTugas.jenis?.replace('_', ' ') }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Tempat / Lokasi Tujuan</span>
                                <p class="font-bold text-slate-900 text-sm">{{ suratTugas.tempat_tujuan }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1 sm:col-span-2">
                                <span class="text-slate-400 font-medium">Rentang Waktu Kedinasan</span>
                                <p class="font-bold text-indigo-700 text-sm font-mono">{{ formatDate(suratTugas.tanggal_mulai) }} s.d. {{ formatDate(suratTugas.tanggal_selesai) }}</p>
                            </div>
                        </div>

                        <!-- Daftar Pegawai Yang Ditugaskan -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Tim Pegawai Yang Ditugaskan</h4>
                            <div class="space-y-2">
                                <div
                                    v-for="p in suratTugas.pegawais"
                                    :key="p.id"
                                    class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs"
                                >
                                    <div>
                                        <p class="font-bold text-slate-900">{{ p.nama }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ p.nip || 'No NIP' }} | {{ p.unit_kerja }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">
                                        {{ getPeranLabel(p.pivot?.peran) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Uraian Keperluan</span>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-800 leading-relaxed">
                                {{ suratTugas.keperluan }}
                            </div>
                        </div>

                        <div v-if="suratTugas.laporan_hasil">
                            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-2">Laporan Hasil Pelaksanaan Tugas</span>
                            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 text-xs text-emerald-950 leading-relaxed whitespace-pre-line">
                                {{ suratTugas.laporan_hasil }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    <!-- Cetak Dokumen PDF -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-file-earmark-pdf text-rose-500"></i>
                            Cetak Dokumen Resmi
                        </h4>
                        <div class="space-y-2.5">
                            <a
                                :href="`/sdm/surat-tugas/${suratTugas.id}/pdf`"
                                target="_blank"
                                class="w-full p-3.5 rounded-2xl bg-rose-50/70 hover:bg-rose-100/80 border border-rose-200/70 text-rose-800 transition flex items-center justify-between text-xs font-bold group"
                            >
                                <div class="flex items-center gap-2.5">
                                    <i class="bi bi-file-earmark-pdf-fill text-lg text-rose-600"></i>
                                    <span>Surat Tugas Resmi</span>
                                </div>
                                <i class="bi bi-box-arrow-up-right text-rose-400 group-hover:text-rose-700 transition"></i>
                            </a>

                            <a
                                :href="`/sdm/surat-tugas/${suratTugas.id}/sppd-pdf`"
                                target="_blank"
                                class="w-full p-3.5 rounded-2xl bg-amber-50/70 hover:bg-amber-100/80 border border-amber-200/70 text-amber-900 transition flex items-center justify-between text-xs font-bold group"
                            >
                                <div class="flex items-center gap-2.5">
                                    <i class="bi bi-car-front-fill text-lg text-amber-600"></i>
                                    <span>SPPD (Lembar Perjalanan)</span>
                                </div>
                                <i class="bi bi-box-arrow-up-right text-amber-400 group-hover:text-amber-700 transition"></i>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Penerbit Surat</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-1">
                            <p><strong>Dibuat oleh:</strong> {{ suratTugas.created_by?.name || 'Administrator' }}</p>
                            <p><strong>Status Approval:</strong> <span class="font-bold capitalize">{{ suratTugas.status }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Input Laporan Hasil -->
            <div v-if="reportModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Laporan Pelaksanaan Tugas</h3>
                                <p class="text-[11px] text-slate-400">Surat Tugas: {{ suratTugas.nomor_surat }}</p>
                            </div>
                        </div>
                        <button @click="reportModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitReport" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                                Uraian Hasil Kegiatan & Rekomendasi <span class="text-rose-500">*</span>
                            </label>
                            <textarea
                                v-model="reportForm.laporan_hasil"
                                rows="6"
                                required
                                placeholder="Jelaskan ringkasan materi, capaian hasil penugasan, output dokumen, dan tindak lanjut institusi..."
                                class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="reportModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="reportForm.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30">
                                {{ reportForm.processing ? 'Menyimpan...' : 'Simpan Laporan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
