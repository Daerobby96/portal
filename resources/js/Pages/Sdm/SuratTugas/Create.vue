<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pegawais: Array,
    nomorSurat: String,
});

const form = useForm({
    nomor_surat: props.nomorSurat,
    perihal: '',
    keperluan: '',
    tanggal_mulai: new Date().toISOString().split('T')[0],
    tanggal_selesai: new Date().toISOString().split('T')[0],
    tempat_tujuan: '',
    jenis: 'dinas_luar',
    anggaran: 0,
    sumber_dana: 'DIPA / Anggaran Institusi',
    catatan: '',
    pegawai_ids: [],
    peran: [],
    file_surat: null,
});

const addPegawai = (pegawaiId) => {
    if (!form.pegawai_ids.includes(pegawaiId)) {
        form.pegawai_ids.push(pegawaiId);
        form.peran.push(form.pegawai_ids.length === 1 ? 'ketua' : 'anggota');
    }
};

const removePegawai = (index) => {
    form.pegawai_ids.splice(index, 1);
    form.peran.splice(index, 1);
};

const submit = () => {
    form.post('/sdm/surat-tugas');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Penerbitan Surat Tugas Kedinasan" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/sdm/surat-tugas" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Surat Tugas
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Penerbitan Surat Tugas
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Penerbitan Surat Tugas Baru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Terbitkan surat tugas kedinasan dosen dan staf untuk keperluan tridharma, pelatihan, seminar, dan perjalanan dinas luar kota.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nomor Surat Tugas <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nomor_surat"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold font-mono text-slate-900"
                                    />
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Perihal / Agenda Tugas <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.perihal"
                                        type="text"
                                        required
                                        placeholder="Contoh: Mengikuti Workshop Akreditasi LAM-INFOKOM"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.perihal" class="text-rose-500 text-[11px] mt-1">{{ form.errors.perihal }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Jenis Penugasan <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.jenis"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    >
                                        <option value="dinas_luar">Dinas Luar Kota</option>
                                        <option value="perjalanan_dinas">Perjalanan Dinas (SPPD)</option>
                                        <option value="pelatihan">Pelatihan / Diklat</option>
                                        <option value="seminar">Seminar / Konferensi</option>
                                        <option value="tugas_khusus">Tugas Khusus Rektorat</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Mulai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_mulai"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Selesai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_selesai"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Tempat & Lokasi Tujuan <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.tempat_tujuan"
                                    type="text"
                                    required
                                    placeholder="Contoh: Hotel Grand Sahid Jaya, Jakarta Pusat"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                />
                            </div>

                            <!-- Pilih Pegawai yang Ditugaskan -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                                        Pegawai Yang Ditugaskan <span class="text-rose-500">*</span>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium">Total: {{ form.pegawai_ids.length }} Pegawai</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <select
                                        @change="e => { addPegawai(Number(e.target.value)); e.target.value = ''; }"
                                        class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option value="">+ Tambah Pegawai ke Surat Tugas</option>
                                        <option v-for="p in pegawais" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.nip || 'No NIP' }} - {{ p.unit_kerja }})</option>
                                    </select>
                                </div>

                                <div v-if="form.pegawai_ids.length > 0" class="space-y-2 pt-2">
                                    <div
                                        v-for="(id, idx) in form.pegawai_ids"
                                        :key="id"
                                        class="p-3 rounded-xl bg-white border border-slate-200 flex items-center justify-between gap-3 text-xs"
                                    >
                                        <span class="font-bold text-slate-900 flex-1">
                                            {{ pegawais.find(p => p.id === id)?.nama }}
                                        </span>
                                        <select
                                            v-model="form.peran[idx]"
                                            class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 font-semibold text-slate-700 bg-slate-50 focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <option value="ketua">Ketua Tim</option>
                                            <option value="anggota">Anggota Tim</option>
                                            <option value="penanggung_jawab">Penanggung Jawab</option>
                                        </select>
                                        <button
                                            type="button"
                                            @click="removePegawai(idx)"
                                            class="p-1.5 rounded-xl text-rose-500 hover:bg-rose-50 transition"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Rincian Keperluan & Tugas <span class="text-rose-500">*</span>
                                </label>
                                <textarea
                                    v-model="form.keperluan"
                                    rows="3"
                                    required
                                    placeholder="Rincian agenda dan tujuan kedinasan..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/sdm/surat-tugas"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing || form.pegawai_ids.length === 0"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menerbitkan...' : 'Terbitkan Surat Tugas' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Info Standar Penugasan</h4>
                        <div class="space-y-2 text-xs text-slate-600 leading-relaxed">
                            <p><strong>Pelaporan:</strong> Pegawai yang telah menyelesaikan tugas kedinasan wajib menginput laporan hasil pada detail surat tugas.</p>
                            <p><strong>Presensi:</strong> Selama periode surat tugas aktif, status absensi harian pegawai otomatis tercatat sebagai <em>Dinas Luar</em>.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
