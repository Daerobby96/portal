<template>
    <AuthenticatedLayout title="Tracer Study & Alumni">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tracer Study & Pelacakan Alumni</h1>
                    <p class="text-xs text-slate-500 mt-1">Pangkalan data penyerapan lulusan, masa tunggu kerja, pendapatan, dan keselarasan kurikulum PDDIKTI.</p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">
                    <a
                        href="/tracer-study/template"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-download text-slate-500"></i>
                        <span>Template Excel</span>
                    </a>
                    <button
                        @click="showImportModal = true"
                        type="button"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-emerald-200 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition shadow-xs cursor-pointer"
                    >
                        <i class="bi bi-cloud-arrow-up-fill text-emerald-600"></i>
                        <span>Import Excel</span>
                    </button>
                    <button
                        @click="openCreateModal"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Alumni</span>
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Responden</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900">{{ stats.total }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Total Alumni Terdata</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-emerald-600">Bekerja</span>
                    </div>
                    <div class="text-2xl font-black text-emerald-700">{{ stats.bekerja_persen }}%</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">{{ stats.bekerja }} Orang Lulusan</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                            <i class="bi bi-shop"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-purple-600">Wirausaha / Studi</span>
                    </div>
                    <div class="text-2xl font-black text-purple-700">{{ stats.wirausaha + stats.lanjut_studi }}</div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">{{ stats.wirausaha }} Usaha · {{ stats.lanjut_studi }} Studi</div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-amber-600">Waktu Tunggu</span>
                    </div>
                    <div class="text-2xl font-black text-amber-700">{{ stats.avg_tunggu }} <span class="text-xs font-bold text-amber-600">Bulan</span></div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Rata-rata Masa Tunggu</div>
                </div>

                <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <span class="text-[10px] font-extrabold uppercase text-blue-600">Rerata Gaji</span>
                    </div>
                    <div class="text-lg font-black text-blue-700 truncate" :title="formatRupiah(stats.avg_gaji)">
                        {{ formatRupiah(stats.avg_gaji) }}
                    </div>
                    <div class="text-[11px] font-semibold text-slate-500 mt-0.5">Pendapatan Per Bulan</div>
                </div>
            </div>

            <!-- AI Insight & PPEPP Integration Row -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <!-- AI Smart Insight Card -->
                <div class="lg:col-span-7 rounded-3xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-950 p-6 text-white shadow-md relative overflow-hidden flex flex-col justify-between">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-indigo-300">
                                <i class="bi bi-robot text-base"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm tracking-tight text-white">AI Smart Insight & Executive Summary</h3>
                                <p class="text-[10px] text-indigo-200">Analisa cerdas performa keterserapan alumni di dunia kerja</p>
                            </div>
                        </div>
                        <div class="text-xs text-indigo-100 leading-relaxed space-y-2 prose-invert prose-strong:text-white prose-strong:font-bold" v-html="aiInsight"></div>
                    </div>
                    <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-indigo-300 relative z-10">
                        <span class="flex items-center gap-1.5">
                            <i class="bi bi-stars text-amber-400"></i>
                            <span>Didukung Model AI Evaluasi Mutu Institusi</span>
                        </span>
                        <span class="font-mono text-[10px]">{{ stats.total }} Responden Terkumpul</span>
                    </div>
                </div>

                <!-- PPEPP Integration Card -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <h3 class="font-bold text-slate-900 text-xs">Integrasi Indikator SPMI (PPEPP)</h3>
                            </div>
                            <form @submit.prevent="syncPpepp">
                                <button
                                    type="submit"
                                    :disabled="syncing"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition cursor-pointer disabled:opacity-50"
                                >
                                    <i v-if="syncing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <i v-else class="bi bi-arrow-repeat"></i>
                                    <span>Sinkronkan</span>
                                </button>
                            </form>
                        </div>

                        <div v-if="ppeppData && ppeppData.length > 0" class="space-y-3">
                            <div v-for="item in ppeppData" :key="item.nama" class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-800 text-[11px] truncate max-w-[200px]">{{ item.nama }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase" :class="item.status === 'Tercapai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'">
                                        {{ item.status }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-slate-500">
                                    <span>Capaian: <strong class="text-slate-800">{{ item.capaian }}</strong> / Target: {{ item.target }} {{ item.satuan }}</span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div
                                        class="h-full rounded-full transition-all duration-500"
                                        :class="item.status === 'Tercapai' ? 'bg-emerald-500' : 'bg-amber-500'"
                                        :style="{ width: `${Math.min(100, (item.capaian / Math.max(1, item.target)) * 100)}%` }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="py-6 text-center text-slate-400 text-xs">
                            <i class="bi bi-info-circle text-lg mb-1 block"></i>
                            <span>Indikator mutu lulusan belum disinkronkan.</span>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 text-[10px] text-slate-400">
                        Otomatis memperbarui IKU 1 pada Siklus SPMI PPEPP
                    </div>
                </div>
            </div>

            <!-- Distribution Breakdown Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs">
                <h3 class="font-bold text-slate-900 text-xs mb-3 flex items-center gap-2">
                    <i class="bi bi-pie-chart-fill text-emerald-600"></i>
                    <span>Distribusi Status Karir Lulusan</span>
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div v-for="(count, label) in statusDist" :key="label" class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase truncate" :title="label">{{ label }}</p>
                        <div class="flex items-baseline justify-between mt-1">
                            <span class="text-lg font-black text-slate-900">{{ count }}</span>
                            <span class="text-[10px] font-bold text-slate-500">
                                {{ stats.total > 0 ? Math.round((count / stats.total) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                            <input
                                v-model="filters.search"
                                @keyup.enter="applyFilters"
                                type="search"
                                placeholder="Cari nama alumni, NIM, perusahaan..."
                                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500/30 outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <select
                            v-model="filters.status_kerja"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Status Kerja</option>
                            <option value="Bekerja">Bekerja (Full/Part Time)</option>
                            <option value="Wirausaha">Wirausaha / Mandiri</option>
                            <option value="Melanjutkan Pendidikan">Melanjutkan Studi</option>
                            <option value="Belum Memungkinkan Bekerja">Belum Bekerja</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.prodi"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Program Studi</option>
                            <option v-for="pr in prodis" :key="pr" :value="pr">{{ pr }}</option>
                        </select>
                    </div>

                    <div>
                        <select
                            v-model="filters.tahun_lulus"
                            @change="applyFilters"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-emerald-500/30 outline-none bg-white"
                        >
                            <option value="">Semua Tahun Lulus</option>
                            <option v-for="t in tahunLulusList" :key="t" :value="t">Lulusan {{ t }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                <th class="px-5 py-3.5 text-left">NIM & Nama Alumni</th>
                                <th class="px-4 py-3.5 text-left">Prodi & Lulus</th>
                                <th class="px-4 py-3.5 text-left">Status Kerja</th>
                                <th class="px-4 py-3.5 text-left">Perusahaan & Jabatan</th>
                                <th class="px-4 py-3.5 text-left">Gaji & Masa Tunggu</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="tracerData.data.length === 0">
                                <td colspan="6" class="px-5 py-14 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl mx-auto mb-2.5">
                                        <i class="bi bi-person-x"></i>
                                    </div>
                                    <p class="font-bold text-slate-600">Belum ada data tracer study alumni</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Silakan import data Excel atau tambah manual</p>
                                </td>
                            </tr>
                            <tr
                                v-for="a in tracerData.data"
                                :key="a.id"
                                class="hover:bg-slate-50/70 transition"
                            >
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-black text-slate-900 block">{{ a.nama }}</span>
                                    <span class="font-mono text-[10px] text-slate-400 block">NIM: {{ a.nim }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 block">{{ a.prodi || '-' }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 text-slate-600 font-mono">
                                        Lulus {{ a.tahun_lulus }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" :class="statusKerjaBadgeClass(a.status_kerja)">
                                        {{ a.status_kerja || '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 max-w-xs">
                                    <span class="font-bold text-slate-800 block truncate" :title="a.perusahaan">{{ a.perusahaan || '-' }}</span>
                                    <span v-if="a.jabatan" class="text-[10px] text-slate-500 block truncate">{{ a.jabatan }}</span>
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-emerald-700 block">{{ formatRupiah(a.gaji) }}</span>
                                    <span v-if="a.waktu_tunggu_bulan !== null" class="text-[10px] text-slate-400 block">
                                        Tunggu: {{ a.waktu_tunggu_bulan }} Bulan
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button
                                            @click="openDetailModal(a)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition cursor-pointer"
                                            title="Lihat Detail"
                                        >
                                            <i class="bi bi-eye text-sm"></i>
                                        </button>
                                        <button
                                            @click="openEditModal(a)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil text-sm"></i>
                                        </button>
                                        <button
                                            @click="confirmDelete(a)"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="tracerData.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>Menampilkan {{ tracerData.from }}–{{ tracerData.to }} dari {{ tracerData.total }} data</span>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in tracerData.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            class="px-2.5 py-1 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-emerald-600 text-white' : 'hover:bg-slate-100 text-slate-600'"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Detail Modal -->
            <Teleport to="body">
                <div v-if="selectedDetail" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-lg space-y-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="font-black text-slate-900 text-sm">Profil & Rekam Jejak Alumni</h3>
                                <p class="text-[11px] text-slate-400 font-mono">{{ selectedDetail.nim }} — {{ selectedDetail.nama }}</p>
                            </div>
                            <button @click="selectedDetail = null" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <!-- Identitas -->
                        <div class="bg-slate-50 rounded-2xl p-3.5 space-y-2">
                            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Identitas Akademik</span>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Program Studi</span>
                                    <strong class="text-slate-800">{{ selectedDetail.prodi || '-' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Tahun Lulus</span>
                                    <strong class="text-slate-800 font-mono">{{ selectedDetail.tahun_lulus }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">No. Telepon / WA</span>
                                    <span class="text-slate-700 font-mono">{{ selectedDetail.telepon || '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Email</span>
                                    <span class="text-slate-700 truncate block">{{ selectedDetail.email || '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pekerjaan -->
                        <div class="bg-slate-50 rounded-2xl p-3.5 space-y-2">
                            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Status & Informasi Pekerjaan</span>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Status Karir</span>
                                    <strong class="text-emerald-700">{{ selectedDetail.status_kerja || '-' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Tingkat Instansi</span>
                                    <span class="text-slate-800">{{ selectedDetail.tingkat_instansi || '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Perusahaan / Instansi</span>
                                    <strong class="text-slate-800">{{ selectedDetail.perusahaan || '-' }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Jabatan / Posisi</span>
                                    <span class="text-slate-700">{{ selectedDetail.jabatan || '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Gaji / Pendapatan</span>
                                    <strong class="text-emerald-700">{{ formatRupiah(selectedDetail.gaji) }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Waktu Tunggu Kerja</span>
                                    <strong class="text-slate-800 font-mono">{{ selectedDetail.waktu_tunggu_bulan || 0 }} Bulan</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Keselarasan -->
                        <div class="bg-slate-50 rounded-2xl p-3.5 space-y-2">
                            <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider block">Keselarasan Bidang Kerja</span>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Keselarasan Horisontal</span>
                                    <span class="text-slate-800 font-semibold">{{ selectedDetail.keselarasan_horisontal || '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[11px] block">Keselarasan Vertikal</span>
                                    <span class="text-slate-800 font-semibold">{{ selectedDetail.keselarasan_vertikal || '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button
                                @click="selectedDetail = null"
                                type="button"
                                class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </Teleport>

            <!-- Form Create / Edit Modal -->
            <Teleport to="body">
                <div v-if="showFormModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-lg space-y-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">
                                {{ isEditing ? 'Edit Data Alumni' : 'Tambah Data Alumni' }}
                            </h3>
                            <button @click="showFormModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">NIM <span class="text-rose-500">*</span></label>
                                    <input v-model="form.nim" type="text" required placeholder="Contoh: 2021001" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30 font-mono" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input v-model="form.nama" type="text" required placeholder="Nama lengkap..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30 font-semibold" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi</label>
                                    <select v-model="form.prodi" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30 bg-white">
                                        <option value="">-- Pilih Prodi --</option>
                                        <option v-for="pr in prodis" :key="pr" :value="pr">{{ pr }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Lulus <span class="text-rose-500">*</span></label>
                                    <input v-model="form.tahun_lulus" type="number" required min="1990" :max="currentYear + 1" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30 font-mono font-semibold" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                                    <input v-model="form.telepon" type="text" placeholder="08..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                                    <input v-model="form.email" type="email" placeholder="alumni@email.com" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Status Pekerjaan <span class="text-rose-500">*</span></label>
                                <select v-model="form.status_kerja" required class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30 bg-white font-semibold">
                                    <option value="Bekerja (Full Time)">Bekerja (Full Time)</option>
                                    <option value="Bekerja (Part Time)">Bekerja (Part Time)</option>
                                    <option value="Wiraswasta / Wirausaha">Wiraswasta / Wirausaha</option>
                                    <option value="Melanjutkan Pendidikan">Melanjutkan Pendidikan</option>
                                    <option value="Tidak Kerja tetapi sedang mencari kerja">Mencari Kerja</option>
                                    <option value="Belum Memungkinkan Bekerja">Belum Bekerja</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan / Instansi</label>
                                    <input v-model="form.perusahaan" type="text" placeholder="PT / CV / Lembaga..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Profesi</label>
                                    <input v-model="form.jabatan" type="text" placeholder="Staff / Programmer..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30" />
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Gaji (Rp)</label>
                                    <input v-model="form.gaji" type="number" min="0" placeholder="5000000" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Tunggu (Bln)</label>
                                    <input v-model="form.waktu_tunggu_bulan" type="number" min="0" max="60" placeholder="3" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Instansi</label>
                                    <select v-model="form.tingkat_instansi" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-500/30 bg-white">
                                        <option value="">-- Tingkat --</option>
                                        <option value="Lokal / Wilayah">Lokal / Wilayah</option>
                                        <option value="Nasional">Nasional</option>
                                        <option value="Multinasional / Internasional">Internasional</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                <button
                                    @click="showFormModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="submittingForm"
                                    class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <i v-if="submittingForm" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>{{ isEditing ? 'Simpan Perubahan' : 'Simpan Data Alumni' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Import Modal -->
            <Teleport to="body">
                <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Import File Tracer Study</h3>
                            <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitImport" class="space-y-4">
                            <div class="border-2 border-dashed border-slate-200 hover:border-emerald-400 rounded-2xl p-6 text-center transition bg-slate-50/50">
                                <i class="bi bi-file-earmark-spreadsheet-fill text-3xl text-emerald-600 mb-2 block"></i>
                                <p class="text-xs font-bold text-slate-700">Pilih Berkas Excel PDDIKTI</p>
                                <p class="text-[10px] text-slate-400 mb-3">Format .xlsx, .xls, .csv (Maks 10MB)</p>
                                <input
                                    type="file"
                                    required
                                    accept=".xlsx,.xls,.csv"
                                    @change="handleImportFile"
                                    class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"
                                />
                            </div>

                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-[11px] flex items-start gap-2">
                                <i class="bi bi-info-circle-fill text-blue-600 shrink-0 mt-0.5"></i>
                                <span>Sistem membaca kode instrumen PDDIKTI seperti f8 (status), f502 (gaji), f505 (waktu tunggu), dan identitas alumni.</span>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showImportModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="importing"
                                    class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5 cursor-pointer"
                                >
                                    <i v-if="importing" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Unggah & Import</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- Delete Confirmation Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-sm">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
                            <i class="bi bi-trash3-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center text-base mb-1">Hapus Data Alumni?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5 leading-relaxed">
                            Data alumni "<span class="font-bold text-slate-800">{{ deleteTarget.nama }}</span>" (NIM: {{ deleteTarget.nim }}) akan dihapus permanen.
                        </p>
                        <div class="flex gap-3">
                            <button
                                @click="deleteTarget = null"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                            >
                                Batal
                            </button>
                            <button
                                @click="proceedDelete"
                                type="button"
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm cursor-pointer"
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
    tracerData: Object,
    stats: Object,
    statusDist: Object,
    aiInsight: String,
    ppeppData: Array,
    prodis: Array,
    tahunLulusList: Array,
    filters: Object,
});

const currentYear = new Date().getFullYear();

const filters = reactive({
    search: props.filters?.search || '',
    status_kerja: props.filters?.status_kerja || '',
    prodi: props.filters?.prodi || '',
    tahun_lulus: props.filters?.tahun_lulus || '',
});

function applyFilters() {
    router.get('/tracer-study', filters, { preserveState: true, replace: true });
}

// PPEPP Sync
const syncing = ref(false);
function syncPpepp() {
    syncing.value = true;
    router.post('/tracer-study/sync-ppepp', {}, {
        preserveScroll: true,
        onFinish: () => {
            syncing.value = false;
        },
    });
}

// Detail Modal
const selectedDetail = ref(null);
function openDetailModal(a) {
    selectedDetail.value = a;
}

// Form Modal (Create / Edit)
const showFormModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const submittingForm = ref(false);

const form = reactive({
    nim: '',
    nama: '',
    prodi: '',
    tahun_lulus: currentYear,
    telepon: '',
    email: '',
    status_kerja: 'Bekerja (Full Time)',
    perusahaan: '',
    jabatan: '',
    gaji: '',
    waktu_tunggu_bulan: '',
    tingkat_instansi: 'Nasional',
    keselarasan_horisontal: 'Erat',
    keselarasan_vertikal: 'Sama',
});

function openCreateModal() {
    isEditing.value = false;
    editingId.value = null;
    form.nim = '';
    form.nama = '';
    form.prodi = props.prodis?.[0] || '';
    form.tahun_lulus = currentYear;
    form.telepon = '';
    form.email = '';
    form.status_kerja = 'Bekerja (Full Time)';
    form.perusahaan = '';
    form.jabatan = '';
    form.gaji = '';
    form.waktu_tunggu_bulan = '';
    form.tingkat_instansi = 'Nasional';
    showFormModal.value = true;
}

function openEditModal(a) {
    isEditing.value = true;
    editingId.value = a.id;
    form.nim = a.nim || '';
    form.nama = a.nama || '';
    form.prodi = a.prodi || '';
    form.tahun_lulus = a.tahun_lulus || currentYear;
    form.telepon = a.telepon || '';
    form.email = a.email || '';
    form.status_kerja = a.status_kerja || 'Bekerja (Full Time)';
    form.perusahaan = a.perusahaan || '';
    form.jabatan = a.jabatan || '';
    form.gaji = a.gaji || '';
    form.waktu_tunggu_bulan = a.waktu_tunggu_bulan || '';
    form.tingkat_instansi = a.tingkat_instansi || 'Nasional';
    showFormModal.value = true;
}

function submitForm() {
    submittingForm.value = true;
    if (isEditing.value) {
        router.put(`/tracer-study/${editingId.value}`, form, {
            preserveScroll: true,
            onSuccess: () => {
                showFormModal.value = false;
            },
            onFinish: () => {
                submittingForm.value = false;
            },
        });
    } else {
        router.post('/tracer-study', form, {
            preserveScroll: true,
            onSuccess: () => {
                showFormModal.value = false;
            },
            onFinish: () => {
                submittingForm.value = false;
            },
        });
    }
}

// Import Modal
const showImportModal = ref(false);
const importFile = ref(null);
const importing = ref(false);

function handleImportFile(e) {
    importFile.value = e.target.files[0];
}

function submitImport() {
    if (!importFile.value) return;
    importing.value = true;
    const formData = new FormData();
    formData.append('file', importFile.value);

    router.post('/tracer-study/import', formData, {
        forceFormData: true,
        onSuccess: () => {
            showImportModal.value = false;
            importFile.value = null;
        },
        onFinish: () => {
            importing.value = false;
        },
    });
}

// Delete Confirmation
const deleteTarget = ref(null);
function confirmDelete(a) {
    deleteTarget.value = a;
}
function proceedDelete() {
    if (!deleteTarget.value) return;
    router.delete(`/tracer-study/${deleteTarget.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            deleteTarget.value = null;
        },
    });
}

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
}

function statusKerjaBadgeClass(status) {
    if (!status) return 'bg-slate-100 text-slate-600';
    const s = status.toLowerCase();
    if (s.startsWith('bekerja') || s === '1') {
        return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    }
    if (s.includes('wirausaha') || s.includes('wiraswasta') || s === '2') {
        return 'bg-purple-50 text-purple-700 border border-purple-200';
    }
    if (s.includes('lanjut') || s.includes('melanjutkan') || s === '3') {
        return 'bg-blue-50 text-blue-700 border border-blue-200';
    }
    return 'bg-amber-50 text-amber-700 border border-amber-200';
}
</script>
