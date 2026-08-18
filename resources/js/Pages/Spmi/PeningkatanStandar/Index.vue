<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    peningkatans: Object,
    stats: Object,
    standars: Array,
    indikators: {
        type: Array,
        default: () => [],
    },
    periodes: Array,
    selectedPeriodeId: Number,
});

const isModalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    periode_id: props.selectedPeriodeId || (props.periodes[0]?.id ?? ''),
    standar_id: props.standars && props.standars.length > 0 ? props.standars[0].id : '',
    indikator_kinerja_id: null,
    target_lama: '',
    capaian_saat_ini: '',
    target_baru: '',
    dasar_pertimbangan: '',
    strategi_pencapaian: '',
    status: 'diajukan',
    catatan: '',
});

// Format options untuk SearchableSelect Standar Mutu
const standarOptions = computed(() => {
    return (props.standars || []).map((s) => ({
        value: s.id,
        label: `[${s.kode}] ${s.nama}`,
        subtext: s.bidang || s.jenis || 'Standar Mutu',
        badge: s.kode,
    }));
});

// Filter indikator kinerja sesuai standar yang dipilih
const filteredIndikators = computed(() => {
    if (!form.standar_id) return [];
    return props.indikators.filter((ind) => ind.standar_id == form.standar_id);
});

// Format options untuk SearchableSelect Indikator Kinerja
const indikatorOptions = computed(() => {
    return filteredIndikators.value.map((ind) => ({
        value: ind.id,
        label: `[${ind.kode}] ${ind.nama}`,
        subtext: `Target: ${ind.target_text} | Capaian: ${ind.capaian_text}`,
        badge: ind.unit_pengukuran || '',
    }));
});

// Saat user memilih Indikator Kinerja, otomatis isi Target Lama dan Capaian Terakhir dari Database
const applyIndikatorData = (indikatorId) => {
    if (!indikatorId) return;
    const selected = props.indikators.find((ind) => String(ind.id) === String(indikatorId));
    if (selected) {
        form.target_lama = selected.target_text || '100%';
        form.capaian_saat_ini = selected.capaian_text || selected.target_text || '100%';
        form.dasar_pertimbangan = `Capaian indikator (${selected.kode}) telah melampaui target baseline pada evaluasi periode berjalan.`;
    }
};

watch(() => form.indikator_kinerja_id, (newVal) => {
    if (newVal && !isEditing.value) {
        applyIndikatorData(newVal);
    }
});

// Reset indikator bila standar berganti saat create
watch(() => form.standar_id, (newVal, oldVal) => {
    if (!isEditing.value && oldVal) {
        form.indikator_kinerja_id = null;
        form.target_lama = '';
        form.capaian_saat_ini = '';
    }
});

const changePeriode = (e) => {
    router.get('/peningkatan-standar', { periode_id: e.target.value }, { preserveState: true });
};

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.periode_id = props.selectedPeriodeId || (props.periodes[0]?.id ?? '');
    form.standar_id = props.standars && props.standars.length > 0 ? props.standars[0].id : '';
    form.indikator_kinerja_id = null;
    form.status = 'diajukan';
    isModalOpen.value = true;
};

