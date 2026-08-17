<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Pegawai;
use App\Models\User;
use App\Imports\PegawaiImport;
use App\Exports\TemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with('user')->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'like', "%{$q}%")
                   ->orWhere('nip', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('unit_kerja', 'like', "%{$q}%")
                   ->orWhere('jabatan', 'like', "%{$q}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_pegawai', $request->jenis);
        }

        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', 'like', '%' . $request->unit_kerja . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_aktif', $request->status === 'aktif');
        }

        $pegawais = $query->paginate(15)->withQueryString();

        $stats = [
            'total'   => Pegawai::count(),
            'dosen'   => Pegawai::where('jenis_pegawai', Pegawai::JENIS_DOSEN)->count(),
            'tendik'  => Pegawai::where('jenis_pegawai', Pegawai::JENIS_TENDIK)->count(),
            'aktif'   => Pegawai::where('is_aktif', true)->count(),
        ];

        $unitKerjas = Pegawai::whereNotNull('unit_kerja')
            ->distinct()->orderBy('unit_kerja')
            ->pluck('unit_kerja');

        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get(); 
        $permissions = \Spatie\Permission\Models\Permission::orderBy('name')->get(); 
        
        return Inertia::render('Sdm/Pegawai/Index', [
            'pegawais'    => $pegawais,
            'stats'       => $stats,
            'unitKerjas'  => $unitKerjas,
            'roles'       => $roles,
            'permissions' => $permissions,
            'filters'     => [
                'search'     => $request->search ?? '',
                'jenis'      => $request->jenis ?? '',
                'unit_kerja' => $request->unit_kerja ?? '',
                'status'     => $request->status ?? '',
            ],
        ]);
    }

    public function create()
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        return Inertia::render('Sdm/Pegawai/Create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                => 'required|string|max:255',
            'nip'                 => 'nullable|string|max:50|unique:pegawais,nip',
            'email'               => 'nullable|email|max:255|unique:pegawais,email',
            'no_hp'               => 'nullable|string|max:50',
            'jabatan'             => 'nullable|string|max:255',
            'unit_kerja'          => 'nullable|string|max:255',
            'jenis_pegawai'       => 'required|in:Dosen,Tenaga Kependidikan,Lainnya',
            'status_kepegawaian'  => 'nullable|string|max:100',
            'user_id'             => 'nullable|exists:users,id',
        ]);

        Pegawai::create($request->only([
            'nip', 'nama', 'email', 'no_hp', 'jabatan', 'unit_kerja',
            'jenis_pegawai', 'status_kepegawaian', 'user_id',
        ]) + ['is_aktif' => true]);

        return redirect('/sdm/pegawai')
            ->with('success', "Pegawai \"{$request->nama}\" berhasil ditambahkan.");
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load('user');
        return Inertia::render('Sdm/Pegawai/Show', [
            'pegawai' => $pegawai,
        ]);
    }

    public function edit(Pegawai $pegawai)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        return Inertia::render('Sdm/Pegawai/Edit', [
            'pegawai' => $pegawai,
            'users'   => $users,
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama'                => 'required|string|max:255',
            'nip'                 => 'nullable|string|max:50|unique:pegawais,nip,' . $pegawai->id,
            'email'               => 'nullable|email|max:255|unique:pegawais,email,' . $pegawai->id,
            'no_hp'               => 'nullable|string|max:50',
            'jabatan'             => 'nullable|string|max:255',
            'unit_kerja'          => 'nullable|string|max:255',
            'jenis_pegawai'       => 'required|in:Dosen,Tenaga Kependidikan,Lainnya',
            'status_kepegawaian'  => 'nullable|string|max:100',
            'user_id'             => 'nullable|exists:users,id',
        ]);

        $pegawai->update($request->only([
            'nip', 'nama', 'email', 'no_hp', 'jabatan', 'unit_kerja',
            'jenis_pegawai', 'status_kepegawaian', 'user_id',
        ]) + ['is_aktif' => $request->boolean('is_aktif', true)]);

        return redirect('/sdm/pegawai')
            ->with('success', "Data pegawai \"{$pegawai->nama}\" berhasil diperbarui.");
    }

    public function createUser(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'password'     => 'required|string|min:8|confirmed',
            'roles'        => 'nullable|array',
            'roles.*'      => 'exists:roles,name',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'exists:permissions,name',
        ]);

        if ($pegawai->user_id) {
            return back()->with('error', 'Pegawai ini sudah ditautkan dengan akun User.');
        }

        if (empty($pegawai->email)) {
            return back()->with('error', 'Pegawai ini tidak memiliki email. Silakan edit data pegawai terlebih dahulu.');
        }

        $user = User::create([
            'name'       => $pegawai->nama,
            'nip'        => $pegawai->nip,
            'email'      => $pegawai->email,
            'unit_kerja' => $pegawai->unit_kerja,
            'jabatan'    => $pegawai->jabatan,
            'no_hp'      => $pegawai->no_hp,
            'password'   => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_active'  => true,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        $pegawai->update(['user_id' => $user->id]);

        return back()->with('success', "Akun User untuk \"{$pegawai->nama}\" berhasil dibuat dan ditautkan.");
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect('/sdm/pegawai')->with('success', "Pegawai \"{$pegawai->nama}\" berhasil dihapus.");
    }

    public function toggleStatus(Pegawai $pegawai)
    {
        $pegawai->update(['is_aktif' => !$pegawai->is_aktif]);
        $status = $pegawai->is_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Pegawai {$pegawai->nama} berhasil {$status}.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new PegawaiImport();
            Excel::import($import, $request->file('file'));
            return back()->with('success', 'Data pegawai berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = [
            'nip', 'nama', 'email', 'no_hp',
            'jabatan', 'unit_kerja',
            'jenis_pegawai',      // Dosen / Tenaga Kependidikan / Lainnya
            'status_kepegawaian', // PNS / PPPK / Honorer / Kontrak / Tetap Yayasan
        ];
        return Excel::download(
            new TemplateExport($headings, 'Template Pegawai'),
            'template-pegawai.xlsx'
        );
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $pegawais = Pegawai::where('is_aktif', true)
            ->where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('nip', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('jabatan', 'like', "%{$q}%")
                      ->orWhere('unit_kerja', 'like', "%{$q}%");
            })
            ->with('user')
            ->orderBy('nama')
            ->limit(10)
            ->get()
            ->map(fn($p) => [
                'source'     => 'pegawai',
                'pegawai_id' => $p->id,
                'user_id'    => $p->user_id,
                'name'       => $p->nama,
                'nip'        => $p->nip,
                'email'      => $p->email,
                'no_hp'      => $p->no_hp,
                'jabatan'    => $p->jabatan,
                'unit_kerja' => $p->unit_kerja,
                'jenis'      => $p->jenis_pegawai,
                'tipe'       => $p->user_id ? 'internal' : 'eksternal',
            ]);

        $pegawaiUserIds = $pegawais->pluck('user_id')->filter()->toArray();

        $users = User::where('is_active', true)
            ->whereNotIn('id', $pegawaiUserIds)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('nip', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('jabatan', 'like', "%{$q}%")
                      ->orWhere('unit_kerja', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->map(fn($u) => [
                'source'     => 'user',
                'pegawai_id' => null,
                'user_id'    => $u->id,
                'name'       => $u->name,
                'nip'        => $u->nip,
                'email'      => $u->email,
                'no_hp'      => $u->no_hp,
                'jabatan'    => $u->jabatan,
                'unit_kerja' => $u->unit_kerja,
                'jenis'      => Str::title(str_replace('_', ' ', $u->roles->first()?->name ?? '')),
                'tipe'       => 'internal',
            ]);

        return response()->json($pegawais->concat($users)->take(15)->values());
    }
}
