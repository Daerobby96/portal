<template>
    <AuthenticatedLayout title="Manajemen Rapat & RTM">
        <div class="space-y-6">
            <!-- Top Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span v-if="activeFilterLabel" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-200">
                            {{ activeFilterLabel }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ dynamicPageTitle }}</h1>
                    <p class="text-xs text-slate-500 mt-1">{{ dynamicPageDescription }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        v-if="hasActiveFilter"
                        @click="resetFilters"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer"
                    >
                        <i class="bi bi-x-circle"></i>
                        <span>Reset Filter</span>
                    </button>
                    <Link
                        href="/rapat/create"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat Rapat Baru</span>
                    </Link>
                </div>
            </div>

            <!-- Active Filter Notice Banner -->
            <div v-if="hasActiveFilter" class="p-3.5 rounded-2xl bg-teal-50/70 border border-teal-200/80 flex items-center justify-between flex-wrap gap-2 text-xs">
                <div class="flex items-center gap-2 text-teal-900 font-semibold flex-wrap">
                    <i class="bi bi-funnel-fill text-teal-600"></i>
                    <span>Filter Aktif:</span>
                    <span v-if="filters.status" class="px-2 py-0.5 rounded-md bg-white text-teal-800 text-[11px] font-bold border border-teal-200 shadow-2xs">
                        Status: {{ filters.status }}
                    </span>
                    <span v-if="filters.jenis" class="px-2 py-0.5 rounded-md bg-white text-teal-800 text-[11px] font-bold border border-teal-200 shadow-2xs">
                        Kategori: {{ filters.jenis }}
                    </span>
                    <span v-if="filters.search" class="px-2 py-0.5 rounded-md bg-white text-teal-800 text-[11px] font-bold border border-teal-200 shadow-2xs">
                        Pencarian: "{{ filters.search }}"
                    </span>
                </div>
                <button
                    @click="resetFilters"
                    type="button"
                    class="text-xs font-bold text-teal-700 hover:text-teal-900 hover:underline cursor-pointer"
                >
                    Tampilkan Semua Data
                </button>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                            <i class="bi bi-calendar2-week-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Total</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Semua Rapat</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Terjadwal</span>
                    </div>
                    <div class="text-2xl font-black text-blue-700">{{ stats.terjadwal }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Siap Dilaksanakan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Aktif</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700 flex items-center gap-1.5">
                        <span>{{ stats.berlangsung }}</span>
                        <span v-if="stats.berlangsung > 0" class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    </div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Sedang Berlangsung</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Selesai</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.selesai }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Notulensi Siap</div>
                </div>

                <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Draft</span>
                    </div>
                    <div class="text-2xl font-black text-slate-700">{{ stats.draft }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Belum Dijadwalkan</div>
                </div>
            </div>

            <!-- Main 2-Column Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Filters & Meetings Table (Span 2) -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Filters Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <!-- Periode Selector -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Periode</label>
                                <select
                                    v-model="filters.periode_id"
                                    @change="applyFilters"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition bg-white"
                                >
                                    <option v-for="p in periodes" :key="p.id" :value="p.id">
                                        {{ p.nama }} {{ p.is_aktif ? '★' : '' }}
                                    </option>
                                </select>
                            </div>

                            <!-- Jenis Rapat -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Jenis Rapat</label>
                                <select
                                    v-model="filters.jenis"
                                    @change="applyFilters"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition bg-white"
                                >
                                    <option value="">Semua Jenis</option>
                                    <option v-for="(lbl, key) in jenisOptions" :key="key" :value="key">
                                        {{ lbl }}
                                    </option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Status</label>
                                <select
                                    v-model="filters.status"
                                    @change="applyFilters"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition bg-white"
                                >
                                    <option value="">Semua Status</option>
                                    <option v-for="(lbl, key) in statusOptions" :key="key" :value="key">
                                        {{ lbl }}
                                    </option>
                                </select>
                            </div>

                            <!-- Reset Button -->
                            <div class="flex items-end">
                                <button
                                    @click="resetFilters"
                                    type="button"
                                    class="w-full py-2 px-3 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition flex items-center justify-center gap-1.5"
                                >
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    <span>Reset Filter</span>
                                </button>
                            </div>

                            <!-- Search Input -->
                            <div class="sm:col-span-2 md:col-span-4">
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                                    <input
                                        v-model="filters.search"
                                        @keyup.enter="applyFilters"
                                        type="search"
                                        placeholder="Cari judul rapat, tempat, atau deskripsi..."
                                        class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition bg-white"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                        <th class="px-5 py-3.5 text-left">Judul & Tempat</th>
                                        <th class="px-4 py-3.5 text-left">Jenis</th>
                                        <th class="px-4 py-3.5 text-left">Jadwal</th>
                                        <th class="px-4 py-3.5 text-left">Status</th>
                                        <th class="px-4 py-3.5 text-center">Peserta/Agenda</th>
                                        <th class="px-4 py-3.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr v-if="rapats.data.length === 0">
                                        <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                                <i class="bi bi-calendar-x"></i>
                                            </div>
                                            <p class="font-bold text-slate-600">Tidak ada rapat ditemukan</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Silakan sesuaikan filter atau buat rapat baru.</p>
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="r in rapats.data"
                                        :key="r.id"
                                        class="hover:bg-slate-50/70 transition group"
                                    >
                                        <!-- Judul & Tempat -->
                                        <td class="px-5 py-3.5 max-w-xs">
                                            <Link :href="`/rapat/${r.id}`" class="font-black text-slate-800 hover:text-teal-600 transition block truncate leading-snug">
                                                {{ r.judul }}
                                            </Link>
                                            <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-1">
                                                <span class="flex items-center gap-1"><i class="bi bi-geo-alt"></i>{{ r.tempat }}</span>
                                                <span v-if="r.creator_name" class="flex items-center gap-1">· {{ r.creator_name }}</span>
                                            </div>
                                        </td>

                                        <!-- Jenis Badge -->
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="jenisBadgeClass(r.jenis)">
                                                {{ r.jenis }}
                                            </span>
                                        </td>

                                        <!-- Jadwal -->
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <p class="font-semibold text-slate-700 text-[11px]">{{ r.tanggal_display }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                                {{ r.waktu_mulai }} - {{ r.waktu_selesai }} WIB
                                            </p>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusBadgeClass(r.status)">
                                                {{ statusOptions[r.status] || r.status }}
                                            </span>
                                        </td>

                                        <!-- Peserta / Agenda Count -->
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2 text-[10px] text-slate-500 font-semibold">
                                                <span title="Jumlah Peserta" class="flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-md">
                                                    <i class="bi bi-people-fill text-teal-600"></i>{{ r.total_peserta }}
                                                </span>
                                                <span title="Susunan Agenda" class="flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-md">
                                                    <i class="bi bi-list-check text-blue-600"></i>{{ r.total_agenda }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <Link
                                                    :href="`/rapat/${r.id}`"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-50 transition"
                                                    title="Lihat Detail & Notulensi"
                                                >
                                                    <i class="bi bi-eye text-sm"></i>
                                                </Link>
                                                <a
                                                    :href="`/rapat/${r.id}/export-pdf`"
                                                    target="_blank"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                                    title="Export PDF Notulensi"
                                                >
                                                    <i class="bi bi-file-earmark-pdf text-sm"></i>
                                                </a>
                                                <Link
                                                    v-if="r.status !== 'selesai' && r.status !== 'dibatalkan'"
                                                    :href="`/rapat/${r.id}/edit`"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                                    title="Edit Rapat"
                                                >
                                                    <i class="bi bi-pencil text-sm"></i>
                                                </Link>
                                                <button
                                                    @click="confirmDelete(r)"
                                                    type="button"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer"
                                                    title="Hapus Rapat"
                                                >
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="rapats.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                            <span>Menampilkan {{ rapats.from }}–{{ rapats.to }} dari {{ rapats.total }} rapat</span>
                            <div class="flex gap-1">
                                <Link
                                    v-for="link in rapats.links"
                                    :key="link.label"
                                    :href="link.url || '#'"
                                    class="px-2.5 py-1 rounded-lg font-semibold transition"
                                    :class="link.active ? 'bg-teal-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Chart, Upcoming, Action Items (Span 1) -->
                <div class="space-y-5">
                    <!-- Volume Chart Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider">Tren Volume Rapat</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">6 bulan terakhir</p>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        </div>
                        <div class="relative h-40">
                            <svg class="w-full h-full" viewBox="0 0 300 120" preserveAspectRatio="none">
                                <line x1="0" y1="30" x2="300" y2="30" stroke="#f1f5f9" stroke-width="1"/>
                                <line x1="0" y1="60" x2="300" y2="60" stroke="#f1f5f9" stroke-width="1"/>
                                <line x1="0" y1="90" x2="300" y2="90" stroke="#f1f5f9" stroke-width="1"/>
                                <polyline
                                    :points="chartPoints"
                                    fill="none"
                                    stroke="#0d9488"
                                    stroke-width="2.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <circle
                                    v-for="(pt, i) in chartDots"
                                    :key="'dot-'+i"
                                    :cx="pt.x"
                                    :cy="pt.y"
                                    r="3.5"
                                    fill="#0d9488"
                                    stroke="#ffffff"
                                    stroke-width="2"
                                />
                            </svg>
                            <div class="absolute bottom-0 inset-x-0 flex justify-between px-1">
                                <span v-for="l in chartData.labels" :key="l" class="text-[9px] font-semibold text-slate-400">
                                    {{ l.split(' ')[0] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Meetings Widget -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-clock-history text-teal-600"></i>
                                <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider">Rapat Mendatang</h3>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase bg-teal-50 text-teal-700">30 Hari</span>
                        </div>

                        <div v-if="mendatang.length === 0" class="text-center py-6 text-slate-400 text-xs">
                            <i class="bi bi-calendar2-check text-2xl text-slate-200 block mb-1"></i>
                            <p class="text-[11px]">Tidak ada agenda rapat dalam 30 hari ke depan</p>
                        </div>
                        <div v-else class="space-y-2.5">
                            <Link
                                v-for="m in mendatang"
                                :key="m.id"
                                :href="`/rapat/${m.id}`"
                                class="block p-3 rounded-xl border border-slate-100 hover:border-teal-200 hover:bg-teal-50/40 transition group"
                            >
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-teal-700 transition line-clamp-1">
                                        {{ m.judul }}
                                    </span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold shrink-0" :class="jenisBadgeClass(m.jenis)">
                                        {{ m.jenis }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-slate-400">
                                    <span class="flex items-center gap-1 font-semibold text-slate-600">
                                        <i class="bi bi-calendar-event text-teal-600"></i>{{ m.tanggal_display }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-geo-alt"></i>{{ m.tempat }}
                                    </span>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Overdue Action Items Alert Card -->
                    <div v-if="overdueActions.length > 0" class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-xs">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
                            <h3 class="font-black text-rose-900 text-xs uppercase tracking-wider">Tindak Lanjut Overdue!</h3>
                        </div>
                        <p class="text-[11px] text-rose-700 mb-3">Ada {{ overdueActions.length }} komitmen rapat yang melewati deadline:</p>
                        <div class="space-y-2">
                            <Link
                                v-for="tl in overdueActions"
                                :key="tl.id"
                                :href="`/rapat/${tl.rapat_id}`"
                                class="block p-2.5 rounded-xl bg-white border border-rose-200 hover:border-rose-300 transition group"
                            >
                                <p class="text-xs font-bold text-slate-800 group-hover:text-rose-600 line-clamp-1">{{ tl.deskripsi }}</p>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                                    <span>PIC: <b>{{ tl.pic_name || '-' }}</b></span>
                                    <span class="text-rose-600 font-bold flex items-center gap-1">
                                        <i class="bi bi-clock-fill"></i>{{ tl.deadline }}
                                    </span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Rapat?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Rapat "<span class="font-bold text-slate-800">{{ deleteTarget.judul }}</span>" dan seluruh notulensi, agenda & lampiran akan dihapus permanen.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="deleteTarget = null"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
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
import { ref, reactive, computed, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rapats: Object,
    periodes: Array,
    periodeId: [Number, String],
    stats: Object,
    mendatang: Array,
    overdueActions: Array,
    chartData: Object,
    filters: Object,
    jenisOptions: Object,
    statusOptions: Object,
});

const filters = reactive({
    periode_id: props.periodeId || '',
    jenis: props.filters?.jenis || '',
    status: props.filters?.status || '',
    search: props.filters?.search || '',
    dari: props.filters?.dari || '',
    sampai: props.filters?.sampai || '',
});

watch(() => props.filters, (newFilters) => {
    filters.jenis = newFilters?.jenis || '';
    filters.status = newFilters?.status || '';
    filters.search = newFilters?.search || '';
    filters.dari = newFilters?.dari || '';
    filters.sampai = newFilters?.sampai || '';
}, { deep: true });

watch(() => props.periodeId, (newPid) => {
    if (newPid) filters.periode_id = newPid;
});

const hasActiveFilter = computed(() => {
    return !!(filters.jenis || filters.status || filters.search || filters.dari || filters.sampai);
});

const activeFilterLabel = computed(() => {
    if (filters.jenis === 'RTM') return 'Rapat Tinjauan Manajemen (RTM)';
    if (filters.jenis) return `Kategori: ${filters.jenis}`;
    if (filters.status === 'terjadwal') return 'Rapat Terjadwal';
    if (filters.status === 'berlangsung') return 'Sedang Berlangsung';
    if (filters.status === 'selesai') return 'Arsip Rapat Selesai';
    if (filters.status === 'draft') return 'Draft Rapat';
    return null;
});

const dynamicPageTitle = computed(() => {
    if (filters.jenis === 'RTM') return 'Rapat Tinjauan Manajemen (RTM SPMI)';
    if (filters.status === 'terjadwal') return 'Jadwal Rapat Terjadwal';
    if (filters.status === 'berlangsung') return 'Rapat Sedang Berlangsung';
    if (filters.status === 'selesai') return 'Arsip Notulensi & Rapat Selesai';
    if (filters.status === 'draft') return 'Konsep & Draft Rapat';
    if (filters.jenis) return `Daftar Rapat: ${filters.jenis}`;
    return 'Manajemen Rapat & Notulensi';
});

const dynamicPageDescription = computed(() => {
    if (filters.jenis === 'RTM') return 'Tinjauan efektivitas sistem penjaminan mutu internal (SPMI) dan evaluasi capaian standar institusi.';
    if (filters.status === 'terjadwal') return 'Daftar agenda rapat yang telah dijadwalkan dan siap untuk diselenggarakan.';
    if (filters.status === 'berlangsung') return 'Sesi rapat yang sedang aktif saat ini beserta pencatatan notulensi dan daftar hadir.';
    if (filters.status === 'selesai') return 'Dokumentasi risalah rapat lengkap, notulensi butir agenda, dan komitmen tindak lanjut.';
    if (filters.status === 'draft') return 'Konsep agenda rapat yang belum dipublikasikan atau dijadwalkan.';
    return 'Jadwal rapat pimpinan/prodi, daftar hadir, notulensi digital & tindak lanjut.';
});

const deleteTarget = ref(null);

function applyFilters() {
    router.get('/rapat', filters, { preserveState: true, replace: true });
}

function resetFilters() {
    filters.jenis = '';
    filters.status = '';
    filters.search = '';
    filters.dari = '';
    filters.sampai = '';
    applyFilters();
}

function confirmDelete(r) {
    deleteTarget.value = r;
}

function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/rapat/${deleteTarget.value.id}`, {
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function jenisBadgeClass(jenis) {
    const map = {
        'RTM': 'bg-indigo-50 text-indigo-700 border border-indigo-200/60',
        'Koordinasi': 'bg-teal-50 text-teal-700 border border-teal-200/60',
        'Evaluasi': 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'Audit': 'bg-rose-50 text-rose-700 border border-rose-200/60',
        'Khusus': 'bg-purple-50 text-purple-700 border border-purple-200/60',
    };
    return map[jenis] || 'bg-slate-100 text-slate-600';
}

function statusBadgeClass(status) {
    const map = {
        'draft': 'bg-slate-100 text-slate-600 border border-slate-200',
        'terjadwal': 'bg-blue-50 text-blue-700 border border-blue-200',
        'berlangsung': 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse',
        'selesai': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'dibatalkan': 'bg-rose-50 text-rose-700 border border-rose-200',
    };
    return map[status] || 'bg-slate-100 text-slate-600';
}

// Chart SVG helpers
const maxVal = computed(() => {
    const vals = props.chartData?.values || [];
    return Math.max(...vals, 1);
});

const chartDots = computed(() => {
    const vals = props.chartData?.values || [0, 0, 0, 0, 0, 0];
    const w = 300, h = 100, pad = 20;
    return vals.map((v, i) => {
        const x = pad + (i / Math.max(vals.length - 1, 1)) * (w - pad * 2);
        const y = h - pad - (v / maxVal.value) * (h - pad * 2);
        return { x: isNaN(x) ? pad : x, y: isNaN(y) ? h - pad : y };
    });
});

const chartPoints = computed(() => chartDots.value.map(p => `${p.x},${p.y}`).join(' '));
</script>
