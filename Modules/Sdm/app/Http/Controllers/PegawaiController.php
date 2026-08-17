<?php

namespace Modules\Sdm\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sdm\Models\Pegawai;
use Modules\DataMaster\Models\UnitKerja;
use Modules\DataMaster\Models\Jabatan;
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
        $query = Pegawai::with(['user', 'unitKerjaRel', 'jabatanRel'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama', 'ilike', "%{$q}%")
                   ->orWhere('nip', 'ilike', "%{$q}%")
                   ->orWhere('email', 'ilike', "%{$q}%")
                   ->orWhere('unit_kerja', 'ilike', "%{$q}%")
                   ->orWhere('jabatan', 'ilike', "%{$q}%")
                   ->orWhereHas('unitKerjaRel', function($uq) use ($q) {
                       $uq->where('nama', 'ilike', "%{$q}%")->orWhere('kode', 'ilike', "%{$q}%");
                   })
                   ->orWhereHas('jabatanRel', function($jq) use ($q) {
                       $jq->where('nama', 'ilike', "%{$q}%")->orWhere('kode', 'ilike', "%{$q}%");
                   });
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_pegawai', $request->jenis);
        }

        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->unit_kerja_id);
        } elseif ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', 'ilike', '%' . $request->unit_kerja . '%');
        }

        if ($request->filled('jabatan_id')) {
            $query->where('jabatan_id', $request->jabatan_id);
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

        $unitKerjas = UnitKerja::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'kode', 'tipe']);
        $jabatans = Jabatan::where('is_aktif', true)->orderBy('level_hirarki')->orderBy('nama')->get(['id', 'nama', 'kode', 'kategori']);

        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get(); 
        $permissions = \Spatie\Permission\Models\Permission::orderBy('name')->get(); 
        
        return Inertia::render('Sdm/Pegawai/Index', [
            'pegawais'    => $pegawais,
            'stats'       => $stats,
            'unitKerjas'  => $unitKerjas,
            'jabatans'    => $jabatans,
            'roles'       => $roles,
            'permissions' => $permissions,
            'filters'     => [
                'search'        => $request->search ?? '',
                'jenis'         => $request->jenis ?? '',
                'unit_kerja_id' => $request->unit_kerja_id ?? '',
                'jabatan_id'    => $request->jabatan_id ?? '',
                'status'        => $request->status ?? '',
            ],
        ]);
    }

    public function create()
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $unitKerjas = UnitKerja::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'kode', 'tipe']);
        $jabatans = Jabatan::where('is_aktif', true)->orderBy('level_hirarki')->orderBy('nama')->get(['id', 'nama', 'kode', 'kategori']);

        return Inertia::render('Sdm/Pegawai/Create', [
            'users'      => $users,
            'unitKerjas' => $unitKerjas,
            'jabatans'   => $jabatans,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'                => 'required|string|max:255',
            'nip'                 => 'nullable|string|max:50|unique:pegawais,nip',
            'email'               => 'nullable|email|max:255|unique:pegawais,email',
            'no_hp'               => 'nullable|string|max:50',
            'unit_kerja_id'       => 'nullable|exists:unit_kerjas,id',
            'jabatan_id'          => 'nullable|exists:jabatans,id',
            'jabatan'             => 'nullable|string|max:255',
            'unit_kerja'          => 'nullable|string|max:255',
            'jenis_pegawai'       => 'required|in:Dosen,Tenaga Kependidikan,Lainnya',
            'status_kepegawaian'  => 'nullable|string|max:100',
            'user_id'             => 'nullable|exists:users,id',
        ]);

        $data = $request->only([
            'nip', 'nama', 'email', 'no_hp', 'unit_kerja_id', 'jabatan_id',
            'jenis_pegawai', 'status_kepegawaian', 'user_id',
        ]) + ['is_aktif' => true];

        if ($request->filled('unit_kerja_id')) {
            $uk = UnitKerja::find($request->unit_kerja_id);
            $data['unit_kerja'] = $uk?->nama ?? $request->unit_kerja;
        } else {
            $data['unit_kerja'] = $request->unit_kerja;
        }

        if ($request->filled('jabatan_id')) {
            $jb = Jabatan::find($request->jabatan_id);
            $data['jabatan'] = $jb?->nama ?? $request->jabatan;
        } else {
            $data['jabatan'] = $request->jabatan;
        }

        Pegawai::create($data);

        return redirect('/sdm/pegawai')
            ->with('success', "Pegawai \"{$request->nama}\" berhasil didaftarkan.");
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['user', 'unitKerjaRel', 'jabatanRel']);
        return Inertia::render('Sdm/Pegawai/Show', [
            'pegawai' => $pegawai,
        ]);
    }

    public function edit(Pegawai $pegawai)
    {
        $pegawai->load(['unitKerjaRel', 'jabatanRel']);
        $users = User::where('is_active', true)->orderBy('name')->get();
        $unitKerjas = UnitKerja::where('is_aktif', true)->orderBy('nama')->get(['id', 'nama', 'kode', 'tipe']);
        $jabatans = Jabatan::where('is_aktif', true)->orderBy('level_hirarki')->orderBy('nama')->get(['id', 'nama', 'kode', 'kategori']);

        return Inertia::render('Sdm/Pegawai/Edit', [
            'pegawai'    => $pegawai,
            'users'      => $users,
            'unitKerjas' => $unitKerjas,
            'jabatans'   => $jabatans,
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama'                => 'required|string|max:255',
            'nip'                 => 'nullable|string|max:50|unique:pegawais,nip,' . $pegawai->id,
            'email'               => 'nullable|email|max:255|unique:pegawais,email,' . $pegawai->id,
            'no_hp'               => 'nullable|string|max:50',
            'unit_kerja_id'       => 'nullable|exists:unit_kerjas,id',
            'jabatan_id'          => 'nullable|exists:jabatans,id',
            'jabatan'             => 'nullable|string|max:255',
            'unit_kerja'          => 'nullable|string|max:255',
            'jenis_pegawai'       => 'required|in:Dosen,Tenaga Kependidikan,Lainnya',
            'status_kepegawaian'  => 'nullable|string|max:100',
            'user_id'             => 'nullable|exists:users,id',
        ]);

        $data = $request->only([
            'nip', 'nama', 'email', 'no_hp', 'unit_kerja_id', 'jabatan_id',
            'jenis_pegawai', 'status_kepegawaian', 'user_id',
        ]) + ['is_aktif' => $request->boolean('is_aktif', true)];

        if ($request->filled('unit_kerja_id')) {
            $uk = UnitKerja::find($request->unit_kerja_id);
            $data['unit_kerja'] = $uk?->nama ?? $request->unit_kerja;
        } else {
            $data['unit_kerja'] = $request->unit_kerja;
        }

        if ($request->filled('jabatan_id')) {
            $jb = Jabatan::find($request->jabatan_id);
            $data['jabatan'] = $jb?->nama ?? $request->jabatan;
        } else {
            $data['jabatan'] = $request->jabatan;
        }

        $pegawai->update($data);

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
            return back()->with('error', 'Pegawai harus memiliki alamat email untuk dibuatkan akun.');
        }

        $user = User::create([
            'name'       => $pegawai->nama,
            'nip'        => $pegawai->nip,
            'email'      => $pegawai->email,
            'no_hp'      => $pegawai->no_hp,
            'unit_kerja' => $pegawai->nama_unit_kerja,
            'jabatan'    => $pegawai->nama_jabatan,
            'password'   => bcrypt($request->password),
            'is_active'  => true,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        $pegawai->update(['user_id' => $user->id]);

        return back()->with('success', "Akun login berhasil dibuat untuk \"{$pegawai->nama}\".");
    }

    public function toggleStatus(Pegawai $pegawai)
    {
        $pegawai->update(['is_aktif' => !$pegawai->is_aktif]);
        $status = $pegawai->is_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pegawai \"{$pegawai->nama}\" berhasil {$status}.");
    }

    public function destroy(Pegawai $pegawai)
    {
        $nama = $pegawai->nama;
        $pegawai->delete();

        return redirect('/sdm/pegawai')
            ->with('success', "Pegawai \"{$nama}\" berhasil dihapus.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new PegawaiImport, $request->file('file'));
            return back()->with('success', 'Data pegawai berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headings = ['nip', 'nama', 'email', 'no_hp', 'jabatan', 'unit_kerja', 'jenis_pegawai', 'status_kepegawaian'];
        return Excel::download(new TemplateExport($headings, 'Template Pegawai'), 'template-pegawai.xlsx');
    }
}
