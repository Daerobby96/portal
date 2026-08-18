<template>
    <AuthenticatedLayout :title="`Edit - ${rapat.judul}`">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3">
                <Link
                    :href="`/rapat/${rapat.id}`"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                >
                    <i class="bi bi-arrow-left text-sm"></i>
                </Link>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Edit Rapat</h1>
                    <p class="text-xs text-slate-400 mt-0.5">{{ rapat.judul }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section 1: Informasi Utama -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Informasi & Klasifikasi</h2>
                            <p class="text-[11px] text-slate-400">Ubah judul, kategori, dan periode akademik rapat.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul / Topik Rapat <span class="text-rose-500">*</span></label>
                        <input
                            v-model="form.judul"
                            type="text"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Rapat <span class="text-rose-500">*</span></label>
                            <select
                                v-model="form.jenis"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition bg-white"
                            >
                                <option v-for="(lbl, key) in jenisOptions" :key="key" :value="key">
                                    {{ key }} — {{ lbl }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Periode Akademik <span class="text-rose-500">*</span></label>
                            <select
                                v-model="form.periode_id"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition bg-white"
                            >
                                <option v-for="p in periodes" :key="p.id" :value="p.id">
                                    {{ p.nama }} {{ p.is_aktif ? '(Aktif ★)' : '' }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Jadwal & Tempat -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">2</div>
                        <div>
                            <h2 class="font-black text-slate-900 text-sm">Waktu & Tempat Pelaksanaan</h2>
                            <p class="text-[11px] text-slate-400">Ubah jadwal pelaksanaan rapat.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal <span class="text-rose-500">*</span></label>
                            <input
                                v-model="form.tanggal"
                                type="date"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Waktu Mulai <span class="text-rose-500">*</span></label>
                            <input
                                v-model="form.waktu_mulai"
                                type="time"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Waktu Selesai <span class="text-rose-500">*</span></label>
                            <input
                                v-model="form.waktu_selesai"
                                type="time"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tempat / Ruangan / Link Online <span class="text-rose-500">*</span></label>
                        <input
                            v-model="form.tempat"
                            type="text"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Pengantar Rapat</label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="4"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none transition resize-none leading-relaxed"
                        ></textarea>
                    </div>
                </div>

                <!-- Section 3: RTM Specific Inputs (If RTM) -->
                <div v-if="form.jenis === 'RTM'" class="bg-indigo-50/50 border border-indigo-200/80 rounded-3xl p-6 sm:p-7 space-y-6">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-indigo-100">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">★</div>
                        <div>
                            <h2 class="font-black text-indigo-950 text-sm">Konsideran RTM (Rapat Tinjauan Manajemen)</h2>
                            <p class="text-[11px] text-indigo-600">Input & Output Standar SPMI SN-Dikti untuk tinjauan mutu institusi.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-indigo-900 uppercase tracking-wider">A. Masukan Tinjauan (Input)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Hasil Audit Internal</label>
                                <textarea v-model="form.input_audit_internal" rows="2" placeholder="Temuan AMI & status kepatuhan..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Umpan Balik / Survei</label>
                                <textarea v-model="form.input_umpan_balik" rows="2" placeholder="Hasil kepuasan mahasiswa & dosen..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Kinerja Proses & Kesesuaian</label>
                                <textarea v-model="form.input_kinerja_proses" rows="2" placeholder="Capaian IKU dan standar mutu..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Status Tindakan RTM Lalu</label>
                                <textarea v-model="form.input_status_tindakan" rows="2" placeholder="Evaluasi tindak lanjut periode sebelumnya..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                        </div>

                        <h3 class="text-xs font-black text-indigo-900 uppercase tracking-wider pt-2">B. Hasil Tinjauan (Output)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Keefektifan Sistem Mutu</label>
                                <textarea v-model="form.output_keefektifan" rows="2" placeholder="Penilaian efektivitas siklus PPEPP..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Rencana Perbaikan Mutu</label>
                                <textarea v-model="form.output_perbaikan" rows="2" placeholder="Rencana korektif dan preventif..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Kebutuhan Sumber Daya</label>
                                <textarea v-model="form.output_sumber_daya" rows="2" placeholder="Alokasi anggaran, SDM, dan sarpras pendukung..." class="w-full p-3 rounded-xl border border-indigo-200/80 text-xs bg-white focus:ring-2 focus:ring-indigo-500/30 outline-none resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link
                        :href="`/rapat/${rapat.id}`"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm hover:shadow-md disabled:opacity-50 inline-flex items-center gap-2 cursor-pointer"
                    >
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-check2-circle"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rapat: Object,
    periodes: Array,
    jenisOptions: Object,
});

const form = reactive({
    judul: props.rapat.judul || '',
    jenis: props.rapat.jenis || 'RTM',
    periode_id: props.rapat.periode_id || '',
    tanggal: props.rapat.tanggal || '',
    waktu_mulai: props.rapat.waktu_mulai || '',
    waktu_selesai: props.rapat.waktu_selesai || '',
    tempat: props.rapat.tempat || '',
    deskripsi: props.rapat.deskripsi || '',
    // RTM
    input_audit_internal: props.rapat.input_audit_internal || '',
    input_umpan_balik: props.rapat.input_umpan_balik || '',
    input_kinerja_proses: props.rapat.input_kinerja_proses || '',
    input_status_tindakan: props.rapat.input_status_tindakan || '',
    input_perubahan_sistem: props.rapat.input_perubahan_sistem || '',
    input_rekomendasi: props.rapat.input_rekomendasi || '',
    output_keefektifan: props.rapat.output_keefektifan || '',
    output_perbaikan: props.rapat.output_perbaikan || '',
    output_sumber_daya: props.rapat.output_sumber_daya || '',
});

const processing = ref(false);
const errors = ref({});

function submit() {
    processing.value = true;
    router.put(`/rapat/${props.rapat.id}`, form, {
        onError: (err) => {
            errors.value = err;
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        },
    });
}
</script>
