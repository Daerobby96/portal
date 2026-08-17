<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    siklusSpmi: Object,
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
    nama: props.siklusSpmi.nama,
    tahun_siklus: props.siklusSpmi.tahun_siklus,
    tanggal_mulai: props.siklusSpmi.tanggal_mulai,
    tanggal_selesai: props.siklusSpmi.tanggal_selesai || '',
    status: props.siklusSpmi.status,
    deskripsi: props.siklusSpmi.deskripsi || '',
    penanggung_jawab_id: props.siklusSpmi.penanggung_jawab_id || '',
    is_aktif: Boolean(props.siklusSpmi.is_aktif),
    periode_ids: props.siklusSpmi.periodes ? props.siklusSpmi.periodes.map(p => p.id) : [],
});

const submit = () => {
    form.put(`/siklus-spmi/${props.siklusSpmi.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Siklus: ${siklusSpmi.nama}`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div>
                <a :href="`/siklus-spmi/${siklusSpmi.id}`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Detail Siklus
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Siklus Mutu</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui konfigurasi dan periode yang ditautkan ke siklus ini.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Row 1: Nama & Tahun -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nama Siklus <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tanggal Mulai <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.tanggal_mulai"
                                type="date"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tanggal Selesai
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

                    <!-- Row 3: Penanggung Jawab & Status Aktif -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Penanggung Jawab Mutu
                            </label>
                            <select
                                v-model="form.penanggung_jawab_id"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="">-- Pilih Penanggung Jawab --</option>
                                <option v-for="u in users" :key="u.id" :value="u.id">
                                    {{ u.name }} ({{ u.role || 'Staff' }})
                                </option>
                            </select>
                        </div>

                        <div class="flex items-center sm:pt-6">
                            <label class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100 cursor-pointer w-full hover:bg-slate-100 transition">
                                <input
                                    v-model="form.is_aktif"
                                    type="checkbox"
                                    class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                />
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Jadikan Siklus Utama Aktif</span>
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
                                class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-slate-200 text-xs font-medium cursor-pointer hover:border-indigo-300 transition"
                            >
                                <input
                                    v-model="form.periode_ids"
                                    type="checkbox"
                                    :value="p.id"
                                    class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>{{ p.nama }} ({{ p.tahun }} - {{ p.semester }})</span>
                            </label>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Deskripsi / Sasaran Mutu
                        </label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="3"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            :href="`/siklus-spmi/${siklusSpmi.id}`"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Perbarui Siklus' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
