<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    appSettings: {
        type: Object,
        default: () => ({}),
    },
});

const showPassword = ref(false);
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk ke Sistem" />

    <div class="min-h-screen min-h-[100dvh] bg-slate-950 text-slate-100 flex items-center justify-center p-4 sm:p-6 relative overflow-hidden font-sans select-none">
        <!-- Ambient Glowing Orbs Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[500px] sm:w-[650px] h-[500px] sm:h-[650px] rounded-full bg-indigo-600/25 blur-[120px] animate-pulse"></div>
            <div class="absolute -bottom-[15%] -right-[10%] w-[550px] sm:w-[700px] h-[550px] sm:h-[700px] rounded-full bg-cyan-500/20 blur-[130px] animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute top-[35%] right-[15%] w-[350px] sm:w-[450px] h-[350px] sm:h-[450px] rounded-full bg-violet-600/20 blur-[100px] animate-pulse" style="animation-delay: 4s"></div>
            <!-- Grid Background Overlay -->
            <div class="absolute inset-0 opacity-[0.03] bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]"></div>
        </div>

        <!-- Main Card Container -->
        <div class="relative z-10 w-full max-w-5xl min-h-[620px] flex flex-col lg:flex-row rounded-3xl bg-slate-900/60 border border-white/10 backdrop-blur-2xl shadow-2xl shadow-black/60 overflow-hidden">
            <!-- Left Panel (Visual Highlights & Feature List) -->
            <div class="hidden lg:flex flex-1 flex-col justify-between p-10 xl:p-12 bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-slate-950/90 border-r border-white/10 relative">
                <!-- App Logo & Branding -->
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 text-xl font-extrabold tracking-wider">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold text-white tracking-tight block">
                            {{ appSettings?.appName || 'PINTAR' }}
                        </span>
                        <span class="text-[11px] font-semibold tracking-wider text-indigo-300 uppercase">
                            Pusat Penjaminan Mutu & Tata Kelola
                        </span>
                    </div>
                </div>

                <!-- Mid Headline & Cards -->
                <div class="my-auto py-8">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight leading-snug mb-3">
                        Meningkatkan Mutu & <br />
                        <span class="bg-gradient-to-r from-indigo-300 via-cyan-300 to-teal-200 bg-clip-text text-transparent">
                            Integritas Akademik
                        </span>
                    </h1>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-6 max-w-md">
                        Sistem manajemen internal terpadu untuk kendali audit lapangan, siklus penjaminan mutu, dan tata kelola perguruan tinggi.
                    </p>

                    <!-- Feature Cards -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-indigo-500/30 transition duration-300">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/15 text-indigo-400 flex items-center justify-center text-lg shrink-0">
                                <i class="bi bi-clipboard2-check-fill"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-white">Audit Mutu Internal (AMI)</h3>
                                <p class="text-[11px] text-slate-400">Siklus audit komprehensif berbasis instrumen & kertas kerja digital.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-cyan-500/30 transition duration-300">
                            <div class="w-10 h-10 rounded-xl bg-cyan-500/15 text-cyan-400 flex items-center justify-center text-lg shrink-0">
                                <i class="bi bi-collection-play-fill"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-white">Siklus PPEPP Otomatis</h3>
                                <p class="text-[11px] text-slate-400">Pelacak kemajuan alur mutu mulai dari Penetapan hingga Peningkatan.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-emerald-500/30 transition duration-300">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-lg shrink-0">
                                <i class="bi bi-folder-fill"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-white">E-Repositori Terpusat</h3>
                                <p class="text-[11px] text-slate-400">Arsip tunggal yang aman untuk dokumen standar, SOP, dan SK.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer info -->
                <div class="text-[11px] text-slate-500">
                    © {{ new Date().getFullYear() }} Penjaminan Mutu Internal. Hak Cipta Dilindungi.
                </div>
            </div>

            <!-- Right Panel (Interactive Login Form) -->
            <div class="flex-1 flex flex-col justify-center p-8 sm:p-12 lg:p-14 bg-slate-900/40">
                <!-- Mobile Logo Header -->
                <div class="lg:hidden text-center mb-6">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center text-white text-xl font-bold mb-2 shadow-lg shadow-indigo-500/30">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-white">{{ appSettings?.appName || 'PINTAR' }}</h2>
                    <p class="text-xs text-slate-400">Pusat Penjaminan Mutu & Tata Kelola</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Selamat Datang</h2>
                    <p class="text-slate-400 text-xs sm:text-sm mt-1.5">
                        Masuk ke akun penjaminan mutu internal Anda
                    </p>
                </div>

                <!-- Error Flash Alert -->
                <div
                    v-if="form.errors.email || form.errors.password"
                    class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-center gap-3 animate-shake"
                >
                    <i class="bi bi-exclamation-triangle-fill text-base shrink-0 text-rose-400"></i>
                    <span class="flex-1 font-medium">{{ form.errors.email || form.errors.password }}</span>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            Alamat Email
                        </label>
                        <div class="relative flex items-center">
                            <i class="bi bi-envelope-fill absolute left-3.5 text-slate-500 text-sm pointer-events-none"></i>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autocomplete="email"
                                placeholder="nama@institusi.ac.id"
                                class="w-full pl-10 pr-4 py-3 text-sm rounded-xl bg-slate-900/60 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-200"
                                :class="{ 'border-rose-500/60 focus:ring-rose-500/40': form.errors.email }"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            Kata Sandi
                        </label>
                        <div class="relative flex items-center">
                            <i class="bi bi-lock-fill absolute left-3.5 text-slate-500 text-sm pointer-events-none"></i>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi Anda"
                                class="w-full pl-10 pr-11 py-3 text-sm rounded-xl bg-slate-900/60 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition duration-200"
                                :class="{ 'border-rose-500/60 focus:ring-rose-500/40': form.errors.password }"
                            />
                            <button
                                type="button"
                                @click="togglePassword"
                                class="absolute right-3.5 text-slate-400 hover:text-white transition p-1"
                                tabindex="-1"
                                :title="showPassword ? 'Sembunyikan password' : 'Lihat password'"
                            >
                                <i :class="showPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 rounded bg-slate-900 border-white/20 text-indigo-600 focus:ring-indigo-500/40 focus:ring-offset-0 transition cursor-pointer"
                            />
                            <span class="text-xs font-medium text-slate-400 hover:text-slate-300">Ingat Saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-0.5 active:translate-y-0 transition duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed disabled:transform-none"
                    >
                        <i v-if="!form.processing" class="bi bi-box-arrow-in-right text-base"></i>
                        <svg v-else class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>{{ form.processing ? 'Memproses Masuk...' : 'Masuk ke Dashboard' }}</span>
                    </button>
                </form>

                <p class="text-center text-[11px] text-slate-500 mt-8">
                    Lupa kata sandi? Hubungi <span class="text-indigo-400 font-semibold">Administrator TI</span>
                </p>
            </div>
        </div>
    </div>
</template>
