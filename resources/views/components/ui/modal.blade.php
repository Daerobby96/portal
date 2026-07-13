@props([
    'id' => null,
    'title' => '',
    'size' => 'md',
    'closeButton' => true,
])

@php
$sizes = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-7xl',
];
@endphp

<div 
    x-data="{ open: false }"
    x-on:open-modal-{{ $id }}.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
    ></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="open = false"
            {{ $attributes->merge(['class' => "relative w-full {$sizes[$size]} bg-white rounded-2xl shadow-xl border border-slate-200"]) }}
        >
            <!-- Header -->
            @if($title || $closeButton)
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h3 class="text-lg font-bold text-slate-800">{{ $title }}</h3>
                @if($closeButton)
                <button 
                    @click="open = false"
                    type="button"
                    class="text-slate-400 hover:text-slate-600 transition-colors"
                >
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
                @endif
            </div>
            @endif

            <!-- Body -->
            <div class="px-6 py-4">
                {{ $slot }}
            </div>

            <!-- Footer (optional) -->
            @isset($footer)
            <div class="border-t border-slate-200 px-6 py-4 bg-slate-50 rounded-b-2xl">
                {{ $footer }}
            </div>
            @endisset
        </div>
    </div>
</div>
