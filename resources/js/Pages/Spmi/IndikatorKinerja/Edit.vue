<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    indikator: Object,
    indikatorKinerja: Object,
    standars: Array,
    tipeOptions: Object,
    unitKerjas: {
        type: Array,
        default: () => [],
    },
    prodis: {
        type: Array,
        default: () => [],
    },
});

const targetIndikator = computed(() => props.indikator || props.indikatorKinerja || {});
const ind = targetIndikator.value;

const form = useForm({
    standar_id: ind.standar_id || '',
    kode: ind.kode || '',
    nama: ind.nama || '',
    tipe: ind.tipe || 'IKU',
    unit_pengukuran: ind.unit_pengukuran || '%',
    target_nilai: ind.target_nilai || '100',
    target_deskripsi: ind.target_deskripsi || '',
    unit_kerja: ind.unit_kerja || 'Semua Program Studi',
    bobot: ind.bobot || 1.0,
    sumber: ind.sumber || '',
    is_aktif: !!ind.is_aktif,
});

const standarOptions = computed(() => {
    return (props.standars || []).map((s) => ({
        value: s.id,
        label: `[${s.kode}] ${s.nama}`,
        subtext: s.bidang || s.jenis || 'Standar Mutu',
        badge: s.kode,
    }));
});

const unitKerjaOptions = computed(() => {
    const list = [
        { value: 'Semua Program Studi', label: 'Semua Program Studi', subtext: 'Seluruh Program Studi', badge: 'PRODI' },
    ];

    (props.unitKerjas || []).forEach((u) => {
        list.push({
            value: u.nama,
            label: u.nama,
            subtext: `Unit Kerja / ${u.tipe || 'Lembaga'} (${u.kode})`,
            badge: u.kode,
        });
    });

    (props.prodis || []).forEach((p) => {
        list.push({
            value: `Program Studi ${p.nama}`,
            label: `Program Studi ${p.nama}`,
            subtext: `Prodi Kampus (Kode: ${p.kode})`,
            badge: 'PRODI',
        });
    });

    return list;
});

const submit = () => {
    form.put(`/indikator-kinerja/${targetIndikator.value.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Indikator: ${targetIndikator.kode || ''} - ${targetIndikator.nama || ''}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <Link href="/indikator-kinerja" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Daftar Indikator</span>
                    </Link>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            {{ targetIndikator.kode }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/20 text-white">
                            {{ targetIndikator.tipe }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Indikator Kinerja Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Perbarui target capaian, satuan pengukuran, atau standar acuan mutu institusi.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Kode Indikator <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.kode"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 uppercase font-mono font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    />
                                    <p v-if="form.errors.kode" class="text-rose-500 text-[11px] mt-1">{{ form.errors.kode }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Nama / Pernyataan Indikator <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.nama" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Standar Mutu Acuan <span class="text-rose-500">*</span>
                                    </label>
                                    <SearchableSelect
                                        v-model="form.standar_id"
                                        :options="standarOptions"
                                        placeholder="Pilih standar acuan..."
                                        search-placeholder="Ketik kode / nama standar..."
                                    />
                                    <p v-if="form.errors.standar_id" class="text-rose-500 text-[11px] mt-1">{{ form.errors.standar_id }}</p>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tipe Indikator <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.tipe"
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold bg-white"
                                    >
                                        <option value="IKU">Indikator Kinerja Utama (IKU)</option>
                                        <option value="IKT">Indikator Kinerja Tambahan (IKT)</option>
                                        <option value="custom">Custom / Spesifik</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Target Nilai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.target_nilai"
                                        type="number"
                                        step="0.01"
                                        required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-slate-900 font-mono"
                                    />
                                    <p v-if="form.errors.target_nilai" class="text-rose-500 text-[11px] mt-1">{{ form.errors.target_nilai }}</p>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Satuan Ukuran <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.unit_pengukuran"
                                        type="text"
                                        required
                                        placeholder="%, Orang, Skor, dsb."
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold"
                                    />
                                    <p v-if="form.errors.unit_pengukuran" class="text-rose-500 text-[11px] mt-1">{{ form.errors.unit_pengukuran }}</p>
                                </div>

                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Unit Penanggung Jawab <span class="text-rose-500">*</span>
                                    </label>
                                    <SearchableSelect
                                        v-model="form.unit_kerja"
                                        :options="unitKerjaOptions"
                                        :allow-custom="true"
                                        placeholder="Pilih unit kerja penanggung jawab..."
                                        search-placeholder="Cari unit atau ketik manual..."
                                    />
                                    <p v-if="form.errors.unit_kerja" class="text-rose-500 text-[11px] mt-1">{{ form.errors.unit_kerja }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Deskripsi Target / Formula Pengukuran
                                </label>
                                <textarea
                                    v-model="form.target_deskripsi"
                                    rows="3"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <Link
                                    href="/indikator-kinerja"
                                    class="px-6 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Indikator' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Metadata Card (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Metadata Indikator</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 space-y-2">
                            <p><strong>Kode:</strong> <span class="font-mono text-indigo-600 font-bold">{{ targetIndikator.kode }}</span></p>
                            <p><strong>Standar:</strong> {{ targetIndikator.standar?.nama || '-' }}</p>
                            <p><strong>Tipe:</strong> {{ targetIndikator.tipe }}</p>
                            <p><strong>Dibuat Pada:</strong> {{ targetIndikator.created_at ? new Date(targetIndikator.created_at).toLocaleDateString('id-ID') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
