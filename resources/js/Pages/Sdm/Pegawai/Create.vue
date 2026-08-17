<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    users: Array,
    unitKerjas: Array,
    jabatans: Array,
});

const form = useForm({
    nama: '',
    nip: '',
    email: '',
    no_hp: '',
    unit_kerja_id: '',
    jabatan_id: '',
    jabatan: '',
    unit_kerja: '',
    jenis_pegawai: 'Dosen',
    status_kepegawaian: 'Tetap Yayasan',
    user_id: '',
});

const onUnitKerjaSelect = (opt) => {
    if (opt) {
        form.unit_kerja = opt.label || '';
    } else {
        form.unit_kerja = '';
    }
};

const onJabatanSelect = (opt) => {
    if (opt) {
        form.jabatan = opt.label || '';
    } else {
        form.jabatan = '';
    }
};

const submit = () => {
    form.post('/sdm/pegawai');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Pegawai Baru" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/pegawai" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Master Pegawai
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Entri Data Induk SDM
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Tambah Pegawai / Dosen Baru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Daftarkan data identitas dosen atau tenaga kependidikan untuk sinkronisasi tridharma, presensi, dan penilaian kinerja.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        placeholder="Contoh: Dr. Ir. Ahmad Dahlan, M.Kom."
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.nama" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        NIP / NIDN / NUPTK
                                    </label>
                                    <input
                                        v-model="form.nip"
                                        type="text"
                                        placeholder="Contoh: 0412088501"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold font-mono text-slate-900"
                                    />
                                    <p v-if="form.errors.nip" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nip }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Alamat Email Resmi
                                    </label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        placeholder="dosen@polka.ac.id"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.email" class="text-rose-500 text-[11px] mt-1">{{ form.errors.email }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nomor WhatsApp / HP
                                    </label>
                                    <input
                                        v-model="form.no_hp"
                                        type="text"
                                        placeholder="081234567890"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900 font-mono"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Jenis Pegawai <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.jenis_pegawai"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="Dosen">Dosen (Tenaga Pendidik)</option>
                                        <option value="Tenaga Kependidikan">Tenaga Kependidikan (Tendik)</option>
                                        <option value="Lainnya">Lainnya / Struktural</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Status Kepegawaian
                                    </label>
                                    <select
                                        v-model="form.status_kepegawaian"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="PNS">PNS (Pegawai Negeri Sipil)</option>
                                        <option value="PPPK">PPPK</option>
                                        <option value="Tetap Yayasan">Tetap Yayasan / Institusi</option>
                                        <option value="Kontrak">Kontrak</option>
                                        <option value="Honorer">Honorer / Luar Biasa</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Autocomplete Search Dropdowns -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Master Jabatan & Fungsional (Autocomplete)
                                    </label>
                                    <SearchableSelect
                                        v-model="form.jabatan_id"
                                        :options="jabatans"
                                        placeholder="Cari atau pilih jabatan..."
                                        search-placeholder="Ketik nama/kode jabatan..."
                                        @change="onJabatanSelect"
                                    />
                                    <input
                                        v-if="!form.jabatan_id"
                                        v-model="form.jabatan"
                                        type="text"
                                        placeholder="Atau ketik nama jabatan manual..."
                                        class="w-full mt-2 px-4 py-2 text-xs rounded-xl border border-dashed border-slate-200 focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Master Unit Kerja (Autocomplete)
                                    </label>
                                    <SearchableSelect
                                        v-model="form.unit_kerja_id"
                                        :options="unitKerjas"
                                        placeholder="Cari atau pilih unit kerja..."
                                        search-placeholder="Ketik jurusan/biro/lembaga..."
                                        @change="onUnitKerjaSelect"
                                    />
                                    <input
                                        v-if="!form.unit_kerja_id"
                                        v-model="form.unit_kerja"
                                        type="text"
                                        placeholder="Atau ketik nama unit kerja manual..."
                                        class="w-full mt-2 px-4 py-2 text-xs rounded-xl border border-dashed border-slate-200 focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/sdm/pegawai"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30"
                                >
                                    <i class="bi bi-person-check-fill"></i>
                                    <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Pegawai' }}</span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Right Column: Account Linkage (4 of 12) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-center gap-2 text-indigo-900 font-bold text-xs">
                            <i class="bi bi-link-45deg text-lg text-indigo-600"></i>
                            <span>Tautkan Akun Sistem (User)</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Cari dan pilih akun login portal untuk menghubungkan data pegawai dengan pengguna sistem:
                        </p>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Pilih Akun User</label>
                            <SearchableSelect
                                v-model="form.user_id"
                                :options="users"
                                placeholder="Cari nama atau email user..."
                                search-placeholder="Ketik email / nama..."
                            />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
