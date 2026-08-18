<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    dokumen: Object,
    kategoris: Array,
    standars: Array,
    selectedStandars: Array,
    unitKerjas: {
        type: Array,
        default: () => [],
    },
    prodis: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    kategori_id: props.dokumen.kategori_id,
    standar_ids: props.selectedStandars || (props.dokumen.standars ? props.dokumen.standars.map((s) => s.id) : []),
    judul: props.dokumen.judul,
    unit_pemilik: props.dokumen.unit_pemilik || '',
    versi: props.dokumen.versi || '1.0',
    tanggal_terbit: props.dokumen.tanggal_terbit ? props.dokumen.tanggal_terbit.split('T')[0] : '',
    tanggal_kadaluarsa: props.dokumen.tanggal_kadaluarsa ? props.dokumen.tanggal_kadaluarsa.split('T')[0] : '',
    status: props.dokumen.status || 'draft',
    is_public: Boolean(props.dokumen.is_public),
    keterangan: props.dokumen.keterangan || '',
    file: null,
});

const unitOptions = computed(() => {
    const list = [
        { value: 'Pusat Penjaminan Mutu & Pengembangan Pendidikan (P4MP)', label: 'Pusat Penjaminan Mutu & Pengembangan Pendidikan (P4MP)', subtext: 'Lembaga Mutu Kampus', badge: 'P4MP' },
        { value: 'Direktorat & Pimpinan Kampus', label: 'Direktorat & Pimpinan Kampus', subtext: 'Pimpinan Institusi', badge: 'DIR' },
        { value: 'Semua Program Studi', label: 'Semua Program Studi', subtext: 'Seluruh Program Studi', badge: 'PRODI' },
    ];

    (props.unitKerjas || []).forEach((u) => {
        if (!list.some((item) => item.value === u.nama)) {
            list.push({
                value: u.nama,
                label: u.nama,
                subtext: `Unit Kerja / ${u.tipe || 'Lembaga'} (${u.kode})`,
                badge: u.kode,
            });
        }
    });

    (props.prodis || []).forEach((p) => {
        list.push({
            value: `Program Studi ${p.nama}`,
            label: `Program Studi ${p.nama}`,
            subtext: `Program Studi (Kode: ${p.kode})`,
            badge: 'PRODI',
        });
    });

    return list;
});

const handleFileChange = (e) => {
    form.file = e.target.files[0];
};

const submit = () => {
    form.post(`/dokumen/${props.dokumen.id}`, {
        _method: 'put',
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Dokumen: ${dokumen.judul}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <Link href="/dokumen" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Daftar Dokumen</span>
                    </Link>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            {{ dokumen.kode_dokumen || dokumen.kode }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/20 text-white">
                            {{ dokumen.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Dokumen Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Perbarui metadata, versi dokumen, standar acuan, atau ganti berkas lampiran.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5 text-xs">
                            <!-- Judul Dokumen -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Judul Dokumen / Formulir <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.judul"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                />
                                <p v-if="form.errors.judul" class="text-rose-500 text-[11px] mt-1">{{ form.errors.judul }}</p>
                            </div>

                            <!-- Row 1: Kategori, Unit Pemilik, Versi -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Kategori Dokumen <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.kategori_id"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold bg-white"
                                    >
                                        <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Unit Pemilik <span class="text-rose-500">*</span>
                                    </label>
                                    <SearchableSelect
                                        v-model="form.unit_pemilik"
                                        :options="unitOptions"
                                        :allow-custom="true"
                                        placeholder="Pilih unit pemilik..."
                                        search-placeholder="Cari unit atau ketik manual..."
                                    />
                                    <p v-if="form.errors.unit_pemilik" class="text-rose-500 text-[11px] mt-1">{{ form.errors.unit_pemilik }}</p>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Versi Dokumen <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.versi"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono font-bold"
                                    />
                                </div>
                            </div>

                            <!-- Row 2: Tanggal Terbit, Kadaluarsa, Status -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Terbit <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_terbit"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Review / Kadaluarsa
                                    </label>
                                    <input
                                        v-model="form.tanggal_kadaluarsa"
                                        type="date"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Status Dokumen <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.status"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold bg-white"
                                    >
                                        <option value="draft">Draft Usulan</option>
                                        <option value="review">Dalam Review</option>
                                        <option value="approved">Approved / Berlaku</option>
                                        <option value="obsolete">Obsolete / Usang</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Deskripsi & Keterangan -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Keterangan & Ruang Lingkup Dokumen
                                </label>
                                <textarea
                                    v-model="form.keterangan"
                                    rows="3"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed"
                                ></textarea>
                            </div>

                            <!-- File Upload (Opsional saat Edit) -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Ganti Berkas Dokumen (Opsional)
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl hover:border-indigo-400 transition bg-slate-50/50">
                                    <div class="space-y-1 text-center">
                                        <i class="bi bi-cloud-arrow-up text-3xl text-slate-400"></i>
                                        <div class="flex text-xs text-slate-600 justify-center">
                                            <label class="relative cursor-pointer bg-white rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none px-2 py-0.5">
                                                <span>Pilih file baru untuk mengganti</span>
                                                <input
                                                    type="file"
                                                    class="sr-only"
                                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                                    @change="handleFileChange"
                                                />
                                            </label>
                                        </div>
                                        <p class="text-[11px] text-slate-400">Biarkan kosong jika tidak ingin mengubah berkas saat ini.</p>
                                        <p v-if="form.file" class="text-xs text-emerald-600 font-bold mt-2">
                                            File baru: {{ form.file.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <Link
                                    href="/dokumen"
                                    class="px-6 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan Dokumen' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Metadata Info (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Metadata Dokumen</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-2">
                            <p><strong>Kode:</strong> <span class="font-mono text-indigo-600 font-bold">{{ dokumen.kode_dokumen || dokumen.kode }}</span></p>
                            <p><strong>Pengunggah:</strong> {{ dokumen.pembuat?.name || '-' }}</p>
                            <p><strong>Dibuat Pada:</strong> {{ dokumen.created_at ? new Date(dokumen.created_at).toLocaleDateString('id-ID') : '-' }}</p>
                            <p><strong>Jumlah Diunduh:</strong> {{ dokumen.download_count || 0 }} kali</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
