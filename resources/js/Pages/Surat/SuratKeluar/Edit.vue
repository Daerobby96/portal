<template>
    <AuthenticatedLayout title="Edit Surat Keluar">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="flex items-center gap-3">
                <a :href="`/surat-keluar/${suratKeluar.id}`" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Surat Keluar</h1>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ suratKeluar.nomor_surat ?? '— Nomor akan digenerate —' }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Perihal & Tujuan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Perihal *</label>
                            <input v-model="form.perihal" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tujuan *</label>
                            <input v-model="form.tujuan" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Surat *</label>
                            <input v-model="form.tanggal_surat" type="date" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Isi Surat</label>
                            <textarea v-model="form.isi_surat" rows="5" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Penandatangan & Status</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Penandatangan *</label>
                            <input v-model="form.penandatangan_nama" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan *</label>
                            <input v-model="form.penandatangan_jabatan" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">NIP</label>
                            <input v-model="form.penandatangan_nip" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status</label>
                        <select v-model="form.status" class="w-full md:w-60 px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                            <option value="draft">Simpan sebagai Draft</option>
                            <option value="pending">Ajukan Persetujuan</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pb-4">
                    <a :href="`/surat-keluar/${suratKeluar.id}`" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" :disabled="processing" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ suratKeluar: Object, jenisSurat: Array, unitPengelola: Array });

const form = reactive({ ...props.suratKeluar });
const processing = ref(false);
const errors = ref({});

function submit() {
    processing.value = true;
    router.put(`/surat-keluar/${props.suratKeluar.id}`, form, {
        onError: e => { errors.value = e; processing.value = false; },
        onFinish: () => { processing.value = false; },
    });
}
</script>
