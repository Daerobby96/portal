@props([
    'variant' => 'info',
    'dismissible' => false,
    'icon' => null,
])

@php
$variants = [
    'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
    'danger' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
];

$icons = [
    'success' => 'bi-check-circle-fill',
    'danger' => 'bi-exclamation-triangle-fill',
    'warning' => 'bi-exclamation-circle-fill',
    'info' => 'bi-info-circle-fill',
];

$defaultIcon = $icon ?? $icons[$variant];
@endphp

<div 
    {{ $attributes->merge(['class' => "relative flex items-start gap-3 px-4 py-3 rounded-lg border {$variants[$variant]}"]) }}
    @if($dismissible) x-data="{ show: true }" x-show="show" x-cloak @endif
>
    @if($defaultIcon)
    <i class="bi {{ $defaultIcon }} text-lg mt-0.5 flex-shrink-0"></i>
    @endif
    
    <div class="flex-1">
        {{ $slot }}
    </div>
    
    @if($dismissible)
    <button 
        @click="show = false"
        type="button" 
        class="flex-shrink-0 text-current opacity-50 hover:opacity-100 transition-opacity"
    >
        <i class="bi bi-x-lg"></i>
    </button>
    @endif
</div>
