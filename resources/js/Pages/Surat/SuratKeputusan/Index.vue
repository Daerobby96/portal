<template>
    <AuthenticatedLayout title="Surat Keputusan">
        <div class="space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-black text-slate-900">Surat Keputusan</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Dokumen SK Yayasan & Perguruan Tinggi</p>
                </div>
                <a href="/surat-keputusan/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold transition shadow-sm">
                    <i class="bi bi-plus-lg"></i> Buat SK
                </a>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-5 py-3.5 text-left">Nomor SK</th>
                                <th class="px-5 py-3.5 text-left">Tentang / Perihal</th>
                                <th class="px-5 py-3.5 text-left">Jenis</th>
                                <th class="px-5 py-3.5 text-left">Penandatangan</th>
                                <th class="px-5 py-3.5 text-left">Tgl. SK</th>
                                <th class="px-5 py-3.5 text-left">Status</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="sks.data.length === 0">
                                <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-xs">
                                    <i class="bi bi-file-earmark-ruled text-3xl block mb-2 text-slate-200"></i>
                                    Belum ada Surat Keputusan
                                </td>
                            </tr>
                            <tr v-for="sk in sks.data" :key="sk.id" class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs font-bold text-slate-700">{{ sk.nomor_surat }}</span>
                                </td>
                                <td class="px-5 py-3.5 max-w-xs">
                                    <p class="text-xs font-semibold text-slate-800 truncate">{{ sk.perihal }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">oleh {{ sk.creator_name }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                        :class="sk.jenis_kode === 'SK-YYS' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700'">
                                        {{ sk.jenis_kode === 'SK-YYS' ? 'Yayasan' : 'Perguruan Tinggi' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">{{ sk.penandatangan }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-600 whitespace-nowrap">{{ sk.tanggal_surat }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Diterbitkan</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a v-if="sk.download_url" :href="sk.download_url" class="p-1.5 rounded-lg text-slate-400 hover:text-green-600 hover:bg-green-50 transition" title="Download PDF">
                                            <i class="bi bi-download text-sm"></i>
                                        </a>
                                        <button @click="confirmDelete(sk)" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div v-if="sks.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>{{ sks.from }}–{{ sks.to }} dari {{ sks.total }}</span>
                    <div class="flex gap-1">
                        <a v-for="link in sks.links" :key="link.label" :href="link.url"
                            class="px-2.5 py-1.5 rounded-lg font-semibold transition"
                            :class="link.active ? 'bg-amber-600 text-white' : 'hover:bg-slate-100 text-slate-500'"
                            v-html="link.label" />
                    </div>
                </div>
            </div>

            <!-- Delete Confirm Modal -->
            <Teleport to="body">
                <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 mx-auto mb-4">
                            <i class="bi bi-trash text-xl"></i>
                        </div>
                        <h3 class="font-black text-slate-900 text-center mb-1">Hapus Surat Keputusan?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5">"{{ deleteTarget.perihal }}" akan dihapus permanen beserta file PDF-nya.</p>
                        <div class="flex gap-3">
                            <button @click="deleteTarget = null" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                            <form :action="`/surat-keputusan/${deleteTarget.id}`" method="POST" class="flex-1">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" :value="csrf">
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ sks: Object });

const deleteTarget = ref(null);
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

function confirmDelete(sk) { deleteTarget.value = sk; }
</script>
