<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    settings: Object,
    serverInfo: Object,
});

const activeTab = ref('general');

const form = useForm({
    app_name: props.settings.app_name || '',
    app_tagline: props.settings.app_tagline || '',
    theme_primary: props.settings.theme_primary || '#4f46e5',
    theme_sidebar: props.settings.theme_sidebar || 'dark',
    
    // Institusi
    nama_institusi: props.settings.nama_institusi || '',
    alamat_institusi: props.settings.alamat_institusi || '',
    kota_institusi: props.settings.kota_institusi || '',
    email_institusi: props.settings.email_institusi || '',
    telepon_institusi: props.settings.telepon_institusi || '',
    website_institusi: props.settings.website_institusi || '',
    
    // Files
    logo: null,
    favicon: null,
    logo_institusi: null,
    kop_surat_yayasan: null,
    kop_surat_pt: null,
});

// File Previews
const previews = ref({
    logo: props.settings.logo || null,
    favicon: props.settings.favicon || null,
    logo_institusi: props.settings.logo_institusi || null,
    kop_surat_yayasan: props.settings.kop_surat_yayasan || null,
    kop_surat_pt: props.settings.kop_surat_pt || null,
});

const handleFileChange = (e, field) => {
    const file = e.target.files[0];
    if (file) {
        form[field] = file;
        const reader = new FileReader();
        reader.onload = (event) => {
            previews.value[field] = event.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const presetColors = [
    { name: 'Indigo Brand', hex: '#4f46e5' },
    { name: 'Polka Purple', hex: '#7c3aed' },
    { name: 'Ocean Blue', hex: '#2563eb' },
    { name: 'Teal Green', hex: '#0d9488' },
    { name: 'Emerald', hex: '#059669' },
    { name: 'Amber Gold', hex: '#d97706' },
    { name: 'Rose Red', hex: '#e11d48' },
    { name: 'Dark Slate', hex: '#334155' },
];

const selectColor = (hex) => {
    form.theme_primary = hex;
};

const submitForm = () => {
    form.post('/settings', {
        forceFormData: true,
        preserveScroll: true,
    });
};

const clearCache = () => {
    if (confirm('Bersihkan seluruh cache aplikasi, views, routes, dan konfigurasi sekarang?')) {
        router.post('/settings/clear-cache', {}, {
            preserveScroll: true,
        });
    }
};

const resetDefault = () => {
    if (confirm('Kembalikan semua pengaturan tampilan & nama aplikasi ke konfigurasi bawaan?')) {
        router.post('/settings/reset', {}, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pengaturan Sistem & Institusi" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/20 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/10 text-indigo-200 border border-white/20 uppercase tracking-wider">
                            Pusat Pengaturan
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Super Admin
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Pengaturan Sistem & Identitas Kampus
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
                        Kelola identitas resmi kampus, kop surat PDF institusi, logo, tema aplikasi, dan optimasi performa sistem.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                    <button
                        @click="clearCache"
                        class="px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center gap-2 border border-white/20 cursor-pointer backdrop-blur-sm"
                        title="Bersihkan Cache Aplikasi"
                    >
                        <i class="bi bi-arrow-repeat text-indigo-300"></i>
                        <span>Clear Cache</span>
                    </button>
                    <button
                        @click="submitForm"
                        :disabled="form.processing"
                        class="px-6 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-floppy-fill"></i>
                        <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                    </button>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex items-center gap-2 border-b border-slate-200/80 overflow-x-auto pb-2 scrollbar-none">
                <button
                    @click="activeTab = 'general'"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0 cursor-pointer"
                    :class="activeTab === 'general' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
                >
                    <i class="bi bi-gear-fill"></i>
                    <span>Aplikasi & Branding</span>
                </button>

                <button
                    @click="activeTab = 'institusi'"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0 cursor-pointer"
                    :class="activeTab === 'institusi' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
                >
                    <i class="bi bi-building-fill"></i>
                    <span>Identitas Kampus</span>
                </button>

                <button
                    @click="activeTab = 'kop'"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0 cursor-pointer"
                    :class="activeTab === 'kop' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
                >
                    <i class="bi bi-file-earmark-image-fill"></i>
                    <span>Kop Surat Resmi (PDF)</span>
                </button>

                <button
                    @click="activeTab = 'theme'"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0 cursor-pointer"
                    :class="activeTab === 'theme' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
                >
                    <i class="bi bi-palette-fill"></i>
                    <span>Tema & Warna</span>
                </button>

                <button
                    @click="activeTab = 'system'"
                    class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-2 shrink-0 cursor-pointer"
                    :class="activeTab === 'system' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'"
                >
                    <i class="bi bi-hdd-stack-fill"></i>
                    <span>Info Sistem & Maintenance</span>
                </button>
            </div>

            <!-- Tab Content Forms -->
            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- 1. GENERAL & BRANDING TAB -->
                <div v-show="activeTab === 'general'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Nama & Tagline Aplikasi</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Konfigurasi nama portal sistem ERP dan deskripsi aplikasi.</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nama Aplikasi <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.app_name"
                                    type="text"
                                    required
                                    placeholder="Contoh: PINTAR"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-semibold text-slate-800"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Tagline Aplikasi
                                </label>
                                <input
                                    v-model="form.app_tagline"
                                    type="text"
                                    placeholder="Contoh: Integrated Campus Enterprise Resource Planning"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-800"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Logo & Favicon Upload -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Logo Aplikasi (Header)</h4>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                                <div v-if="previews.logo" class="mb-3 max-h-20 flex items-center justify-center">
                                    <img :src="previews.logo" alt="Logo App" class="max-h-16 object-contain" />
                                </div>
                                <div v-else class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-3">
                                    <i class="bi bi-image"></i>
                                </div>
                                <input
                                    type="file"
                                    accept="image/png,image/jpeg,image/svg+xml"
                                    @change="e => handleFileChange(e, 'logo')"
                                    class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                                <p class="text-[10px] text-slate-400 mt-2">Format: PNG/SVG transparan, max 2MB.</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Favicon Browser</h4>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                                <div v-if="previews.favicon" class="mb-3">
                                    <img :src="previews.favicon" alt="Favicon" class="w-8 h-8 object-contain" />
                                </div>
                                <div v-else class="w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center text-lg mb-3">
                                    <i class="bi bi-globe"></i>
                                </div>
                                <input
                                    type="file"
                                    accept="image/x-icon,image/png"
                                    @change="e => handleFileChange(e, 'favicon')"
                                    class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                                <p class="text-[10px] text-slate-400 mt-2">Format: ICO/PNG persegi (32x32px), max 512KB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. INSTITUSI TAB -->
                <div v-show="activeTab === 'institusi'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Data Resmi Perguruan Tinggi</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Informasi ini dicantumkan pada seluruh dokumen dinas, surat tugas, berita acara SPMI, dan laporan institusi.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nama Resmi Institusi / Kampus <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.nama_institusi"
                                    type="text"
                                    required
                                    placeholder="Contoh: POLITEKNIK KAMPUS AKADEMIK"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-slate-900"
                                />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Alamat Kampus Terpadu
                                </label>
                                <textarea
                                    v-model="form.alamat_institusi"
                                    rows="2"
                                    placeholder="Contoh: Jl. Raya Kampus Terpadu No. 1, Komplek Akademik Mandiri"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-800"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Kota Domisili Kampus
                                </label>
                                <input
                                    v-model="form.kota_institusi"
                                    type="text"
                                    placeholder="Contoh: Kota Bandung"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-800"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Email Resmi Institusi
                                </label>
                                <input
                                    v-model="form.email_institusi"
                                    type="email"
                                    placeholder="Contoh: info@polka.ac.id"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-800"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Nomor Telepon / Kontak
                                </label>
                                <input
                                    v-model="form.telepon_institusi"
                                    type="text"
                                    placeholder="Contoh: (021) 12345678"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-800"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                    Website Resmi Kampus
                                </label>
                                <input
                                    v-model="form.website_institusi"
                                    type="url"
                                    placeholder="Contoh: https://polka.ac.id"
                                    class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-800"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Logo Institusi -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Logo Lambang Institusi</h4>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-center">
                            <div v-if="previews.logo_institusi" class="mb-4">
                                <img :src="previews.logo_institusi" alt="Logo Institusi" class="max-h-28 object-contain" />
                            </div>
                            <div v-else class="w-20 h-20 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mb-4">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <input
                                type="file"
                                accept="image/png,image/jpeg"
                                @change="e => handleFileChange(e, 'logo_institusi')"
                                class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                            />
                            <p class="text-[10px] text-slate-400 mt-2">Digunakan untuk kop surat & sertifikat. PNG resolusi tinggi transparan direkomendasikan.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. KOP SURAT TAB -->
                <div v-show="activeTab === 'kop'" class="space-y-6">
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Kop Surat Resmi Institusi (PDF Header)</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Gambar banner kop surat yang disematkan secara otomatis di atas surat tugas, SPPD, dan generator SK.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kop Yayasan -->
                            <div class="p-5 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                                <div class="flex items-center gap-2 text-indigo-700 font-bold text-xs">
                                    <i class="bi bi-building"></i>
                                    <span>Kop Surat Yayasan / Badan Pengelola</span>
                                </div>
                                <div class="p-3 rounded-2xl bg-white border border-slate-200 min-h-28 flex items-center justify-center">
                                    <img
                                        v-if="previews.kop_surat_yayasan"
                                        :src="previews.kop_surat_yayasan"
                                        alt="Kop Yayasan"
                                        class="w-full max-h-24 object-contain"
                                    />
                                    <div v-else class="text-center text-slate-400 text-xs py-4">
                                        <i class="bi bi-image text-2xl d-block mb-1 text-slate-300"></i>
                                        <p>Belum ada gambar kop surat Yayasan</p>
                                    </div>
                                </div>
                                <input
                                    type="file"
                                    accept="image/png,image/jpeg"
                                    @change="e => handleFileChange(e, 'kop_surat_yayasan')"
                                    class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                                <p class="text-[10px] text-slate-400">PNG/JPG landscape lebar penuh (proporsi A4 header), max 4MB.</p>
                            </div>

                            <!-- Kop PT -->
                            <div class="p-5 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                                <div class="flex items-center gap-2 text-emerald-700 font-bold text-xs">
                                    <i class="bi bi-mortarboard"></i>
                                    <span>Kop Surat Perguruan Tinggi / Politeknik</span>
                                </div>
                                <div class="p-3 rounded-2xl bg-white border border-slate-200 min-h-28 flex items-center justify-center">
                                    <img
                                        v-if="previews.kop_surat_pt"
                                        :src="previews.kop_surat_pt"
                                        alt="Kop PT"
                                        class="w-full max-h-24 object-contain"
                                    />
                                    <div v-else class="text-center text-slate-400 text-xs py-4">
                                        <i class="bi bi-image text-2xl d-block mb-1 text-slate-300"></i>
                                        <p>Belum ada gambar kop surat Perguruan Tinggi</p>
                                    </div>
                                </div>
                                <input
                                    type="file"
                                    accept="image/png,image/jpeg"
                                    @change="e => handleFileChange(e, 'kop_surat_pt')"
                                    class="text-[11px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                                />
                                <p class="text-[10px] text-slate-400">PNG/JPG landscape lebar penuh (proporsi A4 header), max 4MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. TEMA & WARNA TAB -->
                <div v-show="activeTab === 'theme'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Kustomisasi Tampilan & Aksen</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Atur warna aksen antarmuka dan mode tampilan sidebar portal.</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                                    Warna Utama (Primary Accent) <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <input
                                        v-model="form.theme_primary"
                                        type="color"
                                        class="w-12 h-12 rounded-2xl border border-slate-200 cursor-pointer p-1"
                                    />
                                    <input
                                        v-model="form.theme_primary"
                                        type="text"
                                        required
                                        pattern="^#[a-fA-F0-9]{6}$"
                                        placeholder="#4f46e5"
                                        class="px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 font-mono text-slate-800 uppercase"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-2.5">
                                    Preset Palet Warna Pilihan
                                </label>
                                <div class="flex flex-wrap gap-2.5">
                                    <button
                                        v-for="color in presetColors"
                                        :key="color.hex"
                                        type="button"
                                        @click="selectColor(color.hex)"
                                        class="px-3 py-1.5 rounded-xl border text-xs font-bold flex items-center gap-2 transition cursor-pointer"
                                        :class="form.theme_primary === color.hex ? 'border-slate-900 bg-slate-900 text-white shadow-sm' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100'"
                                    >
                                        <span class="w-3.5 h-3.5 rounded-full border border-black/10" :style="{ backgroundColor: color.hex }"></span>
                                        <span>{{ color.name }}</span>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                                    Gaya Sidebar Menu <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-4 max-w-md">
                                    <label
                                        class="p-4 rounded-2xl border flex items-center gap-3 cursor-pointer transition"
                                        :class="form.theme_sidebar === 'dark' ? 'border-indigo-600 bg-indigo-50/40 text-indigo-900 font-bold' : 'border-slate-200 text-slate-700'"
                                    >
                                        <input type="radio" v-model="form.theme_sidebar" value="dark" class="text-indigo-600" />
                                        <span>Dark Mode Sidebar</span>
                                    </label>

                                    <label
                                        class="p-4 rounded-2xl border flex items-center gap-3 cursor-pointer transition"
                                        :class="form.theme_sidebar === 'light' ? 'border-indigo-600 bg-indigo-50/40 text-indigo-900 font-bold' : 'border-slate-200 text-slate-700'"
                                    >
                                        <input type="radio" v-model="form.theme_sidebar" value="light" class="text-indigo-600" />
                                        <span>Clean Light Sidebar</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Options -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pengaturan Bawaan</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Jika terjadi kesalahan konfigurasi visual, Anda dapat mengembalikan tema ke pengaturan bawaan.
                        </p>
                        <button
                            type="button"
                            @click="resetDefault"
                            class="w-full py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Reset Tema ke Default</span>
                        </button>
                    </div>
                </div>

                <!-- 5. INFO SISTEM & MAINTENANCE TAB -->
                <div v-show="activeTab === 'system'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Informasi Server & Environment</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Rincian status perangkat lunak server dan lingkungan hosting PINTAR.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">PHP Version</span>
                                <p class="font-black text-slate-900 font-mono text-sm">{{ serverInfo?.php_version }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Laravel Framework</span>
                                <p class="font-black text-indigo-700 font-mono text-sm">v{{ serverInfo?.laravel_version }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Database Connection</span>
                                <p class="font-bold text-slate-900 font-mono capitalize">{{ serverInfo?.db_connection }} (PostgreSQL)</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <span class="text-slate-400 font-medium">Application Environment</span>
                                <p class="font-bold text-slate-900 font-mono uppercase">{{ serverInfo?.app_env }}</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-1 sm:col-span-2">
                                <span class="text-slate-400 font-medium">Server Software</span>
                                <p class="font-bold text-slate-700 font-mono truncate">{{ serverInfo?.server_software }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Actions -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pemeliharaan Cache</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Lakukan refresh cache jika ada perubahan struktur database, rute baru, atau pembaruan konfigurasi yang belum termuat.
                        </p>
                        <button
                            type="button"
                            @click="clearCache"
                            class="w-full py-3 rounded-2xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition flex items-center justify-center gap-2 border border-indigo-200 cursor-pointer"
                        >
                            <i class="bi bi-arrow-repeat text-base"></i>
                            <span>Bersihkan Cache Sekarang</span>
                        </button>
                    </div>
                </div>

                <!-- Bottom Save Button for all tabs -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200/80">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-600/30 cursor-pointer"
                    >
                        <i class="bi bi-floppy-fill"></i>
                        <span>{{ form.processing ? 'Menyimpan Pengaturan...' : 'Simpan Semua Pengaturan' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
