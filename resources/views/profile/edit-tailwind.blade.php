@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Ubah data pribadi Anda')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil Saya</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
{{-- Tailwind Grid Layout --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    
    {{-- Main Form --}}
    <div class="lg:col-span-8">
        <x-ui.card variant="primary">
            {{-- Card Header --}}
            <div class="flex items-center gap-2 pb-4 border-b border-slate-200 mb-4">
                <i class="bi bi-pencil text-primary text-lg"></i>
                <h6 class="font-bold text-slate-800 mb-0">Edit Data Profil</h6>
            </div>

            {{-- Form --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors @error('name') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        >
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">NIP</label>
                        <input 
                            type="text" 
                            name="nip"
                            value="{{ old('nip', $user->nip) }}"
                            placeholder="Nomor Induk Pegawai"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors @error('nip') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        >
                        @error('nip')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unit Kerja --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Unit Kerja</label>
                        <input 
                            type="text" 
                            name="unit_kerja"
                            value="{{ old('unit_kerja', $user->unit_kerja) }}"
                            placeholder="Fakultas/Prodi/Unit"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors @error('unit_kerja') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        >
                        @error('unit_kerja')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jabatan</label>
                        <input 
                            type="text" 
                            name="jabatan"
                            value="{{ old('jabatan', $user->jabatan) }}"
                            placeholder="Kepala, Staf, dll"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors @error('jabatan') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        >
                        @error('jabatan')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">No. HP</label>
                        <input 
                            type="text" 
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            placeholder="08xxxxxxxxxx"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 transition-colors @error('no_hp') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        >
                        @error('no_hp')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Foto Profil</label>
                        
                        @if($user->foto)
                            <div class="mb-3">
                                <img 
                                    src="{{ asset('storage/' . $user->foto) }}" 
                                    class="rounded-full ring-2 ring-slate-200 shadow-sm" 
                                    width="60" 
                                    height="60" 
                                    style="object-fit:cover"
                                    alt="Foto Profil"
                                >
                            </div>
                        @endif
                        
                        <input 
                            type="file" 
                            name="foto"
                            accept="image/jpeg,image/png"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors @error('foto') border-red-300 @enderror"
                        >
                        @error('foto')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-slate-500">JPG/PNG, max 2MB. Kosongkan jika tidak ingin mengubah.</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center justify-end gap-2 pt-4 border-t border-slate-200 mt-6">
                    <x-ui.button 
                        variant="outline" 
                        href="{{ route('profile.show') }}"
                        icon="bi-arrow-left"
                    >
                        Batal
                    </x-ui.button>
                    
                    <x-ui.button 
                        type="submit" 
                        variant="primary"
                        icon="bi-save"
                    >
                        Simpan Perubahan
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>

    {{-- Sidebar Info --}}
    <div class="lg:col-span-4 space-y-4">
        
        {{-- Info Akun Card --}}
        <x-ui.card variant="primary">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-200 mb-4">
                <i class="bi bi-info-circle text-primary text-lg"></i>
                <h6 class="font-bold text-slate-800 mb-0">Info Akun</h6>
            </div>

            <dl class="space-y-3">
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email</dt>
                    <dd class="text-sm text-slate-800 font-medium">{{ $user->email }}</dd>
                </div>
                
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Role</dt>
                    <dd class="text-sm text-slate-800 font-medium">
                        {{ Str::title(str_replace("_", " ", $user->roles->first()?->name ?? "User")) }}
                    </dd>
                </div>
                
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</dt>
                    <dd>
                        @if($user->is_active)
                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                        @else
                            <x-ui.badge variant="default">Nonaktif</x-ui.badge>
                        @endif
                    </dd>
                </div>
                
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Terdaftar</dt>
                    <dd class="text-sm text-slate-800 font-medium">
                        {{ $user->created_at->translatedFormat('d F Y') }}
                    </dd>
                </div>
            </dl>
        </x-ui.card>

        {{-- Security Card --}}
        <x-ui.card variant="primary">
            <div class="flex items-center gap-2 pb-4 border-b border-slate-200 mb-4">
                <i class="bi bi-key text-primary text-lg"></i>
                <h6 class="font-bold text-slate-800 mb-0">Keamanan</h6>
            </div>

            <p class="text-sm text-slate-600 mb-4">
                Ubah password akun Anda untuk menjaga keamanan.
            </p>

            <x-ui.button 
                variant="outline-primary" 
                href="{{ route('profile.settings') }}"
                icon="bi-gear"
                class="w-full justify-center"
            >
                Pengaturan Password
            </x-ui.button>
        </x-ui.card>
    </div>
</div>
@endsection
