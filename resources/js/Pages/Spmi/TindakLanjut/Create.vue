<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const props = defineProps({
    temuan: Object,
    petugas: Array,
});

const form = useForm({
    temuan_id: props.temuan.id,
    penanggung_jawab_id: props.petugas?.[0]?.id || '',
    analisa_penyebab: '',
    metode_5_whys: '',
    rencana_tindakan: '',
    tindakan_pencegahan: '',
    target_selesai: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    bukti_tindakan: null,
});

const isAnalyzingRca = ref(false);
const isSuggestingAction = ref(false);

const aiAnalyzeRootCause = async () => {
    isAnalyzingRca.value = true;
    try {
        const text = `${props.temuan.kategori}: ${props.temuan.uraian_temuan}. Bukti: ${props.temuan.bukti_objektif || '-'}`;
        const res = await axios.post('/ai/analyze-root-cause', { text });
        if (res.data.status === 'success') {
            form.analisa_penyebab = res.data.data;
            if (!form.metode_5_whys) {
                form.metode_5_whys = `1. Mengapa terjadi? Karena proses belum terdokumentasi optimal.\n2. Mengapa belum terdokumentasi? Kurangnya standarisasi SOP internal.\n3. Mengapa belum terstandarisasi? Belum ada evaluasi berkala di unit.\n4. Mengapa belum ada evaluasi? Beban kerja dan kurangnya reminder jadwal SPMI.\n5. Akar Masalah: Diperlukan sistem checklist operasional terintegrasi.`;
            }
        } else {
            alert(res.data.message || 'Gagal menghasilkan analisis AI.');
        }
    } catch (err) {
        alert('Gagal terhubung dengan layanan AI.');
    } finally {
        isAnalyzingRca.value = false;
    }
};

const aiSuggestAction = async () => {
    isSuggestingAction.value = true;
    try {
        const text = `${props.temuan.uraian_temuan}. Akar Penyebab: ${form.analisa_penyebab}`;
        const res = await axios.post('/ai/suggest-recommendation', { text });
        if (res.data.status === 'success') {
            form.rencana_tindakan = res.data.data;
            form.tindakan_pencegahan = `Melakukan review berkala setiap semester dan menetapkan penanggung jawab pengendali mutu dokumen internal.`;
        } else {
            alert(res.data.message || 'Gagal menghasilkan rekomendasi AI.');
        }
    } catch (err) {
        alert('Gagal terhubung dengan layanan AI.');
    } finally {
        isSuggestingAction.value = false;
    }
};

const handleFileChange = (e) => {
    form.bukti_tindakan = e.target.files[0];
};

const submit = () => {
    form.post('/tindak-lanjut', {
        forceFormData: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tindak Lanjut Temuan AMI" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <a href="/tindak-lanjut" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-1.5">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar Temuan
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Formulir Tindak Lanjut Temuan (PTK)</h1>
                <p class="text-xs text-slate-500 mt-0.5">Rencanakan tindakan perbaikan korektif dan pencegahan dengan bantuan Asisten AI SPMI.</p>
            </div>

            <!-- Temuan Summary Box -->
            <div class="p-5 rounded-3xl bg-rose-50/60 border border-rose-200/80 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="font-mono font-bold text-xs text-rose-700">{{ temuan.kode_temuan }}</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-rose-200 text-rose-800">
                        {{ temuan.kategori }}
                    </span>
                    <span class="text-xs text-slate-500">Unit: {{ temuan.audit?.unit_yang_diaudit }}</span>
                </div>
                <p class="text-xs font-bold text-slate-900">{{ temuan.uraian_temuan }}</p>
                <p class="text-[11px] text-slate-600"><strong>Bukti Objektif:</strong> {{ temuan.bukti_objektif || '-' }}</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Penanggung Jawab Tindakan <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.penanggung_jawab_id"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option v-for="u in petugas" :key="u.id" :value="u.id">{{ u.name }} ({{ u.role || 'Staff' }})</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Target Selesai <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.target_selesai"
                                type="date"
                                required
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <!-- RCA with AI Trigger -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Analisa Akar Penyebab Masalah (RCA) <span class="text-rose-500">*</span>
                            </label>
                            <button
                                type="button"
                                @click="aiAnalyzeRootCause"
                                :disabled="isAnalyzingRca"
                                class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[11px] font-bold shadow-xs hover:opacity-95 transition flex items-center gap-1 cursor-pointer disabled:opacity-50"
                            >
                                <i class="bi bi-stars"></i>
                                <span>{{ isAnalyzingRca ? 'AI Sedang Menganalisis...' : '✨ AI Analisis Akar Masalah' }}</span>
                            </button>
                        </div>
                        <textarea
                            v-model="form.analisa_penyebab"
                            rows="3"
                            required
                            placeholder="Jelaskan mengapa ketidaksesuaian ini bisa terjadi..."
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Metode Analisa 5-Whys (Opsional)
                        </label>
                        <textarea
                            v-model="form.metode_5_whys"
                            rows="3"
                            placeholder="Why 1 -> Why 2 -> Why 3 -> Why 4 -> Why 5 (Akar masalah utama)"
                            class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        ></textarea>
                    </div>

                    <!-- Corrective Action with AI Trigger -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Rencana Tindakan Korektif <span class="text-rose-500">*</span>
                                </label>
                                <button
                                    type="button"
                                    @click="aiSuggestAction"
                                    :disabled="isSuggestingAction"
                                    class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-bold hover:bg-indigo-100 transition flex items-center gap-1 cursor-pointer disabled:opacity-50"
                                >
                                    <i class="bi bi-magic"></i>
                                    <span>{{ isSuggestingAction ? 'Menyarankan...' : '✨ AI Rekomendasi' }}</span>
                                </button>
                            </div>
                            <textarea
                                v-model="form.rencana_tindakan"
                                rows="3"
                                required
                                placeholder="Langkah konkrit yang akan dilakukan..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tindakan Pencegahan (Preventive Action)
                            </label>
                            <textarea
                                v-model="form.tindakan_pencegahan"
                                rows="3"
                                placeholder="Langkah agar masalah serupa tidak terulang kembali..."
                                class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Lampiran Bukti Tindakan (PDF, Word, Gambar - Max 10MB)
                        </label>
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-2xl p-2"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/tindak-lanjut"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Kirim Tindak Lanjut' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
