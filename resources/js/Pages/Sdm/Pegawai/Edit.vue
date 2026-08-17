<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pegawai: Object,
    users: Array,
});

const form = useForm({
    nama: props.pegawai.nama,
    nip: props.pegawai.nip || '',
    email: props.pegawai.email || '',
    no_hp: props.pegawai.no_hp || '',
    jabatan: props.pegawai.jabatan || '',
    unit_kerja: props.pegawai.unit_kerja || '',
    jenis_pegawai: props.pegawai.jenis_pegawai,
    status_kepegawaian: props.pegawai.status_kepegawaian || 'Tetap Yayasan',
    is_aktif: Boolean(props.pegawai.is_aktif),
});

const submit = () => {
    form.put(`/sdm/pegawai/${props.pegawai.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Pegawai: ${pegawai.nama}`" />

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
                            NIP: {{ pegawai.nip || '-' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase" :class="pegawai.is_aktif ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/30' : 'bg-rose-500/30 text-rose-200 border border-rose-400/30'">
                            {{ pegawai.is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Data Pegawai
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Perbarui informasi identitas kepegawaian, penugasan unit kerja, dan kontak komunikasi resmi.
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

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Jabatan Fungsional / Struktural
                                    </label>
                                    <input
                                        v-model="form.jabatan"
                                        type="text"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Unit Kerja / Program Studi
                                    </label>
                                    <input
                                        v-model="form.unit_kerja"
                                        type="text"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                </div>
                            </div>

                            <div class="pt-2">
                                <label class="flex items-center gap-3 p-3.5 rounded-2xl bg-indigo-50/60 border border-indigo-100 cursor-pointer hover:bg-indigo-50 transition">
                                    <input
                                        v-model="form.is_aktif"
                                        type="checkbox"
                                        class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                    />
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">Status Pegawai Aktif</span>
                                        <span class="text-[10px] text-slate-500">Pegawai aktif menerima penugasan, presensi harian, dan evaluasi tridharma</span>
                                    </div>
                                </label>
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
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Pegawai' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Info Akun & Status</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-2">
                            <p><strong>Nama:</strong> {{ pegawai.nama }}</p>
                            <p><strong>NIP:</strong> <span class="font-mono">{{ pegawai.nip || '-' }}</span></p>
                            <p><strong>Akun Login:</strong> <span :class="pegawai.user_id ? 'text-emerald-600 font-bold' : 'text-slate-400'">{{ pegawai.user_id ? 'Tersinkron (User ID #' + pegawai.user_id + ')' : 'Belum Ada Akun' }}</span></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
