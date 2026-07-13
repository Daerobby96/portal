<?php
namespace Modules\SystemAdmin\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('systemadmin::settings.index', compact('settings'));
    }

    /**
     * Update pengaturan
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name'         => 'required|string|max:50',
            'app_tagline'      => 'nullable|string|max:100',
            'theme_primary'    => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'theme_sidebar'    => 'required|in:dark,light',
            'logo'             => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon'          => 'nullable|image|mimes:ico,png|max:512',
            'logo_institusi'   => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'nama_institusi'   => 'nullable|string|max:100',
            'alamat_institusi' => 'nullable|string|max:255',
            'kota_institusi'   => 'nullable|string|max:50',
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

        // Upload logo
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

        // Clear all cache
        Setting::clearCache();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Reset ke default
     */
    public function reset()
    {
        Setting::set('app_name', 'SPMI');
        Setting::set('app_tagline', 'Sistem Penjaminan Mutu Internal');
        Setting::set('theme_primary', '#4e73df');
        Setting::set('theme_sidebar', 'dark');

        // Delete uploaded files
        $logo = Setting::get('logo');
        $favicon = Setting::get('favicon');
        if ($logo) Storage::disk('public')->delete($logo);
        if ($favicon) Storage::disk('public')->delete($favicon);
        
        Setting::set('logo', null);
        Setting::set('favicon', null);

        // Clear all cache
        Setting::clearCache();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil direset ke default.');
    }
}
