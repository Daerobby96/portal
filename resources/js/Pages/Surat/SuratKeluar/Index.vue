<template>
    <AuthenticatedLayout title="Surat Keluar">
        <div class="space-y-5">
            <!-- Header -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-black text-slate-900">Surat Keluar</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Naskah dinas keluar institusi</p>
                </div>
                <a href="/surat-keluar/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold transition shadow-sm">
                    <i class="bi bi-plus-lg"></i> Buat Surat Keluar
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <input v-model="filters.search" @keyup.enter="applyFilters" type="search" placeholder="Cari nomor, perihal, tujuan..."
                        class="col-span-2 md:col-span-2 px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition" />
                    <select v-model="filters.status" @change="applyFilters" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Menunggu Persetujuan</option>
                        <option value="published">Diterbitkan</option>
                    </select>
                    <select v-model="filters.jenis_surat_id" @change="applyFilters" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition">
                        <option value="">Semua Jenis</option>
                        <option v-for="j in jenisSurat" :key="j.id" :value="j.id">{{ j.nama }}</option>
                    </select>
                    <button @click="resetFilters" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        <i class="bi bi-x-circle mr-1"></i>Reset
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-5 py-3.5 text-left">Nomor Surat</th>
                                <th class="px-5 py-3.5 text-left">Perihal & Tujuan</th>
                                <th class="px-5 py-3.5 text-left">Jenis</th>
                                <th class="px-5 py-3.5 text-left">Tgl. Surat</th>
                                <th class="px-5 py-3.5 text-left">Status</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-if="suratKeluar.data.length === 0">
                                <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                    <i class="bi bi-send-slash text-3xl block mb-2 text-slate-200"></i>
                                    Belum ada surat keluar
                                </td>
                            </tr>
                            <tr v-for="s in suratKeluar.data" :key="s.id" class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs font-bold text-slate-700">{{ s.nomor_surat }}</span>
                                </td>
                                <td class="px-5 py-3.5 max-w-xs">
                                    <p class="font-semibold text-slate-800 text-xs truncate">{{ s.perihal }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5 truncate">→ {{ s.tujuan }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs text-slate-600">{{ s.jenis_surat }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600 whitespace-nowrap">
                                    {{ formatDate(s.tanggal_surat) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <StatusBadge :value="s.status" />
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a :href="`/surat-keluar/${s.id}`" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition" title="Detail">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        <a v-if="s.status !== 'published'" :href="`/surat-keluar/${s.id}/edit`" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Edit">
                                            <i class="bi bi-pencil text-sm"></i>
                                        </a>
                                        <a :href="`/surat-keluar/${s.id}/pdf`" class="p-1.5 rounded-lg text-slate-400 hover:text-green-600 hover:bg-green-50 transition" title="Download PDF">
                                            <i class="bi bi-download text-sm"></i>
                                        </a>
                                        <button v-if="canApprove && s.status === 'pending'" @click="approveAction(s.id)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Setujui">
                                            <i class="bi bi-check2-circle text-sm"></i>
                                        </button>
                                        <button @click="confirmDelete(s)" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div v-if="suratKeluar.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 text-xs text-slate-500">
                    <span>{{ suratKeluar.from }}–{{ suratKeluar.to }} dari {{ suratKeluar.total }}</span>
                    <div class="flex gap-1">
                        <a v-for="link in suratKeluar.links" :key="link.label" :href="link.url"
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
                        <h3 class="font-black text-slate-900 text-center mb-1">Hapus Surat Keluar?</h3>
                        <p class="text-xs text-slate-500 text-center mb-5">"{{ deleteTarget.perihal }}" akan dihapus permanen.</p>
                        <div class="flex gap-3">
                            <button @click="deleteTarget = null" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                            <form :action="`/surat-keluar/${deleteTarget.id}`" method="POST" class="flex-1">
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
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    suratKeluar: Object,
    jenisSurat: Array,
    filters: Object,
    canApprove: Boolean,
});

const filters = reactive({ ...props.filters });
const deleteTarget = ref(null);
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

function applyFilters() {
    router.get('/surat-keluar', filters, { preserveState: true, replace: true });
}
function resetFilters() {
    Object.assign(filters, { search: '', status: '', jenis_surat_id: '', tahun: '' });
    applyFilters();
}
function confirmDelete(s) { deleteTarget.value = s; }
function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}
function approveAction(id) {
    if (confirm('Setujui dan terbitkan surat ini?')) {
        router.post(`/surat-keluar/${id}/approve`, {}, { preserveState: false });
    }
}

const StatusBadge = {
    template: `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase" :class="cls">{{ label }}</span>`,
    props: ['value'],
    computed: {
        cls() { return { draft:'bg-slate-100 text-slate-500', pending:'bg-amber-100 text-amber-700', published:'bg-green-100 text-green-700' }[this.value] || 'bg-slate-100 text-slate-500'; },
        label() { return { draft:'Draft', pending:'Menunggu', published:'Diterbitkan' }[this.value] || this.value; }
    }
};
</script>
