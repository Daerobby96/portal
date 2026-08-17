<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    message: String,
    user: Object,
    system_time: String,
});

// Interactive state demonstration
const counter = ref(0);
const searchQuery = ref('');
const filterStatus = ref('all');

// Sample reactive dataset (simulating SPMI / Audit data)
const sampleAudits = ref([
    { id: 'AUD-001', unit: 'Program Studi D3 Farmasi', lead: 'Dr. Apt. Rina Kusuma', status: 'Selesai', score: 92 },
    { id: 'AUD-002', unit: 'Program Studi D3 Kebidanan', lead: 'Bd. Siti Aminah, M.Tr.Keb', status: 'Dalam Proses', score: 78 },
    { id: 'AUD-003', unit: 'Laboratorium Terpadu', lead: 'Ahmad Fauzi, M.Sc', status: 'Temuan Mayor', score: 64 },
    { id: 'AUD-004', unit: 'Biro Administrasi Akademik', lead: 'Hendri Prabowo, M.Kom', status: 'Selesai', score: 88 },
    { id: 'AUD-005', unit: 'Unit Penjaminan Mutu Internal', lead: 'Prof. Dr. Ir. Budi Santoso', status: 'Dalam Proses', score: 85 },
]);

const newUnitName = ref('');
const newLeadName = ref('');

const addSampleItem = () => {
    if (!newUnitName.value.trim()) return;
    sampleAudits.value.unshift({
        id: `AUD-${String(sampleAudits.value.length + 1).padStart(3, '0')}`,
        unit: newUnitName.value,
        lead: newLeadName.value || 'Auditor Internal',
        status: 'Dalam Proses',
        score: Math.floor(Math.random() * 25) + 75,
    });
    newUnitName.value = '';
    newLeadName.value = '';
};

const deleteItem = (id) => {
    sampleAudits.value = sampleAudits.value.filter(item => item.id !== id);
};

// Computed filtering
const filteredAudits = computed(() => {
    return sampleAudits.value.filter(item => {
        const matchesSearch = item.unit.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              item.lead.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              item.id.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = filterStatus.value === 'all' || item.status === filterStatus.value;
        return matchesSearch && matchesStatus;
    });
});

const averageScore = computed(() => {
    if (sampleAudits.value.length === 0) return 0;
    const total = sampleAudits.value.reduce((acc, curr) => acc + curr.score, 0);
    return Math.round(total / sampleAudits.value.length);
});
</script>

<template>
    <AppLayout>
        <Head title="Vue 3 Proof of Concept" />

        <div class="space-y-6">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-indigo-100 text-xs font-semibold backdrop-blur-md mb-3 border border-white/20">
                        <i class="bi bi-stars"></i>
                        Arsitektur Modern Aktif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Integrasi Vue 3 + Inertia.js Berhasil!
                    </h1>
                    <p class="text-indigo-100 text-sm mt-2 max-w-2xl leading-relaxed">
                        {{ message }}
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center">
                        <p class="text-[11px] text-indigo-200 uppercase font-bold tracking-wider">Status Stack</p>
                        <p class="text-lg font-extrabold text-emerald-300 flex items-center gap-1.5 justify-center mt-0.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Terkoneksi
                        </p>
                    </div>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Total Audit Simulasi</p>
                        <p class="text-2xl font-bold text-slate-900">{{ sampleAudits.length }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Rata-rata Skor Mutu</p>
                        <p class="text-2xl font-bold text-slate-900">{{ averageScore }}%</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Reaktivitas UI (State)</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xl font-bold text-slate-900">{{ counter }}</span>
                            <button
                                @click="counter++"
                                class="px-2 py-0.5 text-xs font-semibold bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition"
                            >
                                +1 Klik
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">Waktu Sinkronisasi</p>
                        <p class="text-xs font-semibold text-slate-800 mt-1">{{ system_time }}</p>
                    </div>
                </div>
            </div>

            <!-- Interactive Table Component -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Simulasi Tabel Reaktif (Vue Component)</h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Ketik pencarian atau ubah filter di bawah: tabel terupdate seketika <strong>tanpa reload halaman</strong>.
                        </p>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari unit atau auditor..."
                                class="pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-52 sm:w-64"
                            />
                        </div>
                        <select
                            v-model="filterStatus"
                            class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="all">Semua Status</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dalam Proses">Dalam Proses</option>
                            <option value="Temuan Mayor">Temuan Mayor</option>
                        </select>
                    </div>
                </div>

                <!-- Add item inline form -->
                <div class="p-4 bg-slate-50 border-b border-slate-100 flex flex-wrap items-center gap-3">
                    <span class="text-xs font-semibold text-slate-700">Tambah Data Cepat:</span>
                    <input
                        v-model="newUnitName"
                        type="text"
                        placeholder="Nama Unit / Prodi..."
                        class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1 min-w-[180px]"
                        @keyup.enter="addSampleItem"
                    />
                    <input
                        v-model="newLeadName"
                        type="text"
                        placeholder="Nama Auditor..."
                        class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1 min-w-[180px]"
                        @keyup.enter="addSampleItem"
                    />
                    <button
                        @click="addSampleItem"
                        class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-5">Kode Audit</th>
                                <th class="py-3 px-5">Unit / Auditee</th>
                                <th class="py-3 px-5">Ketua Auditor</th>
                                <th class="py-3 px-5">Nilai Mutu</th>
                                <th class="py-3 px-5">Status</th>
                                <th class="py-3 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="item in filteredAudits" :key="item.id" class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-5 font-semibold text-indigo-600 font-mono">{{ item.id }}</td>
                                <td class="py-3.5 px-5 font-medium text-slate-900">{{ item.unit }}</td>
                                <td class="py-3.5 px-5 text-slate-600">{{ item.lead }}</td>
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div
                                                class="h-1.5 rounded-full"
                                                :class="item.score >= 85 ? 'bg-emerald-500' : (item.score >= 70 ? 'bg-indigo-500' : 'bg-rose-500')"
                                                :style="{ width: `${item.score}%` }"
                                            ></div>
                                        </div>
                                        <span class="font-bold text-slate-800">{{ item.score }}%</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border"
                                        :class="{
                                            'bg-emerald-50 text-emerald-700 border-emerald-200': item.status === 'Selesai',
                                            'bg-blue-50 text-blue-700 border-blue-200': item.status === 'Dalam Proses',
                                            'bg-rose-50 text-rose-700 border-rose-200': item.status === 'Temuan Mayor'
                                        }"
                                    >
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <button
                                        @click="deleteItem(item.id)"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                        title="Hapus baris"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="filteredAudits.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Tidak ada data audit yang cocok dengan pencarian "{{ searchQuery }}".
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
