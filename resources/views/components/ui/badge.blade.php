@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-full';

$variants = [
    'default' => 'bg-slate-100 text-slate-800',
    'primary' => 'bg-primary/10 text-primary border border-primary/20',
    'success' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
    'danger' => 'bg-red-100 text-red-800 border border-red-200',
    'warning' => 'bg-amber-100 text-amber-800 border border-amber-200',
    'info' => 'bg-blue-100 text-blue-800 border border-blue-200',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1.5 text-sm',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
