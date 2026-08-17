<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Pilih salah satu...',
    },
    searchPlaceholder: {
        type: String,
        default: 'Ketik untuk mencari...',
    },
    allowClear: {
        type: Boolean,
        default: true,
    },
    allowCustom: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const isOpen = ref(false);
const searchQuery = ref('');
const rootRef = ref(null);
const searchInputRef = ref(null);

// Normalize options to [{ value, label, subtext, badge }]
const normalizedOptions = computed(() => {
    return props.options.map(opt => {
        if (typeof opt === 'object' && opt !== null) {
            return {
                value: opt.value !== undefined ? opt.value : (opt.id !== undefined ? opt.id : opt.kode),
                label: opt.label || opt.nama || opt.name || String(opt.value || opt.id || opt.kode),
                subtext: opt.subtext || opt.tipe || opt.kategori || opt.email || '',
                badge: opt.badge || opt.jenjang || '',
                raw: opt,
            };
        }
        return {
            value: opt,
            label: String(opt),
            subtext: '',
            badge: '',
            raw: opt,
        };
    });
});

const selectedOption = computed(() => {
    if (props.modelValue === '' || props.modelValue === null || props.modelValue === undefined) {
        return null;
    }
    return normalizedOptions.value.find(opt => String(opt.value) === String(props.modelValue)) || null;
});

const filteredOptions = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return normalizedOptions.value;
    return normalizedOptions.value.filter(opt => {
        const matchLabel = opt.label.toLowerCase().includes(q);
        const matchSubtext = opt.subtext ? opt.subtext.toLowerCase().includes(q) : false;
        const matchValue = String(opt.value).toLowerCase().includes(q);
        return matchLabel || matchSubtext || matchValue;
    });
});

const toggleDropdown = () => {
    if (props.disabled) return;
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = '';
        nextTick(() => {
            searchInputRef.value?.focus();
        });
    }
};

const closeDropdown = () => {
    isOpen.value = false;
    searchQuery.value = '';
};

const selectOption = (opt) => {
    emit('update:modelValue', opt.value);
    emit('change', opt);
    closeDropdown();
};

const selectCustom = () => {
    if (!props.allowCustom || !searchQuery.value.trim()) return;
    const val = searchQuery.value.trim();
    emit('update:modelValue', val);
    emit('change', { value: val, label: val, raw: val });
    closeDropdown();
};

const clearSelection = (e) => {
    e.stopPropagation();
    emit('update:modelValue', '');
    emit('change', null);
};

const handleClickOutside = (e) => {
    if (rootRef.value && !rootRef.value.contains(e.target)) {
        closeDropdown();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="rootRef" class="relative w-full text-xs">
        <!-- Trigger Button -->
        <div
            @click="toggleDropdown"
            class="w-full min-h-[38px] px-3.5 py-2 rounded-xl border transition flex items-center justify-between gap-2 cursor-pointer select-none bg-white"
            :class="[
                error ? 'border-rose-300 ring-1 ring-rose-200' : 'border-slate-200 hover:border-slate-300',
                isOpen ? 'ring-2 ring-indigo-500/30 border-indigo-500 shadow-sm' : '',
                disabled ? 'bg-slate-50 opacity-60 cursor-not-allowed pointer-events-none' : ''
            ]"
        >
            <div class="flex items-center gap-2 flex-1 min-w-0">
                <template v-if="selectedOption">
                    <span class="font-bold text-slate-900 truncate">
                        {{ selectedOption.label }}
                    </span>
                    <span
                        v-if="selectedOption.badge || selectedOption.subtext"
                        class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/60 uppercase tracking-tight shrink-0"
                    >
                        {{ selectedOption.badge || selectedOption.subtext }}
                    </span>
                </template>
                <template v-else-if="modelValue && allowCustom">
                    <span class="font-bold text-slate-900 truncate">
                        {{ modelValue }}
                    </span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-amber-50 text-amber-700 font-bold border border-amber-200">
                        Custom
                    </span>
                </template>
                <span v-else class="text-slate-400 font-medium truncate">
                    {{ placeholder }}
                </span>
            </div>

            <div class="flex items-center gap-1.5 shrink-0 text-slate-400">
                <button
                    v-if="allowClear && (selectedOption || modelValue)"
                    @click="clearSelection"
                    type="button"
                    class="p-0.5 rounded-md hover:text-rose-500 hover:bg-rose-50 transition cursor-pointer"
                    title="Kosongkan pilihan"
                >
                    <i class="bi bi-x-lg text-[10px]"></i>
                </button>
                <i class="bi text-xs transition duration-200" :class="isOpen ? 'bi-chevron-up text-indigo-600' : 'bi-chevron-down'"></i>
            </div>
        </div>

        <!-- Dropdown Popover -->
        <div
            v-if="isOpen"
            class="absolute z-50 left-0 right-0 mt-1.5 bg-white rounded-2xl shadow-xl border border-slate-200/90 overflow-hidden animate-in fade-in zoom-in-95 duration-150"
        >
            <!-- Search Box -->
            <div class="p-2 border-b border-slate-100 bg-slate-50/60">
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input
                        ref="searchInputRef"
                        v-model="searchQuery"
                        @keydown.enter.prevent="selectCustom"
                        @keydown.esc="closeDropdown"
                        type="text"
                        :placeholder="searchPlaceholder"
                        class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white font-medium text-slate-800"
                    />
                </div>
            </div>

            <!-- Options List -->
            <div class="max-h-56 overflow-y-auto p-1 divide-y divide-slate-50">
                <div
                    v-for="opt in filteredOptions"
                    :key="opt.value"
                    @click="selectOption(opt)"
                    class="px-3 py-2 rounded-xl flex items-center justify-between gap-2 cursor-pointer transition select-none"
                    :class="String(opt.value) === String(modelValue) ? 'bg-indigo-50/80 text-indigo-900 font-bold' : 'hover:bg-slate-50 text-slate-700'"
                >
                    <div class="flex-1 min-w-0">
                        <div class="truncate text-xs">{{ opt.label }}</div>
                        <div v-if="opt.subtext" class="text-[11px] text-slate-400 truncate mt-0.5 capitalize">
                            {{ opt.subtext }}
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <span
                            v-if="opt.badge"
                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/50"
                        >
                            {{ opt.badge }}
                        </span>
                        <i
                            v-if="String(opt.value) === String(modelValue)"
                            class="bi bi-check-lg text-indigo-600 font-bold text-sm"
                        ></i>
                    </div>
                </div>

                <!-- Custom / Not Found State -->
                <div v-if="filteredOptions.length === 0" class="p-3 text-center text-slate-400 space-y-2">
                    <p class="text-xs">Tidak ditemukan opsi yang cocok.</p>
                    <button
                        v-if="allowCustom && searchQuery.trim()"
                        @click="selectCustom"
                        type="button"
                        class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-xs font-bold transition flex items-center gap-1.5 mx-auto"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Gunakan "{{ searchQuery.trim() }}"</span>
                    </button>
                </div>
            </div>
        </div>

        <p v-if="error" class="text-rose-500 text-[11px] mt-1">{{ error }}</p>
    </div>
</template>
