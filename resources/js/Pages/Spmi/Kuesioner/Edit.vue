<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioner: Object,
    periodes: Array,
});

const configForm = useForm({
    judul: props.kuesioner.judul,
    status: props.kuesioner.status,
    is_public: Boolean(props.kuesioner.is_public),
    target_role: props.kuesioner.target_role || 'all',
});

const addQuestionModal = ref(false);
const questionForm = useForm({
    pertanyaan: '',
    tipe: 'likert',
    kategori: '',
});

const submitConfig = () => {
    configForm.put(`/kuesioner/${props.kuesioner.id}`);
};

const submitAddQuestion = () => {
    questionForm.post(`/kuesioner/${props.kuesioner.id}/add-question`, {
        onSuccess: () => {
            questionForm.reset();
            addQuestionModal.value = false;
        },
    });
};

const deleteQuestion = (id) => {
    if (confirm('Hapus butir pertanyaan ini?')) {
        router.delete(`/kuesioner-pertanyaan/${id}`);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Pengaturan: ${kuesioner.judul}`" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <a href="/kuesioner" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Kuesioner
                    </a>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Konfigurasi & Kelola Butir Kuesioner</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Atur status publikasi dan kelola bank butir instrumen survei.</p>
                </div>

                <a
                    :href="`/kuesioner/${kuesioner.id}`"
                    class="px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition flex items-center gap-1.5"
                >
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Lihat Hasil & Analisis</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Pengaturan Utama Form -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h3 class="text-sm font-bold text-slate-900">Pengaturan Kuesioner</h3>

                        <form @submit.prevent="submitConfig" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Kuesioner</label>
                                <input
                                    v-model="configForm.judul"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-semibold"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Publikasi</label>
                                <select
                                    v-model="configForm.status"
                                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-semibold"
                                >
                                    <option value="draft">Draft (Sembunyikan)</option>
                                    <option value="aktif">Aktif (Bisa Diisi)</option>
                                    <option value="selesai">Selesai (Tutup)</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <input
                                    id="is_public"
                                    v-model="configForm.is_public"
                                    type="checkbox"
                                    class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500"
                                />
                                <label for="is_public" class="text-xs font-bold text-slate-700 cursor-pointer">
                                    Akses Publik (Tanpa Login)
                                </label>
                            </div>

                            <button
                                type="submit"
                                :disabled="configForm.processing"
                                class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-xs transition"
                            >
                                {{ configForm.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </button>
                        </form>
                    </div>

                    <!-- Respondent Summary Box -->
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white space-y-1">
                        <p class="text-3xl font-black">{{ kuesioner.jawabans?.length || 0 }}</p>
                        <p class="text-xs text-indigo-200">Responden Mengisi Saat Ini</p>
                    </div>
                </div>

                <!-- Right: Question Builder -->
                <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900">
                            Daftar Pertanyaan ({{ kuesioner.pertanyaans?.length || 0 }})
                        </h3>
                        <button
                            @click="addQuestionModal = true"
                            class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Butir Pertanyaan</span>
                        </button>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <div
                            v-for="(q, idx) in kuesioner.pertanyaans"
                            :key="q.id"
                            class="py-3.5 flex items-start justify-between gap-3"
                        >
                            <div class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ idx + 1 }}
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-slate-900">{{ q.pertanyaan }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase" :class="q.tipe === 'likert' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-700'">
                                        {{ q.tipe }}
                                    </span>
                                </div>
                            </div>

                            <button
                                @click="deleteQuestion(q.id)"
                                class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                title="Hapus Pertanyaan"
                            >
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <div v-if="!kuesioner.pertanyaans || kuesioner.pertanyaans.length === 0" class="py-12 text-center text-xs text-slate-400">
                            Belum ada pertanyaan. Silakan klik tombol "Tambah Butir Pertanyaan".
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Pertanyaan -->
        <div
            v-if="addQuestionModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            @click.self="addQuestionModal = false"
        >
            <div class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Pertanyaan Baru</h3>
                    <button @click="addQuestionModal = false" class="p-1 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitAddQuestion" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Isi Butir Pertanyaan <span class="text-rose-500">*</span></label>
                        <textarea
                            v-model="questionForm.pertanyaan"
                            rows="3"
                            required
                            placeholder="Contoh: Bagaimana kepuasan Anda terhadap kecepatan respon layanan..."
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Pertanyaan</label>
                        <select
                            v-model="questionForm.tipe"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="likert">Skala Likert (1-4 Otomatis)</option>
                            <option value="text">Teks Bebas / Esai</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="addQuestionModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="questionForm.processing"
                            class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-xs"
                        >
                            {{ questionForm.processing ? 'Menyimpan...' : 'Simpan Pertanyaan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
