<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    ruangans: Object,
    prodis: Array,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const jenis = ref(props.filters?.jenis || '');
const status = ref(props.filters?.status || '');

const modalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    kode_ruangan: '',
    nama_ruangan: '',
    jenis: 'kelas',
    gedung: '',
    lantai: '',
    kapasitas: 40,
    luas: 0,
    kondisi: 'baik',
    status: 'tersedia',
    ber_ac: true,
    ber_proyektor: true,
    penanggung_jawab: '',
    fasilitas: '',
    keterangan: '',
    prodi_id: '',
});

let searchTimeout = null;
const handleSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 400);
};

const applyFilters = () => {
    router.get('/ruangan', {
        search: search.value,
        jenis: jenis.value,
        status: status.value,
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
    form.ber_ac = true;
    form.ber_proyektor = true;
    form.kondisi = 'baik';
    form.status = 'tersedia';
    modalOpen.value = true;
};

const openEditModal = (r) => {
    isEditing.value = true;
    editingId.value = r.id;
    form.kode_ruangan = r.kode_ruangan;
    form.nama_ruangan = r.nama_ruangan;
    form.jenis = r.jenis;
    form.gedung = r.gedung || '';
    form.lantai = r.lantai || '';
    form.kapasitas = r.kapasitas || 0;
    form.luas = r.luas || 0;
    form.kondisi = r.kondisi || 'baik';
    form.status = r.status || 'tersedia';
    form.ber_ac = Boolean(r.ber_ac);
    form.ber_proyektor = Boolean(r.ber_proyektor);
    form.penanggung_jawab = r.penanggung_jawab || '';
    form.fasilitas = r.fasilitas || '';
    form.keterangan = r.keterangan || '';
    form.prodi_id = r.prodi_id || '';
    modalOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/ruangan/${editingId.value}`, {
            onSuccess: () => {
                modalOpen.value = false;
                form.reset();
            }
        });
    } else {
        form.post('/ruangan', {
            onSuccess: () => {
                modalOpen.value = false;
                form.reset();
            }
        });
    }
};

const deleteRuangan = (r) => {
    if (confirm(`Apakah Anda yakin ingin menghapus Ruangan "${r.nama_ruangan}"?`)) {
        router.delete(`/ruangan/${r.id}`);
    }
};

const getJenisBadge = (j) => {
    const map = {
        'kelas': 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
        'lab': 'bg-purple-50 text-purple-700 border-purple-200/60',
        'ruang_rapat': 'bg-amber-50 text-amber-700 border-amber-200/60',
        'ruang_dosen': 'bg-sky-50 text-sky-700 border-sky-200/60',
        'perpustakaan': 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
    };
    return map[j] || 'bg-slate-50 text-slate-700 border-slate-200/60';
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Master Ruangan & Gedung Kampus" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-sky-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-sky-200 border border-white/20 uppercase tracking-wider">
                            Data Master
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            Sarana & Prasarana
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Master Ruangan & Gedung Kampus
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola sarana gedung, ruang perkuliahan teori, laboratorium praktikum, ruang rapat pimpinan, dan fasilitas ruangan.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        @click="openCreateModal"
                        class="px-5 py-2.5 rounded-2xl bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-sky-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Ruangan</span>
                    </button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-door-open-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Ruangan</p>
                        <p class="text-xl font-black text-slate-900 leading-tight">{{ stats?.total || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-easel-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ruang Kelas Teori</p>
                        <p class="text-xl font-black text-indigo-600 leading-tight">{{ stats?.kelas || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laboratorium</p>
                        <p class="text-xl font-black text-purple-600 leading-tight">{{ stats?.lab || 0 }}</p>
                    </div>
                </div>

                <div class="p-4.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Tersedia</p>
                        <p class="text-xl font-black text-emerald-600 leading-tight">{{ stats?.tersedia || 0 }}</p>
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
                            placeholder="Cari kode ruangan, nama ruang, gedung, atau lantai..."
                            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                    </div>

                    <select
                        v-model="jenis"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-sky-500"
                    >
                        <option value="">Semua Tipe Ruang</option>
                        <option value="kelas">Ruang Kelas Teori</option>
                        <option value="lab">Laboratorium / Bengkel</option>
                        <option value="ruang_rapat">Ruang Rapat</option>
                        <option value="ruang_dosen">Ruang Dosen</option>
                        <option value="perpustakaan">Perpustakaan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>

                    <select
                        v-model="status"
                        @change="applyFilters"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-sky-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="tidak_tersedia">Tidak Tersedia</option>
                        <option value="dalam_perbaikan">Dalam Perbaikan</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <th class="py-3.5 px-6">Kode Ruangan</th>
                                <th class="py-3.5 px-6">Nama Ruang & Prodi</th>
                                <th class="py-3.5 px-4">Tipe Ruangan</th>
                                <th class="py-3.5 px-4">Lokasi Gedung</th>
                                <th class="py-3.5 px-4 text-center">Kapasitas</th>
                                <th class="py-3.5 px-4">Fasilitas Utama</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr
                                v-for="r in ruangans.data"
                                :key="r.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-sky-700">
                                    {{ r.kode_ruangan }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ r.nama_ruangan }}</div>
                                    <div v-if="r.prodi" class="text-[11px] text-sky-600 mt-0.5 font-semibold">
                                        Prodi: {{ r.prodi.nama }} ({{ r.prodi.jenjang }})
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border capitalize"
                                        :class="getJenisBadge(r.jenis)"
                                    >
                                        {{ r.jenis?.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-semibold text-slate-800">{{ r.gedung || 'Gedung Terpadu' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ r.lantai || 'Lantai 1' }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-slate-700">
                                    {{ r.kapasitas || 0 }} Kursi
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span v-if="r.ber_ac" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200/60">
                                            <i class="bi bi-snow mr-0.5"></i> AC
                                        </span>
                                        <span v-if="r.ber_proyektor" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <i class="bi bi-projector mr-0.5"></i> Proyektor
                                        </span>
                                        <span v-if="!r.ber_ac && !r.ber_proyektor" class="text-[11px] text-slate-400 italic">
                                            Standar
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border capitalize"
                                        :class="r.status === 'tersedia' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60' : 'bg-rose-50 text-rose-700 border-rose-200/60'"
                                    >
                                        {{ r.status?.replace('_', ' ') }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            @click="openEditModal(r)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition cursor-pointer"
                                            title="Edit Ruangan"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </button>
                                        <button
                                            @click="deleteRuangan(r)"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus Ruangan"
                                        >
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!ruangans.data || ruangans.data.length === 0">
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    Belum ada data Ruangan yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="ruangans.links && ruangans.links.length > 3" class="p-4 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ ruangans.from || 0 }} - {{ ruangans.to || 0 }} dari {{ ruangans.total }} ruangan
                    </p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in ruangans.links"
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
                <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl border border-slate-100 space-y-5 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">{{ isEditing ? 'Edit Master Ruangan' : 'Tambah Ruangan Baru' }}</h3>
                                <p class="text-[11px] text-slate-400">Data sarana ruangan, kapasitas, dan fasilitas</p>
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
                                    Kode Ruangan <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.kode_ruangan"
                                    type="text"
                                    required
                                    placeholder="Contoh: R-101 / LAB-IF-1"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-mono font-bold uppercase"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Tipe Ruangan <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.jenis"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                >
                                    <option value="kelas">Ruang Kelas Teori</option>
                                    <option value="lab">Laboratorium / Bengkel</option>
                                    <option value="ruang_rapat">Ruang Rapat</option>
                                    <option value="ruang_dosen">Ruang Dosen</option>
                                    <option value="perpustakaan">Perpustakaan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Nama Ruangan <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_ruangan"
                                type="text"
                                required
                                placeholder="Contoh: Ruang Kuliah Teori 101"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-bold text-slate-900"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Gedung Kampus
                                </label>
                                <input
                                    v-model="form.gedung"
                                    type="text"
                                    placeholder="Contoh: Gedung Kuliah Terpadu A"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Lantai Ke-
                                </label>
                                <input
                                    v-model="form.lantai"
                                    type="text"
                                    placeholder="Contoh: Lantai 2"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kapasitas (Kursi / Orang)
                                </label>
                                <input
                                    v-model.number="form.kapasitas"
                                    type="number"
                                    min="1"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Program Studi Pengelola (Opsional)
                                </label>
                                <select
                                    v-model="form.prodi_id"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                >
                                    <option value="">Fasilitas Bersama / Kampus</option>
                                    <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.jenjang }})</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fasilitas Checkboxes -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-6">
                            <label class="flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer">
                                <input type="checkbox" v-model="form.ber_ac" class="rounded text-sky-600 focus:ring-sky-500" />
                                <span>Dilengkapi AC</span>
                            </label>

                            <label class="flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer">
                                <input type="checkbox" v-model="form.ber_proyektor" class="rounded text-sky-600 focus:ring-sky-500" />
                                <span>Dilengkapi Proyektor</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kondisi Fisik <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.kondisi"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                >
                                    <option value="baik">Kondisi Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Status Ketersediaan <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    v-model="form.status"
                                    required
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                                >
                                    <option value="tersedia">Tersedia Digunakan</option>
                                    <option value="tidak_tersedia">Tidak Tersedia</option>
                                    <option value="dalam_perbaikan">Dalam Pemeliharaan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Fasilitas Tambahan / Keterangan
                            </label>
                            <textarea
                                v-model="form.fasilitas"
                                rows="2"
                                placeholder="Contoh: Sound system, Whiteboard kaca, 30 PC Core i7..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" :disabled="form.processing" class="px-6 py-2 rounded-xl text-xs font-bold bg-sky-600 hover:bg-sky-500 text-white shadow-md shadow-sky-600/30">
                                {{ form.processing ? 'Menyimpan...' : (isEditing ? 'Perbarui Ruangan' : 'Simpan Ruangan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
