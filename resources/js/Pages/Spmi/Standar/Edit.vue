<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    standar: Object,
    bidangOptions: Object,
    jenisOptions: Object,
});

const form = useForm({
    kode: props.standar.kode,
    nama: props.standar.nama,
    deskripsi: props.standar.deskripsi || '',
    bidang: props.standar.bidang,
    jenis: props.standar.jenis,
    nomor: props.standar.nomor || '',
    is_aktif: Boolean(props.standar.is_aktif),
});

const submit = () => {
    form.put(`/standar/${props.standar.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Edit Standar: ${standar.kode}`" />

        <div class="max-w-3xl mx-auto space-y-6">
            <div>
                <a :href="`/standar/${standar.id}`" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Detail Standar
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Standar Mutu</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi dan klasifikasi standar SPMI.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Kode Standar <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.kode"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 uppercase font-mono font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            />
                            <p v-if="form.errors.kode" class="text-rose-500 text-[11px] mt-1">{{ form.errors.kode }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nama Standar <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Bidang Standar <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.bidang"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none capitalize"
                            >
                                <option v-for="(label, key) in bidangOptions" :key="key" :value="key">{{ label }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Jenis Standar <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.jenis"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none capitalize"
                            >
                                <option v-for="(label, key) in jenisOptions" :key="key" :value="key">{{ label }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nomor Urut
                            </label>
                            <input
                                v-model="form.nomor"
                                type="number"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Deskripsi / Ruang Lingkup Standar
                        </label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="3"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            :href="`/standar/${standar.id}`"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Perbarui Standar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
