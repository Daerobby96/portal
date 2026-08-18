<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    kuesioner: Object,
    prodis: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user?.id);
const authUser = computed(() => page.props.auth?.user || {});
const flash = computed(() => page.props.flash || {});

// Deteksi apakah ini kuesioner eksternal (wajib nama/perusahaan)
const isExternalSurvey = computed(() => {
    const id = props.kuesioner?.id;
    const judul = props.kuesioner?.judul?.toLowerCase() || '';
    return id === 11 || id === 12 || judul.includes('pengguna lulusan') || judul.includes('mitra kerjasama');
});

// Tentukan default kategori responden
const getDefaultCategory = () => {
    const judul = props.kuesioner?.judul?.toLowerCase() || '';
    if (judul.includes('edom') || judul.includes('pembelajaran') || judul.includes('wisudawan') || judul.includes('skripsi') || judul.includes('dosen pa') || judul.includes('baak') || judul.includes('kemahasiswaan')) {
        return 'Mahasiswa';
    }
    if (judul.includes('dosen')) {
        return 'Dosen';
    }
    if (judul.includes('tendik') || judul.includes('tenaga kependidikan')) {
        return 'Tenaga Kependidikan';
    }
    if (judul.includes('pengguna lulusan') || judul.includes('employer')) {
        return 'Pengguna Lulusan';
    }
    if (judul.includes('mitra')) {
        return 'Mitra Kerjasama';
    }
    return 'Mahasiswa';
};

const isAnonymous = ref(!isExternalSurvey.value);

const initialAnswers = {};
if (props.kuesioner.pertanyaans) {
    props.kuesioner.pertanyaans.forEach((p) => {
        initialAnswers[p.id] = p.tipe === 'likert' ? '4' : '';
    });
}

const form = useForm({
    is_anonymous: isAnonymous.value,
    nama_responden: '',
    identitas_nomor: '',
    kategori_responden: getDefaultCategory(),
    program_studi: props.prodis && props.prodis.length > 0 ? `${props.prodis[0].jenjang} ${props.prodis[0].nama}` : '',
    angkatan_semester: 'Semester 4',
    instansi: '',
    jabatan: '',
    email_responden: '',
    no_hp_responden: '',
    jawaban: initialAnswers,
});

watch(isAnonymous, (val) => {
    form.is_anonymous = val;
    if (val) {
        form.nama_responden = '';
        form.identitas_nomor = '';
        form.email_responden = '';
        form.no_hp_responden = '';
    }
});

