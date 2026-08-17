<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    unit_kerjas: Object,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const tipe = ref(props.filters?.tipe || '');

const modalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    kode: '',
    nama: '',
    tipe: 'jurusan',
    kepala_unit: '',
    lokasi: '',
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
    router.get('/unit-kerja', {
        search: search.value,
        tipe: tipe.value,
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

const openEditModal = (u) => {
    isEditing.value = true;
    editingId.value = u.id;
    form.kode = u.kode;
    form.nama = u.nama;
    form.tipe = u.tipe;
    form.kepala_unit = u.kepala_unit || '';
    form.lokasi = u.lokasi || '';
    form.deskripsi = u.deskripsi || '';
    form.is_aktif = Boolean(u.is_aktif);
    modalOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/unit-kerja/${editingId.value}`, {
            onSuccess: () => {
                modalOpen.value = false;
                form.reset();
            }
        });
    } else {
        form.post('/unit-kerja', {
            onSuccess: () => {
                modalOpen.value = false;
                form.reset();
            }
        });
    }
};

const deleteUnit = (u) => {
    if (confirm(`Apakah Anda yakin ingin menghapus Unit Kerja "${u.nama}"?`)) {
        router.delete(`/unit-kerja/${u.id}`);
    }
};

const getTipeBadge = (t) => {
    const map = {
        'pimpinan': 'bg-rose-50 text-rose-700 border-rose-200/60',
        'jurusan': 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
        'prodi': 'bg-sky-50 text-sky-700 border-sky-200/60',
        'biro': 'bg-amber-50 text-amber-700 border-amber-200/60',
        'lembaga': 'bg-purple-50 text-purple-700 border-purple-200/60',
        'upt': 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
    };
    return map[t] || 'bg-slate-50 text-slate-700 border-slate-200/60';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Master Unit Kerja & Lembaga" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-sky-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-sky-200 border border-white/20 uppercase tracking-wider">
                            Data Master
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            Struktur Organisasi
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Master Unit Kerja & Lembaga
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola struktur organisasi kampus, jurusan, biro administrasi, lembaga riset/mutu, dan UPT penunjang institusi.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        @click="openCreateModal"
                        class="px-5 py-2.5 rounded-2xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-sky-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Unit Kerja</span>
                    </button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Unit Kerja</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Unit Aktif</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.aktif || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jurusan</p>
                        <p class="text-xl font-black text-indigo-600 leading-tight">{{ stats?.jurusan || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Biro, Lembaga, UPT</p>
                        <p class="text-xl font-black text-purple-600 leading-tight">{{ stats?.upt || 0 }}</p>
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
                            placeholder="Cari kode, nama unit, pimpinan, atau lokasi..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                    </div>

                    <select
                        v-model="tipe"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-sky-500"
                    >
                        <option value="">Semua Kategori Unit</option>
                        <option value="pimpinan">Pimpinan / Rektorat</option>
                        <option value="jurusan">Jurusan</option>
                        <option value="prodi">Program Studi</option>
                        <option value="biro">Biro Administrasi</option>
                        <option value="lembaga">Lembaga (LPPM/Mutu)</option>
                        <option value="upt">UPT Penunjang</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-6">Kode Unit</th>
                                <th class="py-3.5 px-6">Nama Unit Kerja / Lembaga</th>
                                <th class="py-3.5 px-4">Kategori Tipe</th>
                                <th class="py-3.5 px-4">Kepala / Pimpinan Unit</th>
                                <th class="py-3.5 px-4">Lokasi Gedung</th>
                                <th class="py-3.5 px-4 text-center">Pegawai Terdaftar</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="u in unit_kerjas.data"
                                :key="u.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-sky-700">
                                    {{ u.kode }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ u.nama }}</div>
                                    <div v-if="u.deskripsi" class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ u.deskripsi }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border"
                                        :class="getTipeBadge(u.tipe)"
                                    >
                                        {{ u.tipe }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-800">
                                    {{ u.kepala_unit || '-' }}
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ u.lokasi || '-' }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 font-bold text-slate-800 text-xs">
                                        {{ u.pegawais_count || 0 }} Pegawai
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            @click="openEditModal(u)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition cursor-pointer"
                                            title="Edit Unit Kerja"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </button>
                                        <button
                                            @click="deleteUnit(u)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Unit Kerja"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!unit_kerjas.data || unit_kerjas.data.length === 0">
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Belum ada data Unit Kerja yang sesuai dengan filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="unit_kerjas.links && unit_kerjas.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ unit_kerjas.from || 0 }} - {{ unit_kerjas.to || 0 }} dari {{ unit_kerjas.total }} unit
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in unit_kerjas.links"
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
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">{{ isEditing ? 'Edit Unit Kerja' : 'Tambah Unit Kerja Baru' }}</h3>
                                <p class="text-[11px] text-slate-400">Struktur organisasi dan penempatan personil</p>
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
                                    Kode Unit <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.kode"
                                    type="text"
                                    required
                                    placeholder="Contoh: JUR-TI"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-mono font-bold uppercase"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kategori Tipe <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.tipe"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                >
                                    <option value="jurusan">Jurusan</option>
                                    <option value="prodi">Program Studi</option>
                                    <option value="biro">Biro Administrasi</option>
                                    <option value="lembaga">Lembaga (LPPM / Mutu)</option>
                                    <option value="upt">UPT Penunjang</option>
                                    <option value="pimpinan">Pimpinan / Rektorat</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Nama Unit Kerja / Lembaga <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                placeholder="Contoh: Jurusan Teknologi Informasi"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-bold text-slate-900"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kepala / Koordinator Unit
                                </label>
                                <input
                                    v-model="form.kepala_unit"
                                    type="text"
                                    placeholder="Contoh: Dr. Ir. Fulan, M.T."
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Lokasi Gedung / Ruang
                                </label>
                                <input
                                    v-model="form.lokasi"
                                    type="text"
                                    placeholder="Contoh: Gedung A Lt. 2"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Deskripsi / Tugas Pokok
                            </label>
                            <textarea
                                v-model="form.deskripsi"
                                rows="2"
                                placeholder="Keterangan ruang lingkup unit kerja..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-sky-600 hover:bg-sky-500 text-white shadow-md shadow-sky-600/30">
                                {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Perbarui Unit Kerja' : 'Simpan Unit Kerja') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
