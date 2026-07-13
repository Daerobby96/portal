@props([
    'type' => 'text',
    'error' => null,
    'label' => null,
    'required' => false,
])

<div {{ $attributes->only('class') }}>
    @if($label)
    <label class="block text-sm font-medium text-slate-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <input 
        type="{{ $type }}"
        {{ $attributes->except('class')->merge([
            'class' => 'block w-full rounded-lg border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors ' . ($error ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : '')
        ]) }}
    >
    
    @if($error)
    <p class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
