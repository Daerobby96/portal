<template>
    <AuthenticatedLayout title="Unit Pengelola Surat">
        <div class="space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-black text-slate-900">Unit Pengelola Surat</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola unit/bagian pengelola naskah dinas</p>
                </div>
                <button @click="openCreate" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold transition shadow-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Unit
                </button>
            </div>

            <!-- Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-if="units.length === 0" class="md:col-span-2 lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
                    <i class="bi bi-buildings text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-sm font-semibold text-slate-500">Belum ada unit pengelola surat</p>
                    <button @click="openCreate" class="mt-3 text-xs font-bold text-amber-600 hover:underline">Tambah unit pertama</button>
                </div>

                <div v-for="u in units" :key="u.id"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 hover:shadow-md transition group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                                :class="u.jenis_institusi === 'yayasan' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700'">
                                <i :class="u.jenis_institusi === 'yayasan' ? 'bi bi-building' : 'bi bi-mortarboard-fill'"></i>
                            </div>
                            <div>
                                <p class="font-black text-slate-900 text-sm">{{ u.nama }}</p>
                                <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ u.kode }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" :class="u.is_active ? 'bg-green-500' : 'bg-slate-300'"></span>
                            <span class="text-[10px] font-semibold" :class="u.is_active ? 'text-green-600' : 'text-slate-400'">
                                {{ u.is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="text-[10px] text-slate-400 space-y-1 mb-3">
                        <div class="flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 rounded-full font-bold uppercase"
                                :class="u.jenis_institusi === 'yayasan' ? 'bg-violet-100 text-violet-600' : 'bg-blue-100 text-blue-600'">
                                {{ u.jenis_institusi === 'yayasan' ? 'Yayasan' : 'Perguruan Tinggi' }}
                            </span>
                            <span class="flex items-center gap-1"><i class="bi bi-file-text text-amber-500"></i>{{ u.total_surat }} surat</span>
                        </div>
                        <p v-if="u.pic_nama" class="flex items-center gap-1">
                            <i class="bi bi-person-fill text-slate-300"></i>{{ u.pic_nama }}{{ u.pic_jabatan ? ` — ${u.pic_jabatan}` : '' }}
                        </p>
                        <p v-if="u.prefix_format" class="flex items-center gap-1 font-mono">
                            <i class="bi bi-hash text-slate-300"></i>{{ u.prefix_format }}
                        </p>
                        <p v-if="u.deskripsi" class="text-slate-400 mt-1 line-clamp-2">{{ u.deskripsi }}</p>
                    </div>

                    <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                        <button @click="openEdit(u)" class="flex-1 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                            <i class="bi bi-pencil mr-1 text-blue-500"></i>Edit
                        </button>
                        <button @click="confirmDelete(u)" :disabled="u.total_surat > 0"
                            class="flex-1 py-1.5 rounded-lg border text-xs font-semibold transition"
                            :class="u.total_surat > 0 ? 'border-slate-100 text-slate-300 cursor-not-allowed' : 'border-slate-200 text-slate-600 hover:border-red-300 hover:bg-red-50 hover:text-red-600'">
                            <i class="bi bi-trash mr-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Create/Edit Modal -->
            <Teleport to="body">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                            <h2 class="font-black text-slate-900 text-base">{{ editTarget ? 'Edit' : 'Tambah' }} Unit Pengelola</h2>
                            <button @click="closeModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 transition">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>
                        <form @submit.prevent="submitModal" class="p-6 space-y-4">
                            <!-- Jenis Institusi -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Jenis Institusi *</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" @click="modalForm.jenis_institusi = 'yayasan'"
                                        class="py-2.5 rounded-xl border-2 text-xs font-bold transition"
                                        :class="modalForm.jenis_institusi === 'yayasan' ? 'border-violet-500 bg-violet-50 text-violet-800' : 'border-slate-200 text-slate-600 hover:border-violet-200'">
                                        <i class="bi bi-building mr-1.5"></i>Yayasan
                                    </button>
                                    <button type="button" @click="modalForm.jenis_institusi = 'perguruan_tinggi'"
                                        class="py-2.5 rounded-xl border-2 text-xs font-bold transition"
                                        :class="modalForm.jenis_institusi === 'perguruan_tinggi' ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-slate-200 text-slate-600 hover:border-blue-200'">
                                        <i class="bi bi-mortarboard-fill mr-1.5"></i>Perguruan Tinggi
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Unit *</label>
                                    <input v-model="modalForm.nama" type="text" required placeholder="Contoh: Biro SDM"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kode Unit *</label>
                                    <input v-model="modalForm.kode" type="text" required placeholder="Contoh: BSDM" maxlength="20"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-amber-500/30 outline-none transition uppercase"
                                        @input="e => modalForm.kode = e.target.value.toUpperCase()" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Format Prefix Nomor Surat</label>
                                <input v-model="modalForm.prefix_format" type="text" placeholder="Contoh: {NO}/{KODE}/{BULAN}/{TAHUN}"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-mono focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                                <p class="text-[10px] text-slate-400 mt-1">Gunakan {NO}, {KODE}, {BULAN}, {TAHUN} sebagai placeholder.</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama PIC</label>
                                    <input v-model="modalForm.pic_nama" type="text" placeholder="Nama penanggung jawab"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan PIC</label>
                                    <input v-model="modalForm.pic_jabatan" type="text" placeholder="Jabatan PIC"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP/NIK PIC</label>
                                <input v-model="modalForm.pic_nip" type="text" placeholder="NIP atau NIK"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                                <textarea v-model="modalForm.deskripsi" rows="2" placeholder="Deskripsi singkat unit..."
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition resize-none"></textarea>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" @click="modalForm.is_active = !modalForm.is_active"
                                    class="relative w-10 h-6 rounded-full transition-colors"
                                    :class="modalForm.is_active ? 'bg-green-500' : 'bg-slate-200'">
                                    <span class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"
                                        :class="modalForm.is_active ? 'translate-x-4' : 'translate-x-0'"></span>
                                </button>
                                <span class="text-xs font-semibold" :class="modalForm.is_active ? 'text-green-700' : 'text-slate-500'">
                                    {{ modalForm.is_active ? 'Unit Aktif' : 'Unit Non-aktif' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" @click="closeModal" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                <button type="submit" :disabled="processing"
                                    class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                                    <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <i v-else class="bi bi-check2-circle"></i>
                                    {{ editTarget ? 'Simpan Perubahan' : 'Tambah Unit' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Delete Confirm Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 mx-auto mb-4">
                            <i class="bi bi-trash text-xl"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center mb-1">Hapus Unit?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5">"{{ deleteTarget.nama }}" akan dihapus permanen.</p>
                        <div class="flex gap-3">
                            <button @click="deleteTarget = null" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                            <form :action="`/unit-pengelola/${deleteTarget.id}`" method="POST" class="flex-1">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" :value="csrf">
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ units: Array });

const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const showModal  = ref(false);
const editTarget = ref(null);
const deleteTarget = ref(null);
const processing = ref(false);

const defaultForm = () => ({
    nama: '', kode: '', jenis_institusi: 'yayasan',
    prefix_format: '', deskripsi: '',
    pic_nama: '', pic_jabatan: '', pic_nip: '',
    is_active: true,
});

const modalForm = reactive(defaultForm());

function openCreate() {
    editTarget.value = null;
    Object.assign(modalForm, defaultForm());
    showModal.value = true;
}

function openEdit(u) {
    editTarget.value = u;
    Object.assign(modalForm, {
        nama: u.nama, kode: u.kode, jenis_institusi: u.jenis_institusi,
        prefix_format: u.prefix_format || '', deskripsi: u.deskripsi || '',
        pic_nama: u.pic_nama || '', pic_jabatan: u.pic_jabatan || '', pic_nip: u.pic_nip || '',
        is_active: u.is_active,
    });
    showModal.value = true;
}

function closeModal() { showModal.value = false; editTarget.value = null; }
function confirmDelete(u) { deleteTarget.value = u; }

function submitModal() {
    processing.value = true;
    if (editTarget.value) {
        router.put(`/unit-pengelola/${editTarget.value.id}`, modalForm, {
            onSuccess: closeModal,
            onFinish: () => { processing.value = false; },
        });
    } else {
        router.post('/unit-pengelola', modalForm, {
            onSuccess: closeModal,
            onFinish: () => { processing.value = false; },
        });
    }
}
</script>
