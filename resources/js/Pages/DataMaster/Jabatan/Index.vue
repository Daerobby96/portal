<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    jabatans: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const kategori = ref(props.filters?.kategori || '');

const modalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    kode: '',
    nama: '',
    kategori: 'fungsional_dosen',
    level_hirarki: 5,
    tunjangan_dasar: 0,
    deskripsi: '',
    is_aktif: true,
});

let searchTimeout = null;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 400);
};

const applyFilters = () => {
    router.get('/jabatan', {
        search: search.value,
        kategori: kategori.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    form.is_aktif = true;
    modalOpen.value = true;
};

const openEditModal = (j) => {
    isEditing.value = true;
    editingId.value = j.id;
    form.kode = j.kode;
    form.nama = j.nama;
    form.kategori = j.kategori;
    form.level_hirarki = j.level_hirarki || 1;
    form.tunjangan_dasar = j.tunjangan_dasar || 0;
    form.deskripsi = j.deskripsi || '';
    form.is_aktif = Boolean(j.is_aktif);
    modalOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/jabatan/${editingId.value}`, {
            onSuccess: () => {
                modalOpen.value = false;
                form.reset();
            }
        });
    } else {
        form.post('/jabatan', {
            onSuccess: () => {
                modalOpen.value = false;
                form.reset();
            }
        });
    }
};

const deleteJabatan = (j) => {
    if (confirm(`Apakah Anda yakin ingin menghapus Jabatan "${j.nama}"?`)) {
        router.delete(`/jabatan/${j.id}`);
    }
};

const formatCurrency = (val) => {
    if (!val) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};

const getKategoriBadge = (k) => {
    const map = {
        'struktural': 'bg-rose-50 text-rose-700 border-rose-200/60',
        'fungsional_dosen': 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
        'fungsional_tendik': 'bg-sky-50 text-sky-700 border-sky-200/60',
        'pelaksana': 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
    };
    return map[k] || 'bg-slate-50 text-slate-700 border-slate-200/60';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Master Jabatan & Fungsional" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-sky-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-sky-200 border border-white/20 uppercase tracking-wider">
                            Data Master
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            Kepegawaian & Jabatan
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Master Jabatan Struktural & Fungsional
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola nomenklatur jabatan pimpinan, jabatan akademik dosen (Jafung), tenaga kependidikan, dan level hirarki.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        @click="openCreateModal"
                        class="px-5 py-2.5 rounded-2xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-sky-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Jabatan</span>
                    </button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Jabatan</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Struktural</p>
                        <p class="text-xl font-black text-rose-600 leading-tight">{{ stats?.struktural || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Fungsional Dosen/Tendik</p>
                        <p class="text-xl font-black text-indigo-600 leading-tight">{{ stats?.fungsional || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jabatan Aktif</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center gap-3 flex-wrap">
                    <div class="relative flex-1 min-w-[240px]">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input
                            v-model="search"
                            @input="handleSearch"
                            type="text"
                            placeholder="Cari kode, nama jabatan, atau tugas pokok..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                    </div>

                    <select
                        v-model="kategori"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-sky-500"
                    >
                        <option value="">Semua Kategori Jabatan</option>
                        <option value="struktural">Struktural Pimpinan</option>
                        <option value="fungsional_dosen">Fungsional Dosen (Jafung)</option>
                        <option value="fungsional_tendik">Fungsional Tendik</option>
                        <option value="pelaksana">Pelaksana / Staf</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-6">Kode</th>
                                <th class="py-3.5 px-6">Nama Jabatan</th>
                                <th class="py-3.5 px-4">Kategori Jabatan</th>
                                <th class="py-3.5 px-4 text-center">Level Hirarki</th>
                                <th class="py-3.5 px-4 text-right">Tunjangan Dasar</th>
                                <th class="py-3.5 px-4 text-center">Pegawai Terkait</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="j in jabatans.data"
                                :key="j.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-sky-700">
                                    {{ j.kode }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ j.nama }}</div>
                                    <div v-if="j.deskripsi" class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ j.deskripsi }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border capitalize"
                                        :class="getKategoriBadge(j.kategori)"
                                    >
                                        {{ j.kategori?.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-slate-700">
                                    Level {{ j.level_hirarki }}
                                </td>
                                <td class="py-4 px-4 text-right font-mono font-semibold text-emerald-700">
                                    {{ formatCurrency(j.tunjangan_dasar) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 font-bold text-slate-800 text-xs">
                                        {{ j.pegawais_count || 0 }} Pegawai
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            @click="openEditModal(j)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition cursor-pointer"
                                            title="Edit Jabatan"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </button>
                                        <button
                                            @click="deleteJabatan(j)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Jabatan"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!jabatans.data || jabatans.data.length === 0">
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Belum ada data Jabatan yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="jabatans.links && jabatans.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ jabatans.from || 0 }} - {{ jabatans.to || 0 }} dari {{ jabatans.total }} jabatan
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in jabatans.links"
                            :key="i"
                            :href="link.url || '#'"
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold transition"
                            :class="link.active ? 'bg-sky-600 text-white font-bold' : (link.url ? 'text-slate-600 hover:bg-slate-100' : 'text-slate-300 pointer-events-none')"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Form Tambah / Edit -->
            <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                                <i class="bi bi-award"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">{{ isEditing ? 'Edit Master Jabatan' : 'Tambah Jabatan Baru' }}</h3>
                                <p class="text-[11px] text-slate-400">Struktur jabatan struktural dan fungsional akademik</p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kode Jabatan <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.kode"
                                    type="text"
                                    required
                                    placeholder="Contoh: KAPRODI"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-mono font-bold uppercase"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kategori Jabatan <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.kategori"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                >
                                    <option value="struktural">Struktural Pimpinan</option>
                                    <option value="fungsional_dosen">Fungsional Dosen (Jafung)</option>
                                    <option value="fungsional_tendik">Fungsional Tendik</option>
                                    <option value="pelaksana">Pelaksana / Staf</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Nama Jabatan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                placeholder="Contoh: Koordinator Program Studi"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-bold text-slate-900"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Level Hirarki (1 s.d. 10) <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model.number="form.level_hirarki"
                                    type="number"
                                    min="1"
                                    max="20"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Tunjangan Jabatan (Rp)
                                </label>
                                <input
                                    v-model.number="form.tunjangan_dasar"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-mono"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Deskripsi Tugas Pokok & Fungsi
                            </label>
                            <textarea
                                v-model="form.deskripsi"
                                rows="2"
                                placeholder="Keterangan tupoksi jabatan..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-sky-600 hover:bg-sky-500 text-white shadow-md shadow-sky-600/30">
                                {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Perbarui Jabatan' : 'Simpan Jabatan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
