<?php

namespace Modules\SystemAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem & institusi
     */
    public function index()
    {
        Setting::clearCache();

        $settings = [
            // General & Branding
            'app_name'          => Setting::get('app_name', 'ERP-POLKA'),
            'app_tagline'       => Setting::get('app_tagline', 'Integrated Campus Enterprise Resource Planning'),
            'theme_primary'     => Setting::get('theme_primary', '#4f46e5'),
            'theme_sidebar'     => Setting::get('theme_sidebar', 'dark'),
            'logo'              => Setting::get('logo') ? asset('storage/' . Setting::get('logo')) : null,
            'favicon'           => Setting::get('favicon') ? asset('storage/' . Setting::get('favicon')) : null,
            
            // Institusi
            'nama_institusi'    => Setting::get('nama_institusi', 'POLITEKNIK KAMPUS AKADEMIK'),
            'alamat_institusi'  => Setting::get('alamat_institusi', 'Jl. Raya Kampus Terpadu No. 1, Indonesia'),
            'kota_institusi'    => Setting::get('kota_institusi', 'Kota'),
            'email_institusi'   => Setting::get('email_institusi', 'info@polka.ac.id'),
            'telepon_institusi' => Setting::get('telepon_institusi', '(021) 12345678'),
            'website_institusi' => Setting::get('website_institusi', 'https://polka.ac.id'),
            'logo_institusi'    => Setting::get('logo_institusi') ? asset('storage/' . Setting::get('logo_institusi')) : null,
            
            // Kop Surat Resmi
            'kop_surat_yayasan' => Setting::get('kop_surat_yayasan') ? asset('storage/' . Setting::get('kop_surat_yayasan')) : null,
            'kop_surat_pt'      => Setting::get('kop_surat_pt') ? asset('storage/' . Setting::get('kop_surat_pt')) : null,
        ];

        $serverInfo = [
            'php_version'     => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI / Built-in',
            'db_connection'   => config('database.default'),
            'app_env'         => config('app.env'),
            'debug_mode'      => config('app.debug'),
            'timezone'        => config('app.timezone'),
        ];

        return Inertia::render('SystemAdmin/Settings/Index', [
            'settings'   => $settings,
            'serverInfo' => $serverInfo,
        ]);
    }

    /**
     * Update pengaturan sistem & institusi
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name'          => 'required|string|max:50',
            'app_tagline'       => 'nullable|string|max:150',
            'theme_primary'     => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'theme_sidebar'     => 'required|in:dark,light',
            'nama_institusi'    => 'nullable|string|max:150',
            'alamat_institusi'  => 'nullable|string|max:255',
            'kota_institusi'    => 'nullable|string|max:100',
            'email_institusi'   => 'nullable|email|max:100',
            'telepon_institusi' => 'nullable|string|max:50',
            'website_institusi' => 'nullable|string|max:150',
            'logo'              => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon'           => 'nullable|image|mimes:ico,png|max:512',
            'logo_institusi'    => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'kop_surat_yayasan' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
            'kop_surat_pt'      => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
        ]);

        // Update text settings
        Setting::set('app_name', $request->app_name);
        Setting::set('app_tagline', $request->app_tagline);
        Setting::set('theme_primary', $request->theme_primary);
        Setting::set('theme_sidebar', $request->theme_sidebar);
        
        // Update institusi settings
        Setting::set('nama_institusi', $request->nama_institusi);
        Setting::set('alamat_institusi', $request->alamat_institusi);
        Setting::set('kota_institusi', $request->kota_institusi);
        Setting::set('email_institusi', $request->email_institusi);
        Setting::set('telepon_institusi', $request->telepon_institusi);
        Setting::set('website_institusi', $request->website_institusi);

        // Upload logo aplikasi
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $logoPath);
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon) Storage::disk('public')->delete($oldFavicon);
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $faviconPath);
        }

        // Upload logo institusi
        if ($request->hasFile('logo_institusi')) {
            $oldLogoInstitusi = Setting::get('logo_institusi');
            if ($oldLogoInstitusi) Storage::disk('public')->delete($oldLogoInstitusi);
            $logoInstitusiPath = $request->file('logo_institusi')->store('settings', 'public');
            Setting::set('logo_institusi', $logoInstitusiPath);
        }

        // Upload kop surat Yayasan
        if ($request->hasFile('kop_surat_yayasan')) {
            $oldKop = Setting::get('kop_surat_yayasan');
            if ($oldKop) Storage::disk('public')->delete($oldKop);
            $kopPath = $request->file('kop_surat_yayasan')->store('kop_surat', 'public');
            Setting::set('kop_surat_yayasan', $kopPath);
        }

        // Upload kop surat Perguruan Tinggi
        if ($request->hasFile('kop_surat_pt')) {
            $oldKop = Setting::get('kop_surat_pt');
            if ($oldKop) Storage::disk('public')->delete($oldKop);
            $kopPath = $request->file('kop_surat_pt')->store('kop_surat', 'public');
            Setting::set('kop_surat_pt', $kopPath);
        }

        // Clear all caches
        Setting::clearCache();
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            // Ignore in development
        }

        return redirect()->route('settings.index')
            ->with('success', 'Konfigurasi pengaturan sistem dan identitas institusi berhasil diperbarui.');
    }

    /**
     * Clear Cache Sistem
     */
    public function clearCache()
    {
        Setting::clearCache();
        try {
            Artisan::call('optimize:clear');
        } catch (\Exception $e) {
            // fallback
        }

        return redirect()->route('settings.index')
            ->with('success', 'Semua cache aplikasi, route, view, dan konfigurasi berhasil dibersihkan.');
    }

    /**
     * Reset ke default
     */
    public function reset()
    {
        Setting::set('app_name', 'ERP-POLKA');
        Setting::set('app_tagline', 'Integrated Campus Enterprise Resource Planning');
        Setting::set('theme_primary', '#4f46e5');
        Setting::set('theme_sidebar', 'dark');

        // Delete uploaded files if any
        $logo = Setting::get('logo');
        $favicon = Setting::get('favicon');
        if ($logo) Storage::disk('public')->delete($logo);
        if ($favicon) Storage::disk('public')->delete($favicon);
        
        Setting::set('logo', null);
        Setting::set('favicon', null);

        Setting::clearCache();
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            // fallback
        }

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan tampilan berhasil direset ke konfigurasi default.');
    }
}
