<template>
    <AuthenticatedLayout :title="`Rapat: ${rapat.judul}`">
        <div class="space-y-6">
            <!-- Hero Header Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs">
                <div class="flex items-start justify-between flex-wrap gap-4 pb-5 border-b border-slate-100">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex items-center gap-2 flex-wrap text-[11px] text-slate-500">
                            <Link href="/rapat" class="hover:text-teal-600 font-bold flex items-center gap-1 transition">
                                <i class="bi bi-arrow-left"></i>
                                <span>Daftar Rapat</span>
                            </Link>
                            <span>/</span>
                            <span class="px-2 py-0.5 rounded-full font-bold uppercase tracking-wider" :class="jenisBadgeClass(rapat.jenis)">
                                {{ rapat.jenis }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                {{ rapat.periode_nama }}
                            </span>
                        </div>

                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
                            {{ rapat.judul }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-y-1.5 gap-x-4 text-xs text-slate-500 font-medium">
                            <span class="flex items-center gap-1.5 font-semibold text-slate-700">
                                <i class="bi bi-calendar-event text-teal-600"></i>
                                {{ rapat.tanggal_display }}
                            </span>
                            <span class="flex items-center gap-1.5 font-mono text-slate-600">
                                <i class="bi bi-clock text-teal-600"></i>
                                {{ rapat.waktu_mulai }} - {{ rapat.waktu_selesai }} WIB
                            </span>
                            <span class="flex items-center gap-1.5 text-slate-700">
                                <i class="bi bi-geo-alt-fill text-rose-500"></i>
                                {{ rapat.tempat }}
                            </span>
                        </div>
                    </div>

                    <!-- Right Side Actions & Status -->
                    <div class="flex flex-col items-end gap-3 shrink-0">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" :class="statusBadgeClass(rapat.status)">
                                {{ rapat.status }}
                            </span>
                            <button
                                v-if="canEdit"
                                @click="openStatusModal"
                                type="button"
                                class="px-3 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                            >
                                <i class="bi bi-gear-fill mr-1 text-slate-500"></i>Ubah Status
                            </button>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <a
                                v-if="rapat.jenis === 'RTM'"
                                :href="`/cetak/laporan-rtm/${rapat.id}`"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition border border-indigo-200/70 shadow-2xs"
                                title="Download / Cetak Dokumen Resmi RTM Institusi (A4)"
                            >
                                <i class="bi bi-award-fill text-indigo-600"></i>
                                <span>Cetak Laporan RTM Resmi</span>
                            </a>

                            <a
                                :href="exportPdfUrl || `/cetak/notulensi-rapat/${rapat.id}`"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition border border-rose-200/70 shadow-2xs"
                                title="Download / Cetak PDF Notulensi Lengkap"
                            >
                                <i class="bi bi-file-earmark-pdf-fill text-rose-600"></i>
                                <span>Cetak Notulensi PDF</span>
                            </a>

                            <Link
                                v-if="canEdit && !isLocked"
                                :href="`/rapat/${rapat.id}/edit`"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition border border-slate-200"
                            >
                                <i class="bi bi-pencil-fill text-slate-500"></i>
                                <span>Edit Rapat</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Description / Deskripsi Singkat -->
                <div v-if="rapat.deskripsi" class="pt-4 text-xs text-slate-600 leading-relaxed bg-slate-50/70 rounded-2xl p-4 mt-4 border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1">Pengantar / Deskripsi Rapat:</p>
                    <p class="whitespace-pre-line">{{ rapat.deskripsi }}</p>
                </div>

                <!-- Alasan Pembatalan Banner -->
                <div v-if="rapat.status === 'dibatalkan' && rapat.alasan_pembatalan" class="mt-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                    <p class="font-black text-rose-900">Rapat Ini Telah Dibatalkan</p>
                    <p class="mt-1">Alasan: {{ rapat.alasan_pembatalan }}</p>
                </div>

                <!-- Summary Badges Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-5 mt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Agenda</p>
                            <p class="text-xs font-black text-slate-800">{{ rapat.agendas.length }} Agenda</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Kehadiran</p>
                            <p class="text-xs font-black text-slate-800">
                                {{ ringkasanKehadiran.hadir }} / {{ ringkasanKehadiran.total }} Hadir
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Tindak Lanjut</p>
                            <p class="text-xs font-black text-slate-800">
                                {{ ringkasanTL.selesai }} / {{ ringkasanTL.total }} Selesai
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                            <i class="bi bi-paperclip"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Lampiran</p>
                            <p class="text-xs font-black text-slate-800">{{ rapat.lampirans.length }} Berkas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Sections -->
            <div class="space-y-4">
                <!-- Tab Headers -->
                <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 rounded-2xl max-w-fit overflow-x-auto">
                    <button
                        v-for="t in tabs"
                        :key="t.id"
                        @click="activeTab = t.id"
                        type="button"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap cursor-pointer"
                        :class="activeTab === t.id ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/60'"
                    >
                        <i :class="['bi', t.icon]"></i>
                        <span>{{ t.label }}</span>
                        <span v-if="t.count !== undefined" class="px-1.5 py-0.2 text-[10px] rounded-full" :class="activeTab === t.id ? 'bg-teal-100 text-teal-800' : 'bg-slate-200 text-slate-600'">
                            {{ t.count }}
                        </span>
                    </button>
                </div>

                <!-- ═══════════════════════════════════════════════════════ -->
                <!-- TAB 1: SUSUNAN AGENDA & NOTULENSI                       -->
                <!-- ═══════════════════════════════════════════════════════ -->
                <div v-show="activeTab === 'agenda'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Susunan Agenda & Catatan Notulensi</h2>
                            <p class="text-[11px] text-slate-400">Notulis dapat mencatat ringkasan dan hasil pembahasan per butir agenda.</p>
                        </div>
                        <button
                            v-if="canEdit && !isLocked"
                            @click="openAddAgendaModal"
                            type="button"
                            class="px-3.5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Agenda</span>
                        </button>
                    </div>

                    <div v-if="rapat.agendas.length === 0" class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center text-slate-400">
                        <i class="bi bi-list-check text-3xl text-slate-200 block mb-2"></i>
                        <p class="text-xs font-bold text-slate-600">Belum ada susunan agenda</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Tambahkan agenda rapat untuk melengkapi jadwal dan notulensi.</p>
                    </div>

                    <div v-else class="space-y-3">
                        <div
                            v-for="ag in rapat.agendas"
                            :key="ag.id"
                            class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-4"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3">
                                    <span class="w-7 h-7 rounded-xl bg-teal-50 text-teal-700 text-xs font-black flex items-center justify-center shrink-0 mt-0.5">
                                        {{ ag.urutan }}
                                    </span>
                                    <div>
                                        <h3 class="font-black text-slate-900 text-sm">{{ ag.judul }}</h3>
                                        <p v-if="ag.deskripsi" class="text-xs text-slate-500 mt-0.5">{{ ag.deskripsi }}</p>
                                        <span class="inline-block mt-1 text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                                            Estimasi: {{ ag.estimasi_durasi }} Menit
                                        </span>
                                    </div>
                                </div>

                                <button
                                    v-if="canEdit && !isLocked"
                                    @click="deleteAgenda(ag.id)"
                                    type="button"
                                    class="p-1.5 text-slate-300 hover:text-rose-600 transition cursor-pointer"
                                    title="Hapus Agenda"
                                >
                                    <i class="bi bi-trash text-xs"></i>
                                </button>
                            </div>

                            <!-- Notulensi Editor per Agenda -->
                            <div class="pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="text-[11px] font-bold uppercase text-slate-400 flex items-center gap-1.5">
                                        <i class="bi bi-pencil-square text-teal-600"></i>
                                        <span>Catatan Notulensi Pembahasan</span>
                                    </label>
                                    <span v-if="ag.notulensi_updated_at" class="text-[10px] text-slate-400">
                                        Diperbarui {{ ag.notulensi_updated_at }} oleh {{ ag.notulensi_updated_by || 'Notulis' }}
                                    </span>
                                </div>

                                <textarea
                                    v-model="notulensiData[ag.id]"
                                    rows="3"
                                    :disabled="isLocked && !isSuperAdmin"
                                    placeholder="Tuliskan ringkasan pembahasan, kesepakatan, dan arahan agenda ini..."
                                    class="w-full p-3.5 rounded-2xl border border-slate-200 text-xs text-slate-800 focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 outline-none transition bg-slate-50/50 resize-none leading-relaxed"
                                ></textarea>

                                <div class="flex justify-end mt-2">
                                    <button
                                        v-if="!isLocked || isSuperAdmin"
                                        @click="saveNotulensi(ag.id)"
                                        :disabled="savingNotulensi === ag.id"
                                        type="button"
                                        class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-[11px] font-bold transition flex items-center gap-1.5 cursor-pointer shadow-2xs disabled:opacity-50"
                                    >
                                        <i v-if="savingNotulensi === ag.id" class="bi bi-arrow-repeat animate-spin"></i>
                                        <i v-else class="bi bi-check2"></i>
                                        <span>Simpan Notulensi</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════ -->
                <!-- TAB 2: DAFTAR PESERTA & PRESENSI                       -->
                <!-- ═══════════════════════════════════════════════════════ -->
                <div v-show="activeTab === 'peserta'" class="space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Daftar Peserta & Presensi Kehadiran</h2>
                            <p class="text-[11px] text-slate-400">Rekapitulasi kehadiran peserta internal dosen/tendik dan tamu eksternal.</p>
                        </div>
                        <button
                            v-if="canEdit && !isLocked"
                            @click="showAddPesertaModal = true"
                            type="button"
                            class="px-3.5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                        >
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Tambah Peserta</span>
                        </button>
                    </div>

                    <!-- Presensi Filter Pill -->
                    <div class="flex gap-2 text-xs">
                        <button
                            v-for="st in ['semua', 'hadir', 'izin', 'tidak_hadir', 'diundang']"
                            :key="st"
                            @click="pesertaFilter = st"
                            type="button"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition uppercase text-[10px] cursor-pointer"
                            :class="pesertaFilter === st ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
                        >
                            {{ st.replace('_', ' ') }}
                        </button>
                    </div>

                    <!-- Peserta Table Card -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                        <th class="px-5 py-3.5 text-left">Nama Peserta</th>
                                        <th class="px-4 py-3.5 text-left">Instansi / Unit</th>
                                        <th class="px-4 py-3.5 text-left">Peran</th>
                                        <th class="px-4 py-3.5 text-left">Status Kehadiran</th>
                                        <th class="px-4 py-3.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr v-if="filteredPeserta.length === 0">
                                        <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                            Belum ada peserta terdaftar dalam kategori ini.
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="p in filteredPeserta"
                                        :key="p.id"
                                        class="hover:bg-slate-50/70 transition"
                                    >
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                                    {{ p.avatar_inisial || 'P' }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800">{{ p.nama }}</p>
                                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ p.email || p.no_hp || '-' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3.5 text-slate-600">
                                            <span>{{ p.instansi || '-' }}</span>
                                            <span v-if="p.is_eksternal" class="ml-1.5 px-1.5 py-0.2 rounded text-[9px] font-extrabold bg-blue-50 text-blue-700">Tamu</span>
                                        </td>

                                        <td class="px-4 py-3.5">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="p.peran === 'Ketua' ? 'bg-indigo-50 text-indigo-700' : (p.peran === 'Notulis' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600')">
                                                {{ p.peran }}
                                            </span>
                                        </td>

                                        <!-- Quick Attendance Switcher Buttons -->
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-1">
                                                <button
                                                    v-for="st in ['hadir', 'izin', 'tidak_hadir', 'diundang']"
                                                    :key="st"
                                                    @click="setKehadiran(p.id, st)"
                                                    type="button"
                                                    class="px-2 py-1 rounded-lg text-[9px] font-bold transition uppercase cursor-pointer"
                                                    :class="p.status_kehadiran === st ? activeKehadiranClass(st) : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                                                >
                                                    {{ st === 'tidak_hadir' ? 'Absen' : st }}
                                                </button>
                                            </div>
                                            <p v-if="p.kehadiran_updated_at" class="text-[9px] text-slate-400 mt-1">
                                                Tercatat: {{ p.kehadiran_updated_at }}
                                            </p>
                                        </td>

                                        <td class="px-4 py-3.5 text-right">
                                            <button
                                                v-if="canEdit && !isLocked"
                                                @click="deletePeserta(p.id)"
                                                type="button"
                                                class="p-1.5 rounded-lg text-slate-300 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                title="Hapus Peserta"
                                            >
                                                <i class="bi bi-trash text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════ -->
                <!-- TAB 3: TINDAK LANJUT (ACTION ITEMS)                    -->
                <!-- ═══════════════════════════════════════════════════════ -->
                <div v-show="activeTab === 'tindak_lanjut'" class="space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Komitmen & Tindak Lanjut Hasil Rapat</h2>
                            <p class="text-[11px] text-slate-400">Pantau eksekusi tugas, PIC penanggung jawab, dan tanggal batas waktu.</p>
                        </div>
                        <button
                            v-if="canEdit && !isLocked"
                            @click="showAddTLModal = true"
                            type="button"
                            class="px-3.5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Tindak Lanjut</span>
                        </button>
                    </div>

                    <div v-if="rapat.tindak_lanjuts.length === 0" class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center text-slate-400">
                        <i class="bi bi-arrow-repeat text-3xl text-slate-200 block mb-2"></i>
                        <p class="text-xs font-bold text-slate-600">Belum ada tindak lanjut dicatat</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Tambahkan komitmen tindak lanjut agar hasil rapat dapat dievaluasi.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="tl in rapat.tindak_lanjuts"
                            :key="tl.id"
                            class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs space-y-3 flex flex-col justify-between"
                        >
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase" :class="tlStatusClass(tl.status)">
                                        {{ tl.status.replace('_', ' ') }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase" :class="tlPrioritasClass(tl.prioritas)">
                                        Prioritas {{ tl.prioritas }}
                                    </span>
                                </div>

                                <p class="text-xs font-bold text-slate-800 leading-relaxed">{{ tl.deskripsi }}</p>

                                <div class="mt-3 space-y-1 text-[11px] text-slate-500">
                                    <p class="flex items-center gap-1.5">
                                        <i class="bi bi-person-badge text-teal-600"></i>
                                        <span>PIC: <b>{{ tl.pic_name || '-' }}</b></span>
                                    </p>
                                    <p class="flex items-center gap-1.5" :class="tl.is_overdue ? 'text-rose-600 font-bold' : ''">
                                        <i class="bi bi-calendar-check"></i>
                                        <span>Deadline: {{ tl.deadline_display }}</span>
                                        <span v-if="tl.is_overdue" class="px-1.5 py-0.2 rounded text-[9px] bg-rose-100 text-rose-700 uppercase font-black">Overdue</span>
                                    </p>
                                    <p v-if="tl.catatan_progres" class="bg-slate-50 rounded-xl p-2.5 text-[10px] text-slate-600 italic border border-slate-100 mt-2">
                                        "{{ tl.catatan_progres }}"
                                    </p>
                                </div>
                            </div>

                            <!-- Progress updater button -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <button
                                    @click="openEditTLModal(tl)"
                                    type="button"
                                    class="text-xs font-bold text-teal-600 hover:text-teal-800 flex items-center gap-1 cursor-pointer"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Update Status & Progres</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════ -->
                <!-- TAB 4: BERKAS & LAMPIRAN MATERI                        -->
                <!-- ═══════════════════════════════════════════════════════ -->
                <div v-show="activeTab === 'lampiran'" class="space-y-5">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Berkas Materi & Dokumentasi Rapat</h2>
                        <p class="text-[11px] text-slate-400">Unggah bahan paparan slide, SK penugasan, foto absensi fisik, atau dokumen pendukung.</p>
                    </div>

                    <!-- Upload Form Card -->
                    <div v-if="canEdit && !isLocked" class="bg-white rounded-3xl border-2 border-dashed border-slate-200 hover:border-teal-300 transition p-6 text-center shadow-xs">
                        <input
                            ref="lampiranInput"
                            type="file"
                            accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx,.pptx"
                            class="hidden"
                            @change="uploadFile"
                        />
                        <div class="space-y-2 cursor-pointer" @click="$refs.lampiranInput.click()">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl mx-auto">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-800">Klik untuk mengunggah materi / berkas baru</p>
                            <p class="text-[10px] text-slate-400">Format: PDF, JPG, PNG, DOCX, XLSX, PPTX (Maksimal 10 MB per berkas)</p>
                        </div>
                        <div v-if="uploading" class="mt-3 flex items-center justify-center gap-2 text-xs font-bold text-teal-600">
                            <i class="bi bi-arrow-repeat animate-spin"></i>
                            <span>Mengunggah berkas...</span>
                        </div>
                    </div>

                    <!-- Lampiran List Table -->
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-100 font-bold text-slate-500 uppercase tracking-wider text-[10px]">
                                        <th class="px-5 py-3.5 text-left">Nama Berkas</th>
                                        <th class="px-4 py-3.5 text-left">Ukuran</th>
                                        <th class="px-4 py-3.5 text-left">Diupload Oleh</th>
                                        <th class="px-4 py-3.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr v-if="rapat.lampirans.length === 0">
                                        <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                            Belum ada berkas materi yang diunggah.
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="l in rapat.lampirans"
                                        :key="l.id"
                                        class="hover:bg-slate-50/70 transition"
                                    >
                                        <td class="px-5 py-3.5 font-bold text-slate-800 flex items-center gap-2">
                                            <i class="bi bi-file-earmark-text text-teal-600 text-base"></i>
                                            <span class="truncate max-w-sm">{{ l.nama_asli }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-500 font-mono">{{ l.ukuran }}</td>
                                        <td class="px-4 py-3.5 text-slate-500">{{ l.uploader_name }} · {{ l.created_at }}</td>
                                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a
                                                    :href="l.download_url"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-50 transition"
                                                    title="Download Berkas"
                                                >
                                                    <i class="bi bi-download text-sm"></i>
                                                </a>
                                                <button
                                                    v-if="canEdit && !isLocked"
                                                    @click="deleteLampiran(l.id)"
                                                    type="button"
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                    title="Hapus Berkas"
                                                >
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════════ -->
                <!-- TAB 5: RTM & KESIMPULAN                                 -->
                <!-- ═══════════════════════════════════════════════════════ -->
                <div v-show="activeTab === 'rtm'" class="space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs space-y-6">
                        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                            <i class="bi bi-journal-text text-teal-600 text-lg"></i>
                            <h2 class="font-black text-slate-900 text-sm">Kesimpulan & Catatan Akhir Rapat</h2>
                        </div>
                        <div v-if="rapat.kesimpulan" class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                            {{ rapat.kesimpulan }}
                        </div>
                        <div v-else class="text-center py-6 text-slate-400 text-xs">
                            Belum ada kesimpulan akhir tersimpan. Anda dapat mengisi kesimpulan saat menutup status rapat menjadi <b>Selesai</b>.
                        </div>
                    </div>

                    <!-- RTM Analysis Cards if RTM -->
                    <div v-if="rapat.jenis === 'RTM'" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xs space-y-6">
                        <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                            <i class="bi bi-award-fill text-indigo-600 text-lg"></i>
                            <h2 class="font-black text-slate-900 text-sm">Matriks Tinjauan Manajemen Mutu (SN-Dikti)</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-1">
                                <span class="font-black text-indigo-900 text-[11px] uppercase tracking-wider block">1. Hasil Audit Internal (AMI)</span>
                                <p class="text-slate-700 whitespace-pre-line">{{ rapat.input_audit_internal || '-' }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-1">
                                <span class="font-black text-indigo-900 text-[11px] uppercase tracking-wider block">2. Umpan Balik / Kepuasan</span>
                                <p class="text-slate-700 whitespace-pre-line">{{ rapat.input_umpan_balik || '-' }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-1">
                                <span class="font-black text-indigo-900 text-[11px] uppercase tracking-wider block">3. Kinerja Proses & Mutu</span>
                                <p class="text-slate-700 whitespace-pre-line">{{ rapat.input_kinerja_proses || '-' }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-1">
                                <span class="font-black text-indigo-900 text-[11px] uppercase tracking-wider block">4. Evaluasi Tindakan RTM Lalu</span>
                                <p class="text-slate-700 whitespace-pre-line">{{ rapat.input_status_tindakan || '-' }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100 space-y-1 md:col-span-2">
                                <span class="font-black text-emerald-900 text-[11px] uppercase tracking-wider block">5. Rekomendasi & Rencana Peningkatan</span>
                                <p class="text-slate-700 whitespace-pre-line">{{ rapat.output_perbaikan || rapat.input_rekomendasi || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- MODALS                                                      -->
            <!-- ═══════════════════════════════════════════════════════════ -->

            <!-- 1. Modal Ubah Status Rapat -->
            <Teleport to="body">
                <div v-if="showStatusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Ubah Status Rapat</h3>
                            <button @click="showStatusModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitStatusChange" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Status Baru</label>
                                <select
                                    v-model="statusForm.status"
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500/30 outline-none bg-white"
                                >
                                    <option value="draft">Draft (Belum Dijadwalkan)</option>
                                    <option value="terjadwal">Terjadwal (Kirim Undangan)</option>
                                    <option value="berlangsung">Sedang Berlangsung</option>
                                    <option value="selesai">Selesai (Notulensi Final)</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>

                            <div v-if="statusForm.status === 'selesai'">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kesimpulan Akhir Rapat</label>
                                <textarea
                                    v-model="statusForm.kesimpulan"
                                    rows="3"
                                    placeholder="Tuliskan kesimpulan pokok atau hasil musyawarah rapat..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div v-if="statusForm.status === 'dibatalkan'">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Pembatalan <span class="text-rose-500">*</span></label>
                                <textarea
                                    v-model="statusForm.alasan_pembatalan"
                                    required
                                    rows="3"
                                    placeholder="Berikan alasan pembatalan untuk dikirimkan kepada peserta..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showStatusModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="submittingStatus"
                                    class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5"
                                >
                                    <i v-if="submittingStatus" class="bi bi-arrow-repeat animate-spin"></i>
                                    <span>Simpan Status</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- 2. Modal Tambah Agenda -->
            <Teleport to="body">
                <div v-if="showAddAgendaModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">Tambah Susunan Agenda</h3>
                            <button @click="showAddAgendaModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <form @submit.prevent="submitAgenda" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Agenda <span class="text-rose-500">*</span></label>
                                <input
                                    v-model="agendaForm.judul"
                                    type="text"
                                    required
                                    placeholder="Contoh: Pembahasan Capaian IKU 1 - 8"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Estimasi Durasi (Menit) <span class="text-rose-500">*</span></label>
                                <input
                                    v-model="agendaForm.estimasi_durasi"
                                    type="number"
                                    min="1"
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan / Tujuan Singkat</label>
                                <textarea
                                    v-model="agendaForm.deskripsi"
                                    rows="2"
                                    placeholder="Keterangan tambahan..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    @click="showAddAgendaModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="submittingAgenda"
                                    class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50"
                                >
                                    Simpan Agenda
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- 3. Modal Tambah Peserta -->
            <Teleport to="body">
                <div v-if="showAddPesertaModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-xl space-y-4 max-h-[90vh] flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                            <div>
                                <h3 class="font-black text-slate-900 text-sm">Daftarkan Peserta Rapat</h3>
                                <p class="text-[11px] text-slate-400">Pilih satu atau beberapa pegawai sekaligus menggunakan checkbox.</p>
                            </div>
                            <button @click="showAddPesertaModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <!-- Tipe Peserta Switcher -->
                        <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 rounded-2xl text-xs font-bold shrink-0">
                            <button
                                @click="pesertaForm.tipe_peserta = 'internal'"
                                type="button"
                                class="py-2 rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5"
                                :class="pesertaForm.tipe_peserta === 'internal' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500'"
                            >
                                <i class="bi bi-people-fill"></i>
                                <span>Pegawai Internal (Checkbox Sekaligus)</span>
                            </button>
                            <button
                                @click="pesertaForm.tipe_peserta = 'eksternal'"
                                type="button"
                                class="py-2 rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5"
                                :class="pesertaForm.tipe_peserta === 'eksternal' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500'"
                            >
                                <i class="bi bi-building"></i>
                                <span>Tamu Eksternal</span>
                            </button>
                        </div>

                        <!-- Internal: Multi-Select Checkbox Form -->
                        <form v-if="pesertaForm.tipe_peserta === 'internal'" @submit.prevent="submitBatchPeserta" class="space-y-4 flex-1 flex flex-col min-h-0">
                            <!-- Search & Select All Controls -->
                            <div class="space-y-2 shrink-0">
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                                    <input
                                        v-model="pesertaSearchQuery"
                                        type="search"
                                        placeholder="Cari nama pegawai, NIP, unit kerja, atau jabatan..."
                                        class="w-full pl-8 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none"
                                    />
                                </div>

                                <div class="flex items-center justify-between px-1 text-xs">
                                    <label class="flex items-center gap-2 font-bold text-slate-700 cursor-pointer select-none">
                                        <input
                                            type="checkbox"
                                            :checked="isAllSelectableChecked"
                                            :indeterminate.prop="isSomeSelectableChecked"
                                            @change="toggleSelectAllUsers"
                                            class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 cursor-pointer"
                                        />
                                        <span>Pilih Semua yang Belum Terdaftar</span>
                                    </label>
                                    <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">
                                        {{ selectedUserIds.length }} Pegawai Terpilih
                                    </span>
                                </div>
                            </div>

                            <!-- Scrollable Checkbox List -->
                            <div class="border border-slate-200/80 rounded-2xl p-2 max-h-56 overflow-y-auto space-y-1.5 divide-y divide-slate-100 flex-1">
                                <div
                                    v-if="filteredSelectableUsers.length === 0"
                                    class="text-center py-6 text-slate-400 text-xs"
                                >
                                    Tidak ada data pegawai yang sesuai pencarian.
                                </div>
                                <div
                                    v-for="u in filteredSelectableUsers"
                                    :key="u.id"
                                    class="flex items-center justify-between p-2 rounded-xl transition cursor-pointer hover:bg-slate-50 pt-2"
                                    :class="u.is_already_added ? 'opacity-50 bg-slate-50 cursor-not-allowed' : (selectedUserIds.includes(u.id) ? 'bg-teal-50/50' : '')"
                                    @click="!u.is_already_added && toggleUserSelection(u.id)"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <input
                                            type="checkbox"
                                            :value="u.id"
                                            :disabled="u.is_already_added"
                                            :checked="selectedUserIds.includes(u.id)"
                                            @click.stop="toggleUserSelection(u.id)"
                                            class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 cursor-pointer"
                                        />
                                        <div class="w-7 h-7 rounded-lg bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ u.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-900 text-xs truncate leading-tight">{{ u.name }}</p>
                                            <p class="text-[10px] text-slate-400 truncate">
                                                <span v-if="u.nip">{{ u.nip }} · </span>
                                                <span>{{ u.unit_kerja || u.jabatan || u.role || 'Staff' }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="shrink-0 ml-2">
                                        <span v-if="u.is_already_added" class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-200 text-slate-600">
                                            Sudah Terdaftar
                                        </span>
                                        <span v-else class="px-2 py-0.5 rounded-full text-[9px] font-semibold bg-slate-100 text-slate-600">
                                            {{ u.role || 'Internal' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Default Roles & Status Awal -->
                            <div class="grid grid-cols-2 gap-3 shrink-0 pt-1">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Peran dalam Rapat <span class="text-rose-500">*</span></label>
                                    <select v-model="pesertaForm.peran" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-teal-500/30 outline-none font-semibold">
                                        <option value="Peserta">Peserta</option>
                                        <option value="Ketua">Ketua / Pimpinan Rapat</option>
                                        <option value="Notulis">Notulis</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Kehadiran Awal</label>
                                    <select v-model="pesertaForm.status_kehadiran_awal" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-teal-500/30 outline-none font-semibold">
                                        <option value="diundang">Diundang</option>
                                        <option value="hadir">Langsung Hadir</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-100 shrink-0">
                                <span class="text-xs text-slate-500">
                                    <span class="font-bold text-teal-700">{{ selectedUserIds.length }}</span> pegawai dipilih
                                </span>
                                <div class="flex gap-2">
                                    <button
                                        @click="showAddPesertaModal = false"
                                        type="button"
                                        class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="submittingPeserta || selectedUserIds.length === 0"
                                        class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-1.5 cursor-pointer"
                                    >
                                        <i v-if="submittingPeserta" class="bi bi-arrow-repeat animate-spin"></i>
                                        <i v-else class="bi bi-person-check-fill"></i>
                                        <span>Simpan {{ selectedUserIds.length > 0 ? `(${selectedUserIds.length})` : '' }} Peserta Sekaligus</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Eksternal Guest Form -->
                        <form v-else @submit.prevent="submitEksternalPeserta" class="space-y-3.5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Tamu <span class="text-rose-500">*</span></label>
                                <input v-model="pesertaForm.nama_eksternal" type="text" required placeholder="Nama lengkap tamu..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-teal-500/30" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Instansi / Lembaga</label>
                                <input v-model="pesertaForm.instansi" type="text" placeholder="Asal instansi..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-teal-500/30" />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                                    <input v-model="pesertaForm.email_eksternal" type="email" placeholder="email@tamu.com" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-teal-500/30" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                                    <input v-model="pesertaForm.no_hp_eksternal" type="text" placeholder="08..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-teal-500/30" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Peran dalam Rapat <span class="text-rose-500">*</span></label>
                                    <select v-model="pesertaForm.peran" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-teal-500/30 outline-none">
                                        <option value="Peserta">Peserta</option>
                                        <option value="Ketua">Ketua / Pimpinan Rapat</option>
                                        <option value="Notulis">Notulis</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Awal</label>
                                    <select v-model="pesertaForm.status_kehadiran_awal" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-teal-500/30 outline-none">
                                        <option value="diundang">Diundang</option>
                                        <option value="hadir">Langsung Hadir</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                                <button
                                    @click="showAddPesertaModal = false"
                                    type="button"
                                    class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="submittingPeserta"
                                    class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition shadow-sm disabled:opacity-50"
                                >
                                    Daftarkan Tamu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>

            <!-- 4. Modal Tambah / Update Tindak Lanjut -->
            <Teleport to="body">
                <div v-if="showAddTLModal || editingTL" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4">
                    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-black text-slate-900 text-sm">
                                {{ editingTL ? 'Perbarui Tindak Lanjut' : 'Tambah Komitmen Tindak Lanjut' }}
                            </h3>
                            <button @click="closeTLModal" class="text-slate-400 hover:text-slate-600">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <!-- Update Existing TL Form -->
                        <form v-if="editingTL" @submit.prevent="submitUpdateTL" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Pelaksanaan <span class="text-rose-500">*</span></label>
                                <select v-model="editTLForm.status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-teal-500/30 outline-none bg-white">
                                    <option value="belum_mulai">Belum Mulai</option>
                                    <option value="dalam_proses">Dalam Proses</option>
                                    <option value="selesai">Selesai (Completed)</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan Progres / Hasil Tindakan</label>
                                <textarea
                                    v-model="editTLForm.catatan_progres"
                                    rows="3"
                                    placeholder="Jelaskan progres penyelesaian atau tautan bukti..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button @click="closeTLModal" type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">Batal</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold">Simpan Progres</button>
                            </div>
                        </form>

                        <!-- Add New TL Form -->
                        <form v-else @submit.prevent="submitAddTL" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Tindak Lanjut / Tugas <span class="text-rose-500">*</span></label>
                                <textarea
                                    v-model="tlForm.deskripsi"
                                    required
                                    rows="3"
                                    placeholder="Tuliskan tindakan yang harus dilakukan..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none resize-none"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penanggung Jawab (PIC) <span class="text-rose-500">*</span></label>
                                <select v-model="tlForm.pic_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-teal-500/30 outline-none">
                                    <option value="">-- Pilih PIC Pegawai --</option>
                                    <option v-for="u in users" :key="u.id" :value="u.id">
                                        {{ u.name }} ({{ u.role }})
                                    </option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Waktu (Deadline) <span class="text-rose-500">*</span></label>
                                    <input v-model="tlForm.deadline" type="date" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-teal-500/30 outline-none" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Prioritas</label>
                                    <select v-model="tlForm.prioritas" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-teal-500/30 outline-none">
                                        <option value="Tinggi">Tinggi</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Rendah">Rendah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button @click="closeTLModal" type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600">Batal</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold">Simpan Komitmen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rapat: Object,
    ringkasanKehadiran: Object,
    ringkasanTL: Object,
    users: Array,
    pegawais: Array,
    canEdit: Boolean,
    isLocked: Boolean,
    currentUserId: Number,
    isSuperAdmin: Boolean,
    exportPdfUrl: String,
});

// Tabs
const activeTab = ref('agenda');
const tabs = computed(() => [
    { id: 'agenda', label: 'Susunan Agenda', icon: 'bi-list-check', count: props.rapat.agendas.length },
    { id: 'peserta', label: 'Daftar Peserta', icon: 'bi-people-fill', count: props.rapat.peserta.length },
    { id: 'tindak_lanjut', label: 'Tindak Lanjut', icon: 'bi-arrow-repeat', count: props.rapat.tindak_lanjuts.length },
    { id: 'lampiran', label: 'Berkas Materi', icon: 'bi-paperclip', count: props.rapat.lampirans.length },
    { id: 'rtm', label: props.rapat.jenis === 'RTM' ? 'Matriks RTM' : 'Kesimpulan', icon: 'bi-journal-text' },
]);

// ── Notulensi Handling ──────────────────────────────────────────
const notulensiData = reactive({});
props.rapat.agendas.forEach(ag => {
    notulensiData[ag.id] = ag.notulensi || '';
});
const savingNotulensi = ref(null);

function saveNotulensi(agendaId) {
    savingNotulensi.value = agendaId;
    router.put(`/rapat/${props.rapat.id}/agenda/${agendaId}/notulensi`, {
        notulensi: notulensiData[agendaId],
    }, {
        preserveScroll: true,
        onFinish: () => {
            savingNotulensi.value = null;
        },
    });
}

// ── Agenda Operations ───────────────────────────────────────────
const showAddAgendaModal = ref(false);
const submittingAgenda = ref(false);
const agendaForm = reactive({
    judul: '',
    estimasi_durasi: 30,
    deskripsi: '',
});

function openAddAgendaModal() {
    agendaForm.judul = '';
    agendaForm.estimasi_durasi = 30;
    agendaForm.deskripsi = '';
    showAddAgendaModal.value = true;
}

function submitAgenda() {
    submittingAgenda.value = true;
    router.post(`/rapat/${props.rapat.id}/agenda`, agendaForm, {
        preserveScroll: true,
        onSuccess: () => {
            showAddAgendaModal.value = false;
        },
        onFinish: () => {
            submittingAgenda.value = false;
        },
    });
}

function deleteAgenda(agendaId) {
    if (confirm('Hapus susunan agenda ini?')) {
        router.delete(`/rapat/${props.rapat.id}/agenda/${agendaId}`, {
            preserveScroll: true,
        });
    }
}

// ── Peserta Operations ──────────────────────────────────────────
const pesertaFilter = ref('semua');
const filteredPeserta = computed(() => {
    if (pesertaFilter.value === 'semua') return props.rapat.peserta;
    return props.rapat.peserta.filter(p => p.status_kehadiran === pesertaFilter.value);
});

const showAddPesertaModal = ref(false);
const submittingPeserta = ref(false);
const pesertaSearchQuery = ref('');
const selectedUserIds = ref([]);

const pesertaForm = reactive({
    tipe_peserta: 'internal',
    user_id: '',
    nama_eksternal: '',
    instansi: '',
    email_eksternal: '',
    no_hp_eksternal: '',
    peran: 'Peserta',
    status_kehadiran_awal: 'diundang',
});

// Existing participant user_ids set for fast lookup
const existingParticipantUserIds = computed(() => {
    return new Set(props.rapat.peserta.map(p => p.user_id).filter(Boolean));
});

// Filtered users list with indicator if already added
const filteredSelectableUsers = computed(() => {
    const query = pesertaSearchQuery.value.toLowerCase().trim();
    return (props.users || []).map(u => ({
        ...u,
        is_already_added: existingParticipantUserIds.value.has(u.id),
    })).filter(u => {
        if (!query) return true;
        return (
            (u.name && u.name.toLowerCase().includes(query)) ||
            (u.nip && u.nip.toLowerCase().includes(query)) ||
            (u.unit_kerja && u.unit_kerja.toLowerCase().includes(query)) ||
            (u.jabatan && u.jabatan.toLowerCase().includes(query)) ||
            (u.role && u.role.toLowerCase().includes(query))
        );
    });
});

const availableSelectableUsers = computed(() => {
    return filteredSelectableUsers.value.filter(u => !u.is_already_added);
});

const isAllSelectableChecked = computed(() => {
    const available = availableSelectableUsers.value;
    return available.length > 0 && available.every(u => selectedUserIds.value.includes(u.id));
});

const isSomeSelectableChecked = computed(() => {
    const available = availableSelectableUsers.value;
    const count = available.filter(u => selectedUserIds.value.includes(u.id)).length;
    return count > 0 && count < available.length;
});

function toggleUserSelection(userId) {
    const idx = selectedUserIds.value.indexOf(userId);
    if (idx > -1) {
        selectedUserIds.value.splice(idx, 1);
    } else {
        selectedUserIds.value.push(userId);
    }
}

function toggleSelectAllUsers() {
    const available = availableSelectableUsers.value;
    if (isAllSelectableChecked.value) {
        const availableIds = new Set(available.map(u => u.id));
        selectedUserIds.value = selectedUserIds.value.filter(id => !availableIds.has(id));
    } else {
        const currentSet = new Set(selectedUserIds.value);
        available.forEach(u => currentSet.add(u.id));
        selectedUserIds.value = Array.from(currentSet);
    }
}

function submitBatchPeserta() {
    if (selectedUserIds.value.length === 0) return;
    submittingPeserta.value = true;
    router.post(`/rapat/${props.rapat.id}/peserta`, {
        tipe_peserta: 'internal',
        user_ids: selectedUserIds.value,
        peran: pesertaForm.peran,
        status_kehadiran_awal: pesertaForm.status_kehadiran_awal,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showAddPesertaModal.value = false;
            selectedUserIds.value = [];
            pesertaSearchQuery.value = '';
        },
        onFinish: () => {
            submittingPeserta.value = false;
        },
    });
}

function submitEksternalPeserta() {
    submittingPeserta.value = true;
    router.post(`/rapat/${props.rapat.id}/peserta`, {
        tipe_peserta: 'eksternal',
        nama_eksternal: pesertaForm.nama_eksternal,
        instansi: pesertaForm.instansi,
        email_eksternal: pesertaForm.email_eksternal,
        no_hp_eksternal: pesertaForm.no_hp_eksternal,
        peran: pesertaForm.peran,
        status_kehadiran_awal: pesertaForm.status_kehadiran_awal,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showAddPesertaModal.value = false;
            pesertaForm.nama_eksternal = '';
            pesertaForm.instansi = '';
            pesertaForm.email_eksternal = '';
            pesertaForm.no_hp_eksternal = '';
        },
        onFinish: () => {
            submittingPeserta.value = false;
        },
    });
}

function setKehadiran(pesertaId, status) {
    router.patch(`/rapat/${props.rapat.id}/peserta/${pesertaId}/kehadiran`, {
        status_kehadiran: status,
    }, {
        preserveScroll: true,
    });
}

function deletePeserta(pesertaId) {
    if (confirm('Hapus peserta ini dari rapat?')) {
        router.delete(`/rapat/${props.rapat.id}/peserta/${pesertaId}`, {
            preserveScroll: true,
        });
    }
}

// ── Tindak Lanjut Operations ────────────────────────────────────
const showAddTLModal = ref(false);
const editingTL = ref(null);
const tlForm = reactive({
    deskripsi: '',
    pic_id: '',
    deadline: '',
    prioritas: 'Sedang',
});
const editTLForm = reactive({
    status: 'dalam_proses',
    catatan_progres: '',
});

function submitAddTL() {
    router.post(`/rapat/${props.rapat.id}/tindak-lanjut`, tlForm, {
        preserveScroll: true,
        onSuccess: () => {
            showAddTLModal.value = false;
            tlForm.deskripsi = '';
            tlForm.pic_id = '';
            tlForm.deadline = '';
        },
    });
}

function openEditTLModal(tl) {
    editingTL.value = tl;
    editTLForm.status = tl.status;
    editTLForm.catatan_progres = tl.catatan_progres || '';
}

function submitUpdateTL() {
    if (!editingTL.value) return;
    router.patch(`/rapat/${props.rapat.id}/tindak-lanjut/${editingTL.value.id}`, editTLForm, {
        preserveScroll: true,
        onSuccess: () => {
            editingTL.value = null;
        },
    });
}

function closeTLModal() {
    showAddTLModal.value = false;
    editingTL.value = null;
}

// ── File Upload / Lampiran ──────────────────────────────────────
const lampiranInput = ref(null);
const uploading = ref(false);

function uploadFile(e) {
    const file = e.target.files[0];
    if (!file) return;

    uploading.value = true;
    const data = new FormData();
    data.append('file', file);

    router.post(`/rapat/${props.rapat.id}/lampiran`, data, {
        preserveScroll: true,
        onFinish: () => {
            uploading.value = false;
            if (lampiranInput.value) lampiranInput.value.value = '';
        },
    });
}

function deleteLampiran(lampiranId) {
    if (confirm('Hapus berkas materi ini?')) {
        router.delete(`/rapat/${props.rapat.id}/lampiran/${lampiranId}`, {
            preserveScroll: true,
        });
    }
}

// ── Status Change Modal ─────────────────────────────────────────
const showStatusModal = ref(false);
const submittingStatus = ref(false);
const statusForm = reactive({
    status: props.rapat.status || 'draft',
    kesimpulan: props.rapat.kesimpulan || '',
    alasan_pembatalan: props.rapat.alasan_pembatalan || '',
});

function openStatusModal() {
    statusForm.status = props.rapat.status;
    statusForm.kesimpulan = props.rapat.kesimpulan || '';
    statusForm.alasan_pembatalan = props.rapat.alasan_pembatalan || '';
    showStatusModal.value = true;
}

function submitStatusChange() {
    submittingStatus.value = true;
    router.post(`/rapat/${props.rapat.id}/status`, statusForm, {
        preserveScroll: true,
        onSuccess: () => {
            showStatusModal.value = false;
        },
        onFinish: () => {
            submittingStatus.value = false;
        },
    });
}

// ── Badge Helpers ───────────────────────────────────────────────
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

function activeKehadiranClass(st) {
    const map = {
        'hadir': 'bg-emerald-600 text-white shadow-2xs',
        'izin': 'bg-amber-500 text-white shadow-2xs',
        'tidak_hadir': 'bg-rose-600 text-white shadow-2xs',
        'diundang': 'bg-slate-700 text-white shadow-2xs',
    };
    return map[st] || 'bg-slate-800 text-white';
}

function tlStatusClass(st) {
    const map = {
        'belum_mulai': 'bg-slate-100 text-slate-600',
        'dalam_proses': 'bg-blue-100 text-blue-700',
        'selesai': 'bg-emerald-100 text-emerald-700',
        'dibatalkan': 'bg-rose-100 text-rose-700',
    };
    return map[st] || 'bg-slate-100 text-slate-600';
}

function tlPrioritasClass(p) {
    const map = {
        'Tinggi': 'bg-rose-50 text-rose-700 border border-rose-200/60',
        'Sedang': 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'Rendah': 'bg-slate-100 text-slate-600',
    };
    return map[p] || 'bg-slate-100 text-slate-600';
}
</script>
