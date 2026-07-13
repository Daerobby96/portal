@props([
    'variant' => 'default',
    'padding' => 'default',
    'hover' => false,
])

@php
$baseClasses = 'bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden';

$variants = [
    'default' => '',
    'primary' => 'border-l-4 border-l-primary',
    'success' => 'border-l-4 border-l-emerald-500',
    'danger' => 'border-l-4 border-l-red-500',
    'warning' => 'border-l-4 border-l-amber-500',
    'glass' => 'backdrop-blur-xl bg-white/70 border-white/30 shadow-xl',
];

$paddings = [
    'none' => '',
    'sm' => 'p-3',
    'default' => 'p-4',
    'lg' => 'p-6',
    'xl' => 'p-8',
];

$hoverClass = $hover ? 'transition-all duration-200 hover:-translate-y-1 hover:shadow-md' : '';

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $paddings[$padding] . ' ' . $hoverClass;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