const submit = () => {
    form.post(`/survei/${props.kuesioner.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Pengisian Survei: ${kuesioner.judul}`" />

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- IF AUTHENTICATED: WRAP IN AUTHENTICATED LAYOUT                     -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <AuthenticatedLayout v-if="isAuthenticated">
        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <Link href="/survei" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5 mb-2">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Daftar Survei</span>
                </Link>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                        {{ kuesioner.periode?.nama || 'Periode Aktif' }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Survei Aktif
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ kuesioner.judul }}</h1>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ kuesioner.deskripsi || 'Silakan pilih skala penilaian yang paling menggambarkan evaluasi Anda secara objektif.' }}</p>
            </div>

            <!-- Flash Error -->
            <div v-if="flash.error" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
                <span>{{ flash.error }}</span>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- FORM PROFIL RESPONDEN (INTERNAL) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-slate-900">Profil Responden & Jaminan Privasi</h2>
                                <p class="text-xs text-slate-500">Kerahasiaan identitas Anda terjamin untuk menjaga kejujuran dan objektivitas evaluasi.</p>
                            </div>
                        </div>

                        <!-- Toggle Anonim -->
                        <div v-if="!isExternalSurvey" class="flex items-center gap-2.5 p-2 rounded-2xl bg-slate-50 border border-slate-200 shrink-0">
                            <input
                                id="anon_auth"
                                type="checkbox"
                                v-model="isAnonymous"
                                class="w-4 h-4 text-indigo-600 rounded-md focus:ring-indigo-500"
                            />
                            <label for="anon_auth" class="text-xs font-bold text-slate-700 cursor-pointer select-none">
                                Kirim Secara Anonim 🔒
                            </label>
                        </div>
                    </div>

                    <!-- Banner jika Anonim Aktif -->
                    <div v-if="isAnonymous && !isExternalSurvey" class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                        <i class="bi bi-shield-lock-fill text-emerald-600 text-sm"></i>
                        <span><strong>Mode Anonim Aktif:</strong> Nama dan identitas Anda disamarkan. Hanya Program Studi dan Semester/Angkatan yang dicatat untuk rekapitulasi akreditasi.</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Responden <span class="text-rose-500">*</span></label>
                            <select v-model="form.kategori_responden" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none bg-white">
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Tenaga Kependidikan">Tenaga Kependidikan (Tendik)</option>
                                <option value="Alumni">Alumni / Calon Wisudawan</option>
                                <option value="Pengguna Lulusan">Pengguna Lulusan / Industri</option>
                                <option value="Mitra Kerjasama">Mitra Kerjasama</option>
                                <option value="Umum">Masyarakat / Umum</option>
                            </select>
                        </div>

                        <div v-if="prodis && prodis.length > 0">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi Terkait <span class="text-rose-500">*</span></label>
                            <select v-model="form.program_studi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none bg-white">
                                <option v-for="p in prodis" :key="p.id" :value="`${p.jenjang} ${p.nama}`">
                                    {{ p.jenjang }} - {{ p.nama }}
                                </option>
                            </select>
                        </div>

                        <div v-if="['Mahasiswa', 'Alumni'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Semester / Tahun Angkatan</label>
                            <input v-model="form.angkatan_semester" type="text" placeholder="Contoh: Semester 4 / Angkatan 2023" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <!-- Kolom Nama (Muncul jika Anonim dimatikan atau Kuesioner Eksternal) -->
                        <div v-if="!isAnonymous || isExternalSurvey">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Responden <span class="text-rose-500">*</span></label>
                            <input v-model="form.nama_responden" type="text" :required="!isAnonymous || isExternalSurvey" placeholder="Masukkan nama lengkap Anda..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <div v-if="(!isAnonymous || isExternalSurvey) && ['Mahasiswa', 'Dosen', 'Tenaga Kependidikan', 'Alumni'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Identitas (NIM / NIP / NIK)</label>
                            <input v-model="form.identitas_nomor" type="text" placeholder="Contoh: 182504001..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <div v-if="isExternalSurvey || ['Pengguna Lulusan', 'Mitra Kerjasama'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Instansi / Perusahaan <span class="text-rose-500">*</span></label>
                            <input v-model="form.instansi" type="text" required placeholder="Contoh: PT Telkom Indonesia / RS Daerah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <div v-if="isExternalSurvey || ['Pengguna Lulusan', 'Mitra Kerjasama'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan Penilai</label>
                            <input v-model="form.jabatan" type="text" placeholder="Contoh: Manager HRD / Supervisor" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>
                    </div>
                </div>

                <!-- BUTIR PERTANYAAN -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h2 class="text-base font-black text-slate-900">Daftar Pertanyaan Evaluasi</h2>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                            {{ kuesioner.pertanyaans?.length || 0 }} Butir Pertanyaan
                        </span>
                    </div>

                    <div
                        v-for="(p, index) in kuesioner.pertanyaans"
                        :key="p.id"
                        class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3"
                    >
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shrink-0">
                                {{ index + 1 }}
                            </span>
                            <div class="text-xs font-bold text-slate-900 leading-relaxed pt-0.5">
                                {{ p.pertanyaan }}
                            </div>
                        </div>

                        <!-- Likert 1-4 scale -->
                        <div v-if="p.tipe === 'likert'" class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2">
                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '1' ? 'bg-rose-50 border-rose-400 text-rose-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="1" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>1 - Sangat Kurang</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '2' ? 'bg-amber-50 border-amber-400 text-amber-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="2" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>2 - Kurang</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '3' ? 'bg-sky-50 border-sky-400 text-sky-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="3" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>3 - Baik</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '4' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="4" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>4 - Sangat Baik</span>
                            </label>
                        </div>

                        <!-- Open text -->
                        <div v-else class="pt-2">
                            <textarea
                                v-model="form.jawaban[p.id]"
                                rows="3"
                                placeholder="Tuliskan saran atau masukan objektif Anda..."
                                class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <Link href="/survei" class="px-5 py-2.5 rounded-2xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 rounded-2xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50 flex items-center gap-2 cursor-pointer"
                        >
                            <i class="bi bi-send-fill text-xs"></i>
                            <span>{{ form.processing ? 'Mengirim Jawaban...' : 'Kirim Jawaban Survei' }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- IF GUEST / PUBLIC USER: STANDALONE PUBLIC SURVEY LAYOUT            -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div v-else class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-600 selection:text-white flex flex-col">
        <!-- Public Minimalist Navbar -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-600 to-slate-900 text-white flex items-center justify-center font-black text-sm">
                        P
                    </div>
                    <span class="font-black text-slate-900 text-sm">PINTAR</span>
                </Link>

                <div class="flex items-center gap-3">
                    <Link href="/survei" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1">
                        <i class="bi bi-grid"></i>
                        <span>Daftar Survei</span>
                    </Link>
                    <Link href="/" class="text-xs font-bold text-slate-600 hover:text-indigo-600 transition flex items-center gap-1">
                        <i class="bi bi-house"></i>
                        <span>Beranda</span>
                    </Link>
                    <Link href="/login" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs">
                        Login Portal
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Form Container -->
        <main class="max-w-3xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full space-y-6">
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-md mb-3">
                    <i class="bi bi-chat-square-text"></i>
                    <span>Survei Terbuka Bebas Akses</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">{{ kuesioner.judul }}</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                    {{ kuesioner.deskripsi || 'Sampaikan penilaian dan masukan objektif Anda untuk continuous quality improvement mutu layanan institusi.' }}
                </p>
            </div>

            <!-- Flash Error -->
            <div v-if="flash.error" class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
                <span>{{ flash.error }}</span>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- FORM PROFIL RESPONDEN (PUBLIK) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-black text-slate-900">Profil & Jaminan Kerahasiaan</h2>
                                <p class="text-xs text-slate-500">Anda dapat memberikan penilaian secara bebas, jujur, dan objektif.</p>
                            </div>
                        </div>

                        <!-- Toggle Anonim -->
                        <div v-if="!isExternalSurvey" class="flex items-center gap-2.5 p-2 rounded-2xl bg-slate-50 border border-slate-200 shrink-0">
                            <input
                                id="anon_guest"
                                type="checkbox"
                                v-model="isAnonymous"
                                class="w-4 h-4 text-indigo-600 rounded-md focus:ring-indigo-500"
                            />
                            <label for="anon_guest" class="text-xs font-bold text-slate-700 cursor-pointer select-none">
                                Kirim Secara Anonim 🔒
                            </label>
                        </div>
                    </div>

                    <!-- Banner jika Anonim Aktif -->
                    <div v-if="isAnonymous && !isExternalSurvey" class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                        <i class="bi bi-shield-lock-fill text-emerald-600 text-sm"></i>
                        <span><strong>Mode Anonim Aktif:</strong> Identitas pribadi disamarkan. Anda hanya perlu memilih Program Studi dan Semester/Angkatan agar data dapat direkapitulasi pada akreditasi prodi.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status / Kategori Responden <span class="text-rose-500">*</span></label>
                            <select v-model="form.kategori_responden" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none bg-white">
                                <option value="Mahasiswa">Mahasiswa Aktif</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Tenaga Kependidikan">Tenaga Kependidikan (Tendik)</option>
                                <option value="Alumni">Alumni / Calon Wisudawan</option>
                                <option value="Pengguna Lulusan">Pengguna Lulusan / Industri</option>
                                <option value="Mitra Kerjasama">Mitra Kerjasama</option>
                                <option value="Umum">Masyarakat Umum / Orang Tua</option>
                            </select>
                        </div>

                        <div v-if="prodis && prodis.length > 0">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi Terkait <span class="text-rose-500">*</span></label>
                            <select v-model="form.program_studi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none bg-white">
                                <option v-for="p in prodis" :key="p.id" :value="`${p.jenjang} ${p.nama}`">
                                    {{ p.jenjang }} - {{ p.nama }}
                                </option>
                            </select>
                        </div>

                        <div v-if="['Mahasiswa', 'Alumni'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Semester / Angkatan</label>
                            <input v-model="form.angkatan_semester" type="text" placeholder="Contoh: Semester 4 / Angkatan 2023" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <!-- Kolom Nama (Hanya Muncul jika Anonim dimatikan atau Kuesioner Industri/Mitra) -->
                        <div v-if="!isAnonymous || isExternalSurvey" class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Responden <span class="text-rose-500">*</span></label>
                            <input v-model="form.nama_responden" type="text" :required="!isAnonymous || isExternalSurvey" placeholder="Masukkan nama lengkap Anda..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <div v-if="(!isAnonymous || isExternalSurvey) && ['Mahasiswa', 'Dosen', 'Tenaga Kependidikan', 'Alumni'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Identitas (NIM / NIP / NIK)</label>
                            <input v-model="form.identitas_nomor" type="text" placeholder="Contoh: 182504001 / 198501..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <div v-if="isExternalSurvey || ['Pengguna Lulusan', 'Mitra Kerjasama'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Instansi / Perusahaan <span class="text-rose-500">*</span></label>
                            <input v-model="form.instansi" type="text" required placeholder="Contoh: PT Astra / RS Daerah" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>

                        <div v-if="isExternalSurvey || ['Pengguna Lulusan', 'Mitra Kerjasama'].includes(form.kategori_responden)">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Posisi</label>
                            <input v-model="form.jabatan" type="text" placeholder="Contoh: HRD Manager / Kepala Ruang" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-indigo-500/30 outline-none" />
                        </div>
                    </div>
                </div>

                <!-- BUTIR PERTANYAAN -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h2 class="text-base font-black text-slate-900">Daftar Butir Pertanyaan</h2>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                            {{ kuesioner.pertanyaans?.length || 0 }} Pertanyaan
                        </span>
                    </div>

                    <div
                        v-for="(p, index) in kuesioner.pertanyaans"
                        :key="p.id"
                        class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3"
                    >
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shrink-0">
                                {{ index + 1 }}
                            </span>
                            <div class="text-xs font-bold text-slate-900 leading-relaxed pt-0.5">
                                {{ p.pertanyaan }}
                            </div>
                        </div>

                        <!-- Likert 1-4 scale -->
                        <div v-if="p.tipe === 'likert'" class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2">
                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '1' ? 'bg-rose-50 border-rose-400 text-rose-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="1" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>1 - Sangat Kurang</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '2' ? 'bg-amber-50 border-amber-400 text-amber-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="2" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>2 - Kurang</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '3' ? 'bg-sky-50 border-sky-400 text-sky-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="3" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>3 - Baik</span>
                            </label>

                            <label
                                class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-semibold select-none"
                                :class="form.jawaban[p.id] === '4' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100'"
                            >
                                <input type="radio" :name="`p_${p.id}`" value="4" v-model="form.jawaban[p.id]" class="text-indigo-600" />
                                <span>4 - Sangat Baik</span>
                            </label>
                        </div>

                        <!-- Open text -->
                        <div v-else class="pt-2">
                            <textarea
                                v-model="form.jawaban[p.id]"
                                rows="3"
                                placeholder="Tuliskan tanggapan atau saran Anda..."
                                class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <Link href="/survei" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                            &larr; Batalkan dan kembali
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 rounded-2xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-md shadow-indigo-600/30 transition disabled:opacity-50 flex items-center gap-2 cursor-pointer"
                        >
                            <i class="bi bi-send-fill text-xs"></i>
                            <span>{{ form.processing ? 'Mengirim Jawaban...' : 'Kirim Jawaban Survei' }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </main>

        <!-- Public Footer -->
        <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
            <p>&copy; {{ new Date().getFullYear() }} PINTAR. Sistem Informasi Penjaminan Mutu Internal.</p>
        </footer>
    </div>
</template>
