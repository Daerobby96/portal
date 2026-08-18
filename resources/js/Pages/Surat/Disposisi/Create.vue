<template>
    <AuthenticatedLayout title="Buat Disposisi">
        <div class="max-w-2xl mx-auto space-y-5">
            <div class="flex items-center gap-3">
                <a :href="`/surat-masuk/${suratMasuk.id}`" class="w-9 h-9 rounded-xl flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition text-slate-500">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900">Buat Lembar Disposisi</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Delegasikan penanganan surat kepada pejabat/staf</p>
                </div>
            </div>

            <!-- Surat Info Card -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 shrink-0">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-amber-900">{{ suratMasuk.perihal }}</p>
                        <p class="text-[10px] text-amber-600 mt-1 font-mono">{{ suratMasuk.nomor_agenda }} · {{ suratMasuk.nomor_surat }}</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">Dari {{ suratMasuk.pengirim }} · Diterima {{ suratMasuk.tanggal_terima }}</p>
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase"
                            :class="sifatClass(suratMasuk.sifat)">{{ suratMasuk.sifat?.replace('_', ' ') }}</span>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                    <h2 class="font-black text-slate-800 text-sm pb-3 border-b border-slate-100">Instruksi Disposisi</h2>

                    <!-- Instruksi Cepat -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Instruksi Cepat</label>
                        <div class="flex flex-wrap gap-2">
                            <button v-for="instr in quickInstructions" :key="instr" type="button"
                                @click="appendInstruksi(instr)"
                                class="px-3 py-1.5 rounded-xl border text-xs font-semibold transition"
                                :class="form.isi_disposisi.includes(instr) ? 'border-amber-400 bg-amber-50 text-amber-700' : 'border-slate-200 text-slate-600 hover:border-amber-300 hover:bg-amber-50/50'">
                                {{ instr }}
                            </button>
                        </div>
                    </div>

                    <!-- Isi Disposisi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Arahan / Catatan Disposisi <span class="text-red-500">*</span></label>
                        <textarea v-model="form.isi_disposisi" required rows="4"
                            placeholder="Tuliskan arahan atau instruksi disposisi..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition resize-none"
                            :class="errors.isi_disposisi ? 'border-red-400' : ''"></textarea>
                        <p v-if="errors.isi_disposisi" class="text-red-500 text-[10px] mt-1">{{ errors.isi_disposisi }}</p>
                    </div>

                    <!-- Kepada -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Ditujukan Kepada <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input v-model="userSearch" type="text" placeholder="Cari nama pegawai..."
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-400 outline-none transition"
                                :class="errors.kepada_user_id ? 'border-red-400' : ''"
                                @focus="showUserDropdown = true"
                                @blur="setTimeout(() => showUserDropdown = false, 200)" />
                            <div v-if="showUserDropdown && filteredUsers.length > 0"
                                class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-20 max-h-48 overflow-y-auto">
                                <button v-for="u in filteredUsers" :key="u.id" type="button"
                                    @click="selectUser(u)"
                                    class="w-full text-left px-3.5 py-2.5 text-xs hover:bg-amber-50 transition flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-amber-500 to-orange-500 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                        {{ u.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ u.name }}</p>
                                        <p class="text-slate-400 text-[10px]">{{ u.email }}</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div v-if="selectedUser" class="mt-2 flex items-center gap-2 px-3 py-2 bg-amber-50 rounded-xl border border-amber-200 text-xs">
                            <div class="w-6 h-6 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center">
                                {{ selectedUser.name.charAt(0) }}
                            </div>
                            <span class="font-semibold text-amber-800">{{ selectedUser.name }}</span>
                            <button type="button" @click="clearUser" class="ml-auto text-amber-500 hover:text-red-500">
                                <i class="bi bi-x-circle text-sm"></i>
                            </button>
                        </div>
                        <p v-if="errors.kepada_user_id" class="text-red-500 text-[10px] mt-1">{{ errors.kepada_user_id }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Prioritas -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Prioritas <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <button v-for="p in ['rendah','sedang','tinggi']" :key="p" type="button"
                                    @click="form.prioritas = p"
                                    class="flex-1 py-2 rounded-xl text-xs font-bold transition border"
                                    :class="form.prioritas === p ? prioritasActive(p) : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                                    {{ p.charAt(0).toUpperCase() + p.slice(1) }}
                                </button>
                            </div>
                        </div>
                        <!-- Batas Waktu -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Waktu</label>
                            <input v-model="form.batas_waktu" type="date" :min="tomorrow"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500/30 outline-none transition" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pb-4">
                    <a :href="`/surat-masuk/${suratMasuk.id}`" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" :disabled="processing || !form.kepada_user_id" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold transition shadow-sm disabled:opacity-50 inline-flex items-center gap-2">
                        <i v-if="processing" class="bi bi-arrow-repeat animate-spin"></i>
                        <i v-else class="bi bi-send-fill"></i>
                        Kirim Disposisi
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ suratMasuk: Object, users: Array });

const form = reactive({
    kepada_user_id: null,
    isi_disposisi: '',
    batas_waktu: '',
    prioritas: 'sedang',
});

const errors = ref({});
const processing = ref(false);
const userSearch = ref('');
const selectedUser = ref(null);
const showUserDropdown = ref(false);

const quickInstructions = ['Tindak lanjuti', 'Hadiri / Wakilkan', 'Pelajari / Telaah', 'Koordinasikan', 'Arsipkan', 'Siapkan jawaban'];

const tomorrow = computed(() => {
    const d = new Date(); d.setDate(d.getDate() + 1);
    return d.toISOString().split('T')[0];
});

const filteredUsers = computed(() => {
    if (!userSearch.value) return props.users.slice(0, 8);
    return props.users.filter(u => u.name.toLowerCase().includes(userSearch.value.toLowerCase()) || u.email.toLowerCase().includes(userSearch.value.toLowerCase())).slice(0, 8);
});

function selectUser(u) {
    selectedUser.value = u;
    form.kepada_user_id = u.id;
    userSearch.value = '';
    showUserDropdown.value = false;
}
function clearUser() { selectedUser.value = null; form.kepada_user_id = null; }

function appendInstruksi(instr) {
    if (form.isi_disposisi.includes(instr)) {
        form.isi_disposisi = form.isi_disposisi.replace(instr + '\n', '').replace(instr, '').trim();
    } else {
        form.isi_disposisi = form.isi_disposisi ? form.isi_disposisi + '\n' + instr : instr;
    }
}

function prioritasActive(p) {
    return { rendah: 'border-slate-500 bg-slate-100 text-slate-800', sedang: 'border-amber-500 bg-amber-100 text-amber-800', tinggi: 'border-red-500 bg-red-100 text-red-800' }[p];
}

function sifatClass(s) {
    return { rahasia:'bg-red-100 text-red-700', segera:'bg-orange-100 text-orange-700', sangat_segera:'bg-red-100 text-red-800', biasa:'bg-amber-50 text-amber-700' }[s] || 'bg-slate-100 text-slate-500';
}

function submit() {
    processing.value = true;
    router.post(`/surat-masuk/${props.suratMasuk.id}/disposisi`, form, {
        onError: e => { errors.value = e; processing.value = false; },
        onFinish: () => { processing.value = false; },
    });
}
</script>
