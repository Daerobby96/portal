<template>
    <AuthenticatedLayout title="Kategori Aset">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/aset"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kategori Aset</h1>
                        <p class="text-xs text-slate-500 mt-1">Klasifikasi pengelompokan jenis barang dan fasilitas institusi.</p>
                    </div>
                </div>

                <button
                    @click="openCreateModal"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                >
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Kategori</span>
                </button>
            </div>

            <!-- Categories Grid Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="k in kategoris"
                    :key="k.id"
                    class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition space-y-4 group relative"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl text-white shadow-xs"
                                :style="{ backgroundColor: k.color || '#10b981' }"
                            >
                                <i class="bi" :class="k.icon || 'bi-tags-fill'"></i>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] font-black uppercase px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ k.kode }}
                                </span>
                                <h3 class="font-black text-slate-900 text-sm mt-1 group-hover:text-emerald-600 transition">
                                    {{ k.nama }}
                                </h3>
                            </div>
                        </div>

                        <div class="flex items-center gap-1">
                            <button
                                @click="openEditModal(k)"
                                type="button"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                title="Edit"
                            >
                                <i class="bi bi-pencil text-xs"></i>
                            </button>
                            <button
                                @click="confirmDelete(k)"
                                type="button"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                title="Hapus"
                            >
                                <i class="bi bi-trash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <p v-if="k.keterangan" class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                        {{ k.keterangan }}
                    </p>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-semibold">Aset Terdaftar:</span>
                        <Link :href="`/aset?kategori_id=${k.id}`" class="font-black text-emerald-700 hover:underline">
                            {{ k.asets_count }} Barang <i class="bi bi-chevron-right text-[10px]"></i>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Modal Form Tambah / Edit Kategori -->
            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">
                                {{ editingCategory ? 'Edit Kategori Aset' : 'Tambah Kategori Aset' }}
                            </h3>
                            <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Kategori <span class="text-rose-500">*</span></label>
                                <input
                                    v-model="form.kode"
                                    type="text"
                                    required
                                    placeholder="Contoh: ELEKTRONIK"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none uppercase font-mono"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                                <input
                                    v-model="form.nama"
                                    type="text"
                                    required
                                    placeholder="Contoh: Peralatan Komputer & IT"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                                />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Icon (Bootstrap) <span class="text-rose-500">*</span></label>
                                    <input
                                        v-model="form.icon"
                                        type="text"
                                        required
                                        placeholder="bi-laptop"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Warna Aksen</label>
                                    <div class="flex items-center gap-2">
                                        <input
                                            v-model="form.color"
                                            type="color"
                                            class="w-9 h-9 rounded-xl border-0 p-0 cursor-pointer"
                                        />
                                        <input
                                            v-model="form.color"
                                            type="text"
                                            class="w-full px-2.5 py-2 rounded-xl border border-slate-200 text-xs font-mono"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan</label>
                                <textarea
                                    v-model="form.keterangan"
                                    rows="2"
                                    placeholder="Deskripsi singkat jenis barang..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <input
                                    id="is_aktif"
                                    v-model="form.is_aktif"
                                    type="checkbox"
                                    class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300"
                                />
                                <label for="is_aktif" class="text-xs font-bold text-slate-700 cursor-pointer">Status Aktif Digunakan</label>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="submitting"
                                    class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5"
                                >
                                    <i v-if="submitting" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>{{ editingCategory ? 'Simpan Perubahan' : 'Simpan Kategori' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Delete Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Kategori?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Kategori "<span class="font-bold text-slate-800">{{ deleteTarget.nama }}</span>" akan dihapus. Pastikan tidak ada aset yang terkait.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="deleteTarget = null"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm"
                            >
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kategoris: Array,
});

const showModal = ref(false);
const editingCategory = ref(null);
const submitting = ref(false);

const form = reactive({
    kode: '',
    nama: '',
    icon: 'bi-box-seam',
    color: '#10b981',
    keterangan: '',
    is_aktif: true,
});

function openCreateModal() {
    editingCategory.value = null;
    form.kode = '';
    form.nama = '';
    form.icon = 'bi-box-seam';
    form.color = '#10b981';
    form.keterangan = '';
    form.is_aktif = true;
    showModal.value = true;
}

function openEditModal(cat) {
    editingCategory.value = cat;
    form.kode = cat.kode;
    form.nama = cat.nama;
    form.icon = cat.icon || 'bi-box-seam';
    form.color = cat.color || '#10b981';
    form.keterangan = cat.keterangan || '';
    form.is_aktif = cat.is_aktif;
    showModal.value = true;
}

function submitForm() {
    submitting.value = true;
    if (editingCategory.value) {
        router.put(`/kategori-aset/${editingCategory.value.id}`, form, {
            onSuccess: () => {
                showModal.value = false;
            },
            onFinish: () => {
                submitting.value = false;
            },
        });
    } else {
        router.post('/kategori-aset', form, {
            onSuccess: () => {
                showModal.value = false;
            },
            onFinish: () => {
                submitting.value = false;
            },
        });
    }
}

const deleteTarget = ref(null);
function confirmDelete(cat) {
    deleteTarget.value = cat;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/kategori-aset/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}
</script>
