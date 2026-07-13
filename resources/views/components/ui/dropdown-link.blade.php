@props([
    'icon' => null,
])

<a {{ $attributes->merge(['class' => 'flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors']) }}>
    @if($icon)
        <i class="bi {{ $icon }} text-slate-400"></i>
    @endif
    {{ $slot }}
</a>
