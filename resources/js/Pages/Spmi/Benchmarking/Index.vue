<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    benchmarkings: Object,
    stats: Object,
    periodes: Array,
    selectedPeriodeId: Number,
});

const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    periode_id: props.selectedPeriodeId || (props.periodes[0]?.id ?? ''),
    nama_mitra: '',
    tingkat: 'Nasional',
    bidang_standar: '',
    tanggal_kegiatan: new Date().toISOString().split('T')[0],
    capaian_institusi: '',
    capaian_mitra: '',
    gap_analisis: '',
    best_practice_diadopsi: '',
    rencana_tindak_lanjut: '',
    status: 'Terlaksana',
    pic_nama: '',
});

const changePeriode = (e) => {
    router.get('/benchmarking', { periode_id: e.target.value }, { preserveState: true });
};

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.periode_id = props.selectedPeriodeId || (props.periodes[0]?.id ?? '');
    form.tingkat = 'Nasional';
    form.status = 'Terlaksana';
    form.tanggal_kegiatan = new Date().toISOString().split('T')[0];
    isModalOpen.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    editingId.value = item.id;
    form.periode_id = item.periode_id;
    form.nama_mitra = item.nama_mitra;
    form.tingkat = item.tingkat;
    form.bidang_standar = item.bidang_standar;
    form.tanggal_kegiatan = item.tanggal_kegiatan ? item.tanggal_kegiatan.split('T')[0] : '';
    form.capaian_institusi = item.capaian_institusi || '';
    form.capaian_mitra = item.capaian_mitra || '';
    form.gap_analisis = item.gap_analisis || '';
    form.best_practice_diadopsi = item.best_practice_diadopsi || '';
    form.rencana_tindak_lanjut = item.rencana_tindak_lanjut || '';
    form.status = item.status;
    form.pic_nama = item.pic_nama || '';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const saveItem = () => {
    if (isEditing.value) {
        form.put(`/benchmarking/${editingId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/benchmarking', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus data benchmarking ini?')) {
        router.delete(`/benchmarking/${id}`);
    }
};

const getTingkatBadge = (tingkat) => {
    switch (tingkat) {
        case 'Internasional':
            return 'bg-purple-50 text-purple-700 border-purple-200';
        case 'Nasional':
            return 'bg-blue-50 text-blue-700 border-blue-200';
        default:
            return 'bg-slate-50 text-slate-700 border-slate-200';
    }
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'Diimplementasikan':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'Terlaksana':
            return 'bg-sky-50 text-sky-700 border-sky-200';
        default:
            return 'bg-amber-50 text-amber-800 border-amber-200';
    }
};
</script>

<template>
    <Head title="Benchmarking Mutu (Studi Banding)" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Pilar 5 PPEPP: Benchmarking Mutu
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Benchmarking Mutu (Studi Banding)</h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Pencatatan studi banding standar mutu dengan perguruan tinggi mitra nasional & internasional untuk adopsi *best practice*.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <!-- Filter Periode -->
                    <div class="relative">
                        <select
                            :value="selectedPeriodeId"
                            @change="changePeriode"
                            class="px-3.5 py-2 rounded-2xl border border-slate-200 text-xs font-bold bg-white focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700 shadow-2xs"
                        >
                            <option v-for="p in periodes" :key="p.id" :value="p.id">
                                Periode: {{ p.nama }} {{ p.is_aktif ? '(Aktif)' : '' }}
                            </option>
                        </select>
                    </div>

                    <button
                        @click="openCreateModal"
                        class="px-4 py-2.5 rounded-2xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/20 transition flex items-center gap-2 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Kegiatan Benchmarking</span>
                    </button>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Total Benchmarking</span>
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-buildings"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mt-2 font-mono">{{ stats.total }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Institusi mitra studi banding</span>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Best Practice Diadopsi</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 mt-2 font-mono">{{ stats.diimplementasikan }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Telah diimplementasikan di kampus</span>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Terlaksana (Proses Adopsi)</span>
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-flag-fill"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-sky-600 mt-2 font-mono">{{ stats.terlaksana }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Laporan & rekomendasi selesai</span>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Mitra Internasional</span>
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-purple-600 mt-2 font-mono">{{ stats.internasional }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Global benchmark partners</span>
                </div>
            </div>

            <!-- Table of Benchmarkings -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Rekapitulasi Studi Banding & Analisis Gap Mutu</h2>
                        <p class="text-xs text-slate-500">Dokumentasi bukti dukung Kriteria 2 & Kriteria 9 akreditasi BAN-PT/LAM.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5">Perguruan Tinggi / Mitra</th>
                                <th class="px-4 py-3.5">Tingkat</th>
                                <th class="px-4 py-3.5">Bidang Standar</th>
                                <th class="px-4 py-3.5">Tanggal</th>
                                <th class="px-4 py-3.5">Best Practice yang Diadopsi</th>
                                <th class="px-4 py-3.5">PIC / Tim Pelaksana</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="item in benchmarkings.data" :key="item.id" class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-black text-slate-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <span>{{ item.nama_mitra }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase border" :class="getTingkatBadge(item.tingkat)">
                                        {{ item.tingkat }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 font-bold text-slate-700 max-w-xs">
                                    {{ item.bidang_standar }}
                                </td>

                                <td class="px-4 py-4 font-mono text-slate-500">
                                    {{ item.tanggal_kegiatan ? item.tanggal_kegiatan.split('T')[0] : '-' }}
                                </td>

                                <td class="px-4 py-4 text-emerald-800 font-semibold max-w-xs truncate" :title="item.best_practice_diadopsi">
                                    {{ item.best_practice_diadopsi || '-' }}
                                </td>

                                <td class="px-4 py-4 text-slate-600 font-medium">
                                    {{ item.pic_nama || '-' }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border" :class="getStatusBadge(item.status)">
                                        {{ item.status }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-right space-x-1">
                                    <button
                                        @click="openEditModal(item)"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition cursor-pointer"
                                        title="Edit Kegiatan"
                                    >
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </button>
                                    <button
                                        @click="deleteItem(item.id)"
                                        class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-slate-100 transition cursor-pointer"
                                        title="Hapus"
                                    >
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="benchmarkings.data.length === 0">
                                <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                    <i class="bi bi-buildings text-3xl block mb-2 opacity-50"></i>
                                    <span>Belum ada kegiatan benchmarking pada periode ini.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL FORM -->
            <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto space-y-5 border border-slate-200 shadow-2xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-black text-slate-900">
                                {{ isEditing ? 'Edit Kegiatan Benchmarking' : 'Form Pencatatan Benchmarking Mutu' }}
                            </h3>
                            <p class="text-xs text-slate-500">Merekam studi banding komparasi standar mutu dan adopsi best practice.</p>
                        </div>
                        <button @click="closeModal" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="saveItem" class="space-y-4 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block font-bold text-slate-700 mb-1">Perguruan Tinggi / Institusi Mitra <span class="text-rose-500">*</span></label>
                                <input v-model="form.nama_mitra" type="text" required placeholder="Contoh: Politeknik Negeri Bandung / NUS Singapore" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tingkat <span class="text-rose-500">*</span></label>
                                <select v-model="form.tingkat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500 bg-white">
                                    <option value="Lokal">Lokal / Regional</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Bidang / Standar yang Dibandingkan <span class="text-rose-500">*</span></label>
                                <input v-model="form.bidang_standar" type="text" required placeholder="Contoh: Kurikulum OBE & Teaching Factory" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tanggal Pelaksanaan Kegiatan <span class="text-rose-500">*</span></label>
                                <input v-model="form.tanggal_kegiatan" type="date" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kondisi / Capaian Kampus Kita Saat Ini</label>
                                <textarea v-model="form.capaian_institusi" rows="2" placeholder="Jelaskan kondisi baseline institusi..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Capaian / Keunggulan Institusi Mitra</label>
                                <textarea v-model="form.capaian_mitra" rows="2" placeholder="Jelaskan keunggulan mitra yang menjadi rujukan..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Gap Analisis (Kesenjangan Mutu)</label>
                            <textarea v-model="form.gap_analisis" rows="2" placeholder="Faktor penyebab kesenjangan (SDM, fasilitas, regulasi SOP)..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block font-bold text-emerald-800 mb-1">Best Practice yang Diadopsi (Rekomendasi Kaizen)</label>
                            <textarea v-model="form.best_practice_diadopsi" rows="2" placeholder="SOP, instrumen, atau tata kelola unggul yang akan diterapkan di kampus kita..." class="w-full px-3.5 py-2 rounded-xl border border-emerald-300 bg-emerald-50/30 focus:ring-2 focus:ring-emerald-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Penanggung Jawab (PIC / Tim Pelaksana)</label>
                                <input v-model="form.pic_nama" type="text" placeholder="Contoh: Tim LPM / Kaprodi TI" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Status Implementasi <span class="text-rose-500">*</span></label>
                                <select v-model="form.status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500 bg-white">
                                    <option value="Perencanaan">Perencanaan (Inisiasi MoU/Agenda)</option>
                                    <option value="Terlaksana">Terlaksana (Laporan Analisis Selesai)</option>
                                    <option value="Diimplementasikan">Diimplementasikan (Best Practice Berjalan)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="closeModal" class="px-4 py-2 rounded-xl font-bold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-xl font-bold bg-indigo-600 text-white hover:bg-indigo-500 shadow-md shadow-indigo-600/20 disabled:opacity-50">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Data Benchmarking' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
