<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kategoris: Array,
});

const modalOpen = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    nama: '',
    kode: '',
    warna: 'primary',
    keterangan: '',
});

const openCreateModal = () => {
    isEditing.value = false;
    editingId.value = null;
    form.reset();
    modalOpen.value = true;
};

const openEditModal = (k) => {
    isEditing.value = true;
    editingId.value = k.id;
    form.nama = k.nama;
    form.kode = k.kode;
    form.warna = k.warna || 'primary';
    form.keterangan = k.keterangan || '';
    modalOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(`/kategori-dokumen/${editingId.value}`, {
            onSuccess: () => { modalOpen.value = false; },
        });
    } else {
        form.post('/kategori-dokumen', {
            onSuccess: () => { modalOpen.value = false; },
        });
    }
};

const deleteKategori = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori dokumen ini?')) {
        router.delete(`/kategori-dokumen/${id}`);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Kategori Dokumen Mutu" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-2.5">
                        <i class="bi bi-tags"></i>
                        <span>Taksonomi Dokumen</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Kategori Dokumen Mutu
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Klasifikasi tipe dokumen mutu (Kebijakan, Manual SPMI, Standar Mutu, Formulir, SOP).
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button
                        @click="openCreateModal"
                        class="px-4 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Kategori</span>
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-6">Kode</th>
                                <th class="py-3.5 px-6">Nama Kategori</th>
                                <th class="py-3.5 px-6">Keterangan</th>
                                <th class="py-3.5 px-6 text-center">Jumlah Dokumen</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="k in kategoris"
                                :key="k.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    {{ k.kode }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ k.nama }}
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    {{ k.keterangan || '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[11px]">
                                        {{ k.dokumens_count || 0 }} Dokumen
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openEditModal(k)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button
                                            @click="deleteKategori(k.id)"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!kategoris || kategoris.length === 0">
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    Belum ada kategori dokumen terdaftar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Create / Edit -->
        <div
            v-if="modalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="modalOpen = false"
        >
            <div class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ isEditing ? 'Edit Kategori Dokumen' : 'Tambah Kategori Dokumen' }}
                    </h3>
                    <button @click="modalOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Kategori <span class="text-rose-500">*</span></label>
                        <input
                            v-model="form.kode"
                            type="text"
                            required
                            placeholder="SOP / MAN / KBJ"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 uppercase font-mono font-bold focus:ring-2 focus:ring-indigo-500"
                        />
                        <p v-if="form.errors.kode" class="text-rose-500 text-[11px] mt-1">{{ form.errors.kode }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input
                            v-model="form.nama"
                            type="text"
                            required
                            placeholder="Contoh: Standar Operasional Prosedur"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan</label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="modalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-xs"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Kategori' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
