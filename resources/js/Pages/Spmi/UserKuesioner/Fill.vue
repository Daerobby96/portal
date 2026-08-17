<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioner: Object,
});

const initialAnswers = {};
if (props.kuesioner.pertanyaans) {
    props.kuesioner.pertanyaans.forEach((p) => {
        initialAnswers[p.id] = p.tipe === 'likert' ? '4' : '';
    });
}

const form = useForm({
    jawaban: initialAnswers,
});

const submit = () => {
    form.post(`/survei/${props.kuesioner.id}`);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Pengisian Survei: ${kuesioner.judul}`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <a href="/survei" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Survei
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ kuesioner.judul }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ kuesioner.deskripsi || 'Silakan pilih skala penilaian yang paling menggambarkan pengalaman Anda.' }}</p>
            </div>

            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-6">
                    <div
                        v-for="(p, index) in kuesioner.pertanyaans"
                        :key="p.id"
                        class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3"
                    >
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                {{ index + 1 }}
                            </span>
                            <div class="text-xs font-bold text-slate-900 leading-relaxed">
                                {{ p.pertanyaan }}
                            </div>
                        </div>

                        <!-- Likert 1-4 scale -->
                        <div v-if="p.tipe === 'likert'" class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2">
                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                :class="form.jawaban[p.id] === '1' ? 'bg-rose-50 border-rose-400 text-rose-800' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input
                                    type="radio"
                                    :name="`p_${p.id}`"
                                    value="1"
                                    v-model="form.jawaban[p.id]"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>1 - Sangat Kurang</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                :class="form.jawaban[p.id] === '2' ? 'bg-amber-50 border-amber-400 text-amber-800' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input
                                    type="radio"
                                    :name="`p_${p.id}`"
                                    value="2"
                                    v-model="form.jawaban[p.id]"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>2 - Kurang</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                :class="form.jawaban[p.id] === '3' ? 'bg-sky-50 border-sky-400 text-sky-800' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input
                                    type="radio"
                                    :name="`p_${p.id}`"
                                    value="3"
                                    v-model="form.jawaban[p.id]"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>3 - Baik</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold"
                                :class="form.jawaban[p.id] === '4' ? 'bg-emerald-50 border-emerald-400 text-emerald-800' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input
                                    type="radio"
                                    :name="`p_${p.id}`"
                                    value="4"
                                    v-model="form.jawaban[p.id]"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>4 - Sangat Baik</span>
                            </label>
                        </div>

                        <!-- Open text -->
                        <div v-else class="pt-2">
                            <textarea
                                v-model="form.jawaban[p.id]"
                                rows="3"
                                placeholder="Tuliskan jawaban atau saran Anda..."
                                class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/survei"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Mengirim...' : 'Kirim Jawaban Survei' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