const openEditModal = (item) => {
    isEditing.value = true;
    editingId.value = item.id;
    form.periode_id = item.periode_id;
    form.standar_id = item.standar_id;
    form.indikator_kinerja_id = item.indikator_kinerja_id;
    form.target_lama = item.target_lama;
    form.capaian_saat_ini = item.capaian_saat_ini;
    form.target_baru = item.target_baru;
    form.dasar_pertimbangan = item.dasar_pertimbangan || '';
    form.strategi_pencapaian = item.strategi_pencapaian || '';
    form.status = item.status;
    form.catatan = item.catatan || '';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const saveItem = () => {
    if (isEditing.value) {
        form.put(`/peningkatan-standar/${editingId.value}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/peningkatan-standar', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus usulan peningkatan standar ini?')) {
        router.delete(`/peningkatan-standar/${id}`);
    }
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'diterapkan':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'disetujui':
            return 'bg-sky-50 text-sky-700 border-sky-200';
        case 'diajukan':
            return 'bg-amber-50 text-amber-800 border-amber-200';
        default:
            return 'bg-slate-50 text-slate-700 border-slate-200';
    }
};
</script>

<template>
    <Head title="Peningkatan Standar Mutu (Kaizen)" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Pilar 5 PPEPP: Peningkatan (Kaizen)
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Peningkatan Standar Mutu</h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Peningkatan target baseline standar mutu secara berkelanjutan berdasarkan capaian evaluasi & kesepakatan RTM.
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
                        <span>Usulkan Peningkatan Standar</span>
                    </button>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Total Usulan Peningkatan</span>
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-arrow-up-right-circle"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mt-2 font-mono">{{ stats.total }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Standar dievaluasi untuk upgrade</span>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Diterapkan (Renstra Baru)</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-check2-all"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 mt-2 font-mono">{{ stats.diterapkan }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Telah resmi berlaku di sistem</span>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Disetujui RTM</span>
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-patch-check"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-sky-600 mt-2 font-mono">{{ stats.disetujui }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Disahkan pimpinan institusi</span>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Menunggu Review / Diajukan</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-black">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-600 mt-2 font-mono">{{ stats.diajukan }}</div>
                    <span class="text-[10px] text-slate-400 font-semibold">Usulan baru dari auditor/prodi</span>
                </div>
            </div>

            <!-- Table of Upgraded Standards -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Matriks Peningkatan Standar Mutu (Kaizen Upgrades)</h2>
                        <p class="text-xs text-slate-500">Perbandingan target lama terhadap target baru yang ditingkatkan.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5">Standar & Indikator Mutu</th>
                                <th class="px-4 py-3.5">Target Lama (Baseline)</th>
                                <th class="px-4 py-3.5">Capaian Terakhir (Evaluasi)</th>
                                <th class="px-4 py-3.5 text-emerald-700">Target Baru (Peningkatan Kaizen)</th>
                                <th class="px-4 py-3.5">Dasar Pertimbangan (RTM)</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="item in peningkatans.data" :key="item.id" class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-bold text-slate-900 max-w-xs">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-slate-100 text-slate-700">
                                            {{ item.standar?.kode || 'STD' }}
                                        </span>
                                        <span class="text-xs font-black text-slate-900">{{ item.standar?.nama || '-' }}</span>
                                    </div>
                                    <div v-if="item.indikator_kinerja" class="text-[11px] text-indigo-600 font-semibold mt-0.5">
                                        <i class="bi bi-bullseye me-1"></i>{{ item.indikator_kinerja?.nama }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 font-semibold text-slate-600 font-mono">
                                    {{ item.target_lama }}
                                </td>

                                <td class="px-4 py-4 font-bold text-indigo-700 font-mono">
                                    {{ item.capaian_saat_ini }}
                                </td>

                                <td class="px-4 py-4 font-black text-emerald-700 bg-emerald-50/30 font-mono">
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi bi-arrow-up text-emerald-600 font-bold"></i>
                                        <span>{{ item.target_baru }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-slate-500 max-w-xs truncate" :title="item.dasar_pertimbangan">
                                    {{ item.dasar_pertimbangan || '-' }}
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
                                        title="Edit Peningkatan"
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

                            <tr v-if="peningkatans.data.length === 0">
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                    <i class="bi bi-arrow-up-right-circle text-3xl block mb-2 opacity-50"></i>
                                    <span>Belum ada usulan peningkatan standar pada periode ini.</span>
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
                                {{ isEditing ? 'Edit Peningkatan Standar Mutu' : 'Form Usulan Peningkatan Standar (Kaizen)' }}
                            </h3>
                            <p class="text-xs text-slate-500">Menaikkan baseline target standar yang telah tercapai untuk siklus PPEPP baru.</p>
                        </div>
                        <button @click="closeModal" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="saveItem" class="space-y-4 text-xs">
                        <!-- SEARCHABLE SELECT STANDAR MUTU -->
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Standar Mutu yang Ditingkatkan <span class="text-rose-500">*</span></label>
                            <SearchableSelect
                                v-model="form.standar_id"
                                :options="standarOptions"
                                placeholder="Pilih atau cari standar mutu..."
                                search-placeholder="Ketik kode / nama standar..."
                            />
                        </div>

                        <!-- SEARCHABLE SELECT INDIKATOR KINERJA SPESIFIK -->
                        <div v-if="filteredIndikators && filteredIndikators.length > 0">
                            <label class="block font-bold text-slate-700 mb-1">Pilih Indikator Kinerja Mutu (Otomatis Tarik Data Database)</label>
                            <SearchableSelect
                                v-model="form.indikator_kinerja_id"
                                :options="indikatorOptions"
                                placeholder="Pilih atau cari indikator kinerja (IKU/IKT)..."
                                search-placeholder="Ketik kode / deskripsi indikator..."
                                @change="applyIndikatorData"
                            />
                            <span class="text-[10px] text-indigo-600 font-medium mt-1.5 flex items-center gap-1">
                                <i class="bi bi-lightning-charge-fill text-amber-500"></i>
                                Memilih indikator akan otomatis mengisi Target Lama dan Capaian Terakhir dari database monitoring SPMI.
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Target Lama (Baseline Awal) <span class="text-rose-500">*</span></label>
                                <input v-model="form.target_lama" type="text" required placeholder="Contoh: IPK Lulusan >= 3.25 atau 80%" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500" />
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Capaian Terakhir (Hasil Evaluasi/AMI) <span class="text-rose-500">*</span></label>
                                <input v-model="form.capaian_saat_ini" type="text" required placeholder="Contoh: 3.42 (Tercapai 105%) atau 92%" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-emerald-800 mb-1">Target Baru yang Ditingkatkan (New Baseline Target Kaizen) <span class="text-rose-500">*</span></label>
                            <input v-model="form.target_baru" type="text" required placeholder="Contoh: IPK Lulusan >= 3.50 atau 95%" class="w-full px-3.5 py-2.5 rounded-xl border border-emerald-300 bg-emerald-50/40 font-bold text-emerald-900 focus:ring-2 focus:ring-emerald-500" />
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Dasar Pertimbangan Peningkatan (RTM / Temuan AMI)</label>
                            <textarea v-model="form.dasar_pertimbangan" rows="2" placeholder="Tuliskan latar belakang, rekomendasi RTM, atau regulasi yang mendasari peningkatan target ini..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Strategi Pencapaian Mutu Baru</label>
                            <textarea v-model="form.strategi_pencapaian" rows="2" placeholder="Langkah-langkah strategis atau alokasi sumber daya untuk mencapai target baru..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Status Persetujuan <span class="text-rose-500">*</span></label>
                            <select v-model="form.status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold focus:ring-2 focus:ring-indigo-500 bg-white">
                                <option value="diajukan">Diajukan (Menunggu Review RTM)</option>
                                <option value="disetujui">Disetujui (Disahkan oleh Pimpinan)</option>
                                <option value="diterapkan">Diterapkan (Masuk Renstra Periode Baru)</option>
                                <option value="draft">Draft Usulan</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="closeModal" class="px-4 py-2 rounded-xl font-bold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" :disabled="form.processing" class="px-5 py-2 rounded-xl font-bold bg-indigo-600 text-white hover:bg-indigo-500 shadow-md shadow-indigo-600/20 disabled:opacity-50">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Data Peningkatan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
