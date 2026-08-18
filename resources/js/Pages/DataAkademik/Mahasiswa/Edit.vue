<template>
    <AuthenticatedLayout title="Edit Data Mahasiswa">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Data Mahasiswa</h1>
                    <p class="text-xs text-slate-500 mt-1">Perbarui data induk dan riwayat akademik {{ mahasiswa.nama }}.</p>
                </div>
                <Link
                    href="/mahasiswa"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition"
                >
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali</span>
                </Link>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs space-y-6">
                <!-- Section 1: Identitas Akademik -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase text-sky-700 tracking-wider flex items-center gap-2 pb-2 border-b border-slate-100">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>1. Informasi Akademik Utama</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">NIM (Nomor Induk Mahasiswa) <span class="text-rose-500">*</span></label>
                            <input
                                v-model="form.nim"
                                type="text"
                                required
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi</label>
                            <select
                                v-model="form.prodi_id"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30 bg-white"
                            >
                                <option value="">-- Pilih Program Studi --</option>
                                <option v-for="p in prodis" :key="p.id" :value="p.id">{{ p.nama }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Periode Masuk / Angkatan</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input
                                    v-model="form.angkatan"
                                    type="number"
                                    placeholder="Tahun: 2024"
                                    min="2000"
                                    max="2100"
                                    class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500/30"
                                />
                                <input
                                    v-model="form.semester_berjalan"
                                    type="number"
                                    placeholder="Smstr: 1"
                                    min="1"
                                    max="14"
                                    class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500/30"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status Mahasiswa <span class="text-rose-500">*</span></label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30 bg-white font-bold"
                            >
                                <option v-for="(lbl, val) in statusOptions" :key="val" :value="val">{{ lbl }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jalur Penerimaan</label>
                            <select
                                v-model="form.jalur_masuk"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30 bg-white"
                            >
                                <option value="">-- Pilih Jalur Masuk --</option>
                                <option v-for="j in jalurOptions" :key="j" :value="j">{{ j }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">IPK Terkini (Skala 4.00)</label>
                            <input
                                v-model="form.ipk"
                                type="number"
                                step="0.01"
                                min="0"
                                max="4"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Masuk</label>
                            <input
                                v-model="form.tanggal_masuk"
                                type="date"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Kelulusan</label>
                            <input
                                v-model="form.tanggal_lulus"
                                type="date"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Masa Studi (Bulan)</label>
                            <input
                                v-model="form.masa_studi_bulan"
                                type="number"
                                min="0"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Biodata Pribadi -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-black uppercase text-sky-700 tracking-wider flex items-center gap-2 pb-2 border-b border-slate-100">
                        <i class="bi bi-person-vcard-fill"></i>
                        <span>2. Biodata & Kontak Pribadi</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                            <select
                                v-model="form.jenis_kelamin"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30 bg-white"
                            >
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                            <input
                                v-model="form.nik"
                                type="text"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-mono outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tempat Lahir</label>
                            <input
                                v-model="form.tempat_lahir"
                                type="text"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Lahir</label>
                            <input
                                v-model="form.tanggal_lahir"
                                type="date"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor HP / WhatsApp</label>
                            <input
                                v-model="form.no_hp"
                                type="text"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30 font-mono"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Aktif</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                            <textarea
                                v-model="form.alamat"
                                rows="2"
                                class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-sky-500/30"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                    <Link
                        href="/mahasiswa"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5 cursor-pointer"
                    >
                        <i v-if="form.processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    mahasiswa: Object,
    prodis: Array,
    periodes: Array,
    statusOptions: Object,
    jalurOptions: Array,
});

const form = useForm({
    nim: props.mahasiswa.nim || '',
    nama: props.mahasiswa.nama || '',
    jenis_kelamin: props.mahasiswa.jenis_kelamin || 'L',
    no_hp: props.mahasiswa.no_hp || '',
    email: props.mahasiswa.email || '',
    prodi_id: props.mahasiswa.prodi_id || '',
    periode_id: props.mahasiswa.periode_id || '',
    angkatan: props.mahasiswa.angkatan || '',
    semester_berjalan: props.mahasiswa.semester_berjalan || 1,
    jalur_masuk: props.mahasiswa.jalur_masuk || '',
    ipk: props.mahasiswa.ipk || '',
    status: props.mahasiswa.status || 'aktif',
    tanggal_masuk: props.mahasiswa.tanggal_masuk ? props.mahasiswa.tanggal_masuk.substring(0, 10) : '',
    tanggal_lulus: props.mahasiswa.tanggal_lulus ? props.mahasiswa.tanggal_lulus.substring(0, 10) : '',
    masa_studi_bulan: props.mahasiswa.masa_studi_bulan || '',
    keterangan: props.mahasiswa.keterangan || '',
    tempat_lahir: props.mahasiswa.tempat_lahir || '',
    tanggal_lahir: props.mahasiswa.tanggal_lahir ? props.mahasiswa.tanggal_lahir.substring(0, 10) : '',
    nik: props.mahasiswa.nik || '',
    alamat: props.mahasiswa.alamat || '',
});

function submit() {
    form.put(`/mahasiswa/${props.mahasiswa.id}`);
}
</script>
