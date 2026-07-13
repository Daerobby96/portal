@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-bold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-primary hover:bg-primary-dark text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0',
    'secondary' => 'bg-slate-600 hover:bg-slate-700 text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0',
    'warning' => 'bg-amber-500 hover:bg-amber-600 text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0',
    'outline' => 'border-2 border-slate-300 hover:border-slate-400 text-slate-700 hover:bg-slate-50 hover:-translate-y-0.5 active:translate-y-0',
    'outline-primary' => 'border-2 border-primary hover:bg-primary text-primary hover:text-white hover:-translate-y-0.5 active:translate-y-0',
    'ghost' => 'text-slate-700 hover:bg-slate-100 hover:text-slate-900',
    'link' => 'text-primary hover:text-primary-dark underline-offset-4 hover:underline',
];

$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs rounded-md gap-1',
    'sm' => 'px-3 py-2 text-sm rounded-lg gap-1.5',
    'md' => 'px-4 py-2.5 text-sm rounded-lg gap-2',
    'lg' => 'px-5 py-3 text-base rounded-xl gap-2',
    'xl' => 'px-6 py-3.5 text-lg rounded-xl gap-2.5',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="bi {{ $icon }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="bi {{ $icon }}"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="bi {{ $icon }}"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="bi {{ $icon }}"></i>
        @endif
    </button>
@endif
