<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    periodes: Array,
});

const form = useForm({
    judul: '',
    periode_id: props.periodes?.[0]?.id || '',
    target_role: 'all',
    deskripsi: '',
    is_public: false,
    status: 'aktif',
});

const submit = () => {
    form.post('/kuesioner');
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat Kuesioner Baru" />

        <div class="max-w-3xl mx-auto space-y-6">
            <div>
                <a href="/kuesioner" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Kuesioner
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Kuesioner Baru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Definisikan survei mutu kepuasan sivitas akademika.</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Judul Kuesioner <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.judul"
                            type="text"
                            required
                            placeholder="Contoh: Survei Kepuasan Mahasiswa Terhadap Layanan Akademik"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Periode Mutu <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.periode_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="p in periodes" :key="p.id" :value="p.id">{{ p.nama }} ({{ p.tahun }})</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Target Responden <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.target_role"
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 capitalize"
                            >
                                <option value="all">Semua Sivitas</option>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="alumni">Alumni / Tracer Study</option>
                                <option value="mitra">Mitra / Pengguna Lulusan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Deskripsi / Petunjuk Pengisian
                        </label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="3"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/kuesioner"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Kuesioner' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
