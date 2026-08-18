<template>
    <AuthenticatedLayout :title="`Detail - ${kerjasama.nama_mitra}`">
        <div class="space-y-6">
            <!-- Top Breadcrumb & Actions -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <Link
                        href="/kerjasama"
                        class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500"
                    >
                        <i class="bi bi-arrow-left text-sm"></i>
                    </Link>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="tingkatBadgeClass(kerjasama.tingkat)">
                                {{ kerjasama.tingkat }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(kerjasama.status)">
                                {{ kerjasama.status }}
                            </span>
                        </div>
                        <h1 class="text-xl font-black text-slate-900 mt-1">{{ kerjasama.nama_mitra }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        v-if="kerjasama.dokumen_mou"
                        :href="kerjasama.dokumen_mou"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition border border-blue-200"
                    >
                        <i class="bi bi-file-earmark-pdf-fill text-blue-600"></i>
                        <span>Naskah MoU (PDF)</span>
                    </a>

                    <Link
                        :href="`/kerjasama/${kerjasama.id}/edit`"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition border border-slate-200"
                    >
                        <i class="bi bi-pencil-fill text-slate-500"></i>
                        <span>Edit</span>
                    </Link>

                    <button
                        @click="showDeleteModal = true"
                        type="button"
                        class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200 cursor-pointer"
                        title="Hapus Data"
                    >
                        <i class="bi bi-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Left: Informasi Detail Kerjasama (Span 2) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs space-y-5">
                        <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="font-black text-slate-900 text-sm">Informasi Kerjasama</h2>
                            <span v-if="kerjasama.jenis_dokumen" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-blue-50 text-blue-700 border border-blue-200/60">
                                {{ kerjasama.jenis_dokumen }}
                            </span>
                        </div>

                        <!-- Judul Kegiatan -->
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Judul / Topik Kegiatan</span>
                            <p class="text-sm font-black text-slate-800 leading-snug">{{ kerjasama.judul_kerjasama }}</p>
                        </div>

                        <!-- Data List -->
                        <div class="divide-y divide-slate-100 text-xs">
                            <div class="py-2.5 flex justify-between gap-2">
                                <span class="text-slate-400 font-medium">Jenis Mitra</span>
                                <span class="font-bold text-slate-800 text-right">{{ kerjasama.jenis_mitra }}</span>
                            </div>

                            <div class="py-2.5 flex justify-between gap-2">
                                <span class="text-slate-400 font-medium">Tingkat</span>
                                <span class="font-bold text-slate-800 text-right">{{ kerjasama.tingkat }}</span>
                            </div>

                            <div class="py-2.5 flex justify-between gap-2">
                                <span class="text-slate-400 font-medium">Pengusul</span>
                                <span class="font-bold text-slate-800 text-right">{{ kerjasama.prodi_nama }}</span>
                            </div>

                            <div class="py-2.5 flex justify-between gap-2">
                                <span class="text-slate-400 font-medium">Masa Berlaku</span>
                                <span class="font-semibold text-slate-800 text-right">
                                    {{ kerjasama.tanggal_mulai }} — {{ kerjasama.tanggal_selesai }}
                                </span>
                            </div>

                            <div class="py-2.5 flex justify-between gap-2">
                                <span class="text-slate-400 font-medium">Status</span>
                                <span class="font-bold" :class="kerjasama.status === 'Aktif' ? 'text-emerald-700' : 'text-slate-700'">
                                    {{ kerjasama.status }}
                                </span>
                            </div>
                        </div>

                        <!-- Expiring Warning Pill -->
                        <div v-if="kerjasama.is_expiring" class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                            <div class="flex items-center gap-2 font-bold">
                                <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
                                <span>Perhatian: Kerjasama Akan Berakhir</span>
                            </div>
                            <p class="text-[11px] text-rose-700 mt-1">Masa berlaku dokumen ini tersisa kurang dari 60 hari. Segera lakukan peninjauan perpanjangan MoU.</p>
                        </div>

                        <!-- Keterangan -->
                        <div v-if="kerjasama.keterangan" class="pt-3 border-t border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400 block mb-1">Catatan / Keterangan</span>
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50 p-3 rounded-xl border border-slate-100">
                                {{ kerjasama.keterangan }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: Evaluasi Mitra & Kepuasan (Span 3) -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <div>
                                    <h2 class="font-black text-slate-900 text-sm">Evaluasi Kemitraan & Kepuasan</h2>
                                    <p class="text-[11px] text-slate-400">Penilaian berkala efektivitas kerjasama institusi dengan mitra.</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Average Rating Pill -->
                                <div v-if="avgNilai > 0" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-black">
                                    <i class="bi bi-star-fill text-amber-500"></i>
                                    <span>{{ avgNilai }} / 5.0</span>
                                </div>

                                <button
                                    @click="showAddEvaluasiModal = true"
                                    type="button"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Tambah Evaluasi</span>
                                </button>
                            </div>
                        </div>

                        <!-- Evaluasi List Timeline -->
                        <div v-if="evaluasis.length === 0" class="text-center py-12 text-slate-400">
                            <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-600">Belum ada evaluasi kegiatan untuk mitra ini</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Klik tombol "Tambah Evaluasi" di atas untuk mencatat penilaian kepuasan.</p>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="ev in evaluasis"
                                :key="ev.id"
                                class="p-4 rounded-2xl border border-slate-100 hover:border-pink-200 hover:bg-pink-50/20 transition space-y-2 group"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2">
                                        <!-- Star Rating Indicator -->
                                        <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                                            <i v-for="i in 5" :key="i" :class="i <= ev.nilai ? 'bi bi-star-fill' : 'bi bi-star text-slate-200'"></i>
                                        </div>
                                        <span class="text-xs font-black text-slate-800">Skor: {{ ev.nilai }}/5</span>
                                    </div>

                                    <button
                                        @click="deleteEvaluasi(ev.id)"
                                        type="button"
                                        class="p-1 rounded-lg text-slate-300 hover:text-rose-600 transition cursor-pointer"
                                        title="Hapus Evaluasi"
                                    >
                                        <i class="bi bi-trash text-xs"></i>
                                    </button>
                                </div>

                                <p v-if="ev.catatan" class="text-xs text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    {{ ev.catatan }}
                                </p>

                                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1">
                                    <span>Evaluator: <b>{{ ev.evaluator_name }}</b></span>
                                    <span>Tanggal: {{ ev.tanggal_evaluasi }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Evaluasi -->
            <Teleport to="body">
                <div v-if="showAddEvaluasiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Tambah Evaluasi Mitra</h3>
                            <button @click="showAddEvaluasiModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitEvaluasi" class="space-y-4">
                            <!-- Star Rating Picker -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Nilai Kepuasan & Kinerja Mitra <span class="text-rose-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <button
                                        v-for="s in 5"
                                        :key="s"
                                        type="button"
                                        @click="evaluasiForm.nilai = s"
                                        class="text-2xl transition hover:scale-125 cursor-pointer"
                                        :class="s <= evaluasiForm.nilai ? 'text-amber-400' : 'text-slate-200'"
                                    >
                                        <i class="bi bi-star-fill"></i>
                                    </button>
                                    <span class="text-xs font-extrabold text-slate-700 ml-2">({{ evaluasiForm.nilai }} dari 5 Bintang)</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Evaluasi <span class="text-rose-500">*</span></label>
                                <input
                                    v-model="evaluasiForm.tanggal_evaluasi"
                                    type="date"
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan & Ulasan Evaluasi</label>
                                <textarea
                                    v-model="evaluasiForm.catatan"
                                    rows="3"
                                    placeholder="Jelaskan capaian implementasi kegiatan, kepuasan terhadap mitra, dan rekomendasi perpanjangan..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-pink-500/30 outline-none resize-none leading-relaxed"
                                ></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showAddEvaluasiModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="submittingEvaluasi"
                                    class="px-5 py-2 rounded-xl bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5"
                                >
                                    <i v-if="submittingEvaluasi" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Simpan Evaluasi</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Delete Kerjasama Modal -->
            <Teleport to="body">
                <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Kerjasama?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data kerjasama dengan "<span class="font-bold text-slate-800">{{ kerjasama.nama_mitra }}</span>" akan dihapus permanen.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="showDeleteModal = false"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDeleteKerjasama"
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
    kerjasama: Object,
    evaluasis: Array,
    avgNilai: Number,
});

const today = new Date().toISOString().split('T')[0];

const showAddEvaluasiModal = ref(false);
const submittingEvaluasi = ref(false);
const evaluasiForm = reactive({
    nilai: 5,
    tanggal_evaluasi: today,
    catatan: '',
});

function submitEvaluasi() {
    submittingEvaluasi.value = true;
    router.post(`/kerjasama/${props.kerjasama.id}/evaluasi`, evaluasiForm, {
        preserveScroll: true,
        onSuccess: () => {
            showAddEvaluasiModal.value = false;
            evaluasiForm.nilai = 5;
            evaluasiForm.catatan = '';
        },
        onFinish: () => {
            submittingEvaluasi.value = false;
        },
    });
}

function deleteEvaluasi(evaluasiId) {
    if (confirm('Hapus hasil evaluasi ini?')) {
        router.delete(`/kerjasama/${props.kerjasama.id}/evaluasi/${evaluasiId}`, {
            preserveScroll: true,
        });
    }
}

const showDeleteModal = ref(false);
function proceedDeleteKerjasama() {
    router.delete(`/kerjasama/${props.kerjasama.id}`);
}

function tingkatBadgeClass(tingkat) {
    const map = {
        'Internasional': 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'Nasional': 'bg-blue-50 text-blue-700 border border-blue-200/60',
        'Lokal': 'bg-slate-100 text-slate-600 border border-slate-200',
    };
    return map[tingkat] || 'bg-slate-100 text-slate-600';
}

function statusBadgeClass(status) {
    const map = {
        'Aktif': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'Draft': 'bg-slate-100 text-slate-600 border border-slate-200',
        'Selesai': 'bg-blue-50 text-blue-700 border border-blue-200',
        'Kedaluwarsa': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}
</script>
