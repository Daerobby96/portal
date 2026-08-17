<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    siklus: Object,
    periodes: {
        type: Array,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
    selectedPeriodes: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    nama: props.siklus.nama,
    tahun_siklus: props.siklus.tahun_siklus,
    tanggal_mulai: props.siklus.tanggal_mulai,
    tanggal_selesai: props.siklus.tanggal_selesai || '',
    status: props.siklus.status,
    deskripsi: props.siklus.deskripsi || '',
    penanggung_jawab_id: props.siklus.penanggung_jawab_id || '',
    is_aktif: Boolean(props.siklus.is_aktif),
    periode_ids: props.selectedPeriodes || [],
});

const submit = () => {
    form.put(`/siklus-spmi/${props.siklus.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Siklus: ${siklus.nama}`" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="/siklus-spmi" class="text-xs font-semibold text-indigo-300 hover:text-white flex items-center gap-1.5 mb-2.5 transition">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Siklus
                    </a>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                            Siklus SPMI Tahun {{ siklus.tahun_siklus }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/20 text-white">
                            {{ siklus.status }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Edit Siklus Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Perbarui konfigurasi siklus mutu, rentang pelaksanaan, penanggung jawab, dan tautan periode akademik.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nama Siklus <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tahun Siklus <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tahun_siklus"
                                        type="number"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Mulai <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.tanggal_mulai"
                                        type="date"
                                        required
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Tanggal Selesai (Target)
                                    </label>
                                    <input
                                        v-model="form.tanggal_selesai"
                                        type="date"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Status Siklus <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.status"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option value="persiapan">Persiapan</option>
                                        <option value="berjalan">Berjalan</option>
                                        <option value="evaluasi">Evaluasi</option>
                                        <option value="ditutup">Ditutup</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Penanggung Jawab Mutu
                                    </label>
                                    <select
                                        v-model="form.penanggung_jawab_id"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option value="">-- Pilih Penanggung Jawab --</option>
                                        <option v-for="u in users" :key="u.id" :value="u.id">
                                            {{ u.name }} ({{ u.role || 'Staff' }})
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-center sm:pt-6">
                                    <label class="flex items-center gap-3 p-3 rounded-2xl bg-indigo-50/60 border border-indigo-100 cursor-pointer w-full hover:bg-indigo-50 transition">
                                        <input
                                            v-model="form.is_aktif"
                                            type="checkbox"
                                            class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">Jadikan Siklus Utama Aktif</span>
                                            <span class="text-[10px] text-slate-500">Siklus ini akan menjadi fokus monitoring SPMI saat ini</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Tautkan Periode Akademik ke Siklus Ini
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 rounded-2xl bg-slate-50 border border-slate-200">
                                    <label
                                        v-for="p in periodes"
                                        :key="p.id"
                                        class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-slate-200 text-xs font-medium cursor-pointer hover:border-indigo-300 transition"
                                    >
                                        <input
                                            v-model="form.periode_ids"
                                            type="checkbox"
                                            :value="p.id"
                                            class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="font-semibold text-slate-800">{{ p.nama }} ({{ p.tahun }} - {{ p.semester }})</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Deskripsi / Sasaran Mutu Siklus
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="4"
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <a
                                    href="/siklus-spmi"
                                    class="px-6 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition disabled:opacity-50 cursor-pointer"
                                >
                                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Siklus Mutu' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Status Siklus</h4>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-2">
                            <p><strong>Status Saat Ini:</strong> <span class="capitalize font-bold text-indigo-600">{{ siklus.status }}</span></p>
                            <p><strong>Fokus Aktif:</strong> {{ siklus.is_aktif ? 'Ya (Siklus Aktif)' : 'Tidak' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
