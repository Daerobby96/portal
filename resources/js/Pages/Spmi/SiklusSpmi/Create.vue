<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    periodes: {
        type: Array,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    nama: '',
    tahun_siklus: new Date().getFullYear(),
    tanggal_mulai: new Date().toISOString().split('T')[0],
    tanggal_selesai: '',
    status: 'persiapan',
    deskripsi: '',
    penanggung_jawab_id: '',
    is_aktif: false,
    periode_ids: [],
});

const submit = () => {
    form.post('/siklus-spmi');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat Siklus Mutu Baru" />

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
                            Siklus Penjaminan Mutu Internal (PPEPP)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Buat Siklus Mutu Baru
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Inisialisasi siklus penjaminan mutu SPMI, tautkan periode semester akademik, dan tentukan target sasaran mutu institusi.
                    </p>
                </div>
            </div>

            <!-- 2-Column Grid Layout (Grid 12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Main Form (8 of 12) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Row 1: Nama & Tahun -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Nama Siklus <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.nama"
                                        type="text"
                                        required
                                        placeholder="Contoh: Siklus Mutu SPMI Tahun 2025/2026"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-900"
                                    />
                                    <p v-if="form.errors.nama" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nama }}</p>
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

                            <!-- Row 2: Rentang Tanggal & Status -->
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
                                        Status Awal <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.status"
                                        class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                                    >
                                        <option value="persiapan">Persiapan</option>
                                        <option value="berjalan">Berjalan</option>
                                        <option value="evaluasi">Evaluasi</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 3: Penanggung Jawab & Status Aktif -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        Penanggung Jawab Mutu (LPM)
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

                            <!-- Periode Akademik Tautan -->
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

                            <!-- Deskripsi / Catatan -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Deskripsi / Sasaran Mutu Siklus
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="4"
                                    placeholder="Tuliskan target sasaran mutu dan ruang lingkup pelaksanaan siklus..."
                                    class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 leading-relaxed"
                                ></textarea>
                            </div>

                            <!-- Submit Actions -->
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
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Siklus Baru' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Sticky Sidebars (4 of 12) -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <!-- Card: 5 Pilar PPEPP -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-arrow-repeat text-indigo-600"></i>
                            <span>5 Pilar Siklus SPMI (PPEPP)</span>
                        </h4>
                        <div class="space-y-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">P1</span>
                                <div>
                                    <strong class="text-slate-900 block">Penetapan Standar</strong>
                                    <span class="text-[11px] text-slate-500">Perumusan standar mutu SN-Dikti.</span>
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">P2</span>
                                <div>
                                    <strong class="text-slate-900 block">Pelaksanaan Standar</strong>
                                    <span class="text-[11px] text-slate-500">Operasional tridharma & monitoring.</span>
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">P3</span>
                                <div>
                                    <strong class="text-slate-900 block">Evaluasi Pelaksanaan</strong>
                                    <span class="text-[11px] text-slate-500">Audit Mutu Internal (AMI) berkala.</span>
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">P4</span>
                                <div>
                                    <strong class="text-slate-900 block">Pengendalian</strong>
                                    <span class="text-[11px] text-slate-500">Rapat Tinjauan Manajemen (RTM).</span>
                                </div>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0">P5</span>
                                <div>
                                    <strong class="text-slate-900 block">Peningkatan Standar</strong>
                                    <span class="text-[11px] text-slate-500">Benchmarking & Kaizen mutu baru.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
