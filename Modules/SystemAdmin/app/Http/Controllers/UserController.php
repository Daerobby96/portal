<?php

namespace Modules\SystemAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('nip', 'ilike', "%{$search}%")
                  ->orWhere('unit_kerja', 'ilike', "%{$search}%")
                  ->orWhere('jabatan', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users = $query->paginate(12)->withQueryString();
        $roles = Role::orderBy('name')->get(['id', 'name']);
        
        $stats = [
            'total'    => User::count(),
            'aktif'    => User::where('is_active', true)->count(),
            'nonaktif' => User::where('is_active', false)->count(),
            'roles_count' => Role::count(),
        ];

        return Inertia::render('SystemAdmin/Users/Index', [
            'users'   => $users,
            'roles'   => $roles,
            'stats'   => $stats,
            'filters' => [
                'search' => $request->search ?? '',
                'role'   => $request->role ?? '',
                'status' => $request->status ?? '',
            ],
        ]);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('SystemAdmin/Users/Create', [
            'roles'       => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nip'          => 'nullable|string|max:30|unique:users,nip',
            'email'        => 'required|email|unique:users,email',
            'unit_kerja'   => 'nullable|string|max:255',
            'jabatan'      => 'nullable|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'password'     => 'required|string|min:8|confirmed',
            'roles'        => 'nullable|array',
            'roles.*'      => 'exists:roles,name',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'exists:permissions,name',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-user', 'public');
        }

        $user = User::create([
            'name'       => $request->name,
            'nip'        => $request->nip,
            'email'      => $request->email,
            'unit_kerja' => $request->unit_kerja,
            'jabatan'    => $request->jabatan,
            'no_hp'      => $request->no_hp,
            'password'   => Hash::make($request->password),
            'foto'       => $fotoPath,
            'is_active'  => true,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna {$request->name} berhasil didaftarkan.");
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions']);

        return Inertia::render('SystemAdmin/Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        $user->load(['roles', 'permissions']);
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();
        $userPermissions = $user->permissions->pluck('name')->toArray();

        return Inertia::render('SystemAdmin/Users/Edit', [
            'user'            => $user,
            'roles'           => $roles,
            'permissions'     => $permissions,
            'userRoles'       => $userRoles,
            'userPermissions' => $userPermissions,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nip'          => 'nullable|string|max:30|unique:users,nip,' . $user->id,
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'unit_kerja'   => 'nullable|string|max:255',
            'jabatan'      => 'nullable|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'password'     => 'nullable|string|min:8|confirmed',
            'roles'        => 'nullable|array',
            'roles.*'      => 'exists:roles,name',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'exists:permissions,name',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'name', 'nip', 'email',
            'unit_kerja', 'jabatan', 'no_hp',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user->update($data);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles ?? []);
        }
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions ?? []);
        }

        return redirect()->route('users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->foto) Storage::disk('public')->delete($user->foto);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            if (class_exists(\App\Imports\UserImport::class)) {
                Excel::import(new \App\Imports\UserImport, $request->file('file'));
                return back()->with('success', 'Data pengguna berhasil diimport.');
            }
            return back()->with('error', 'Fitur UserImport sedang dalam pemeliharaan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        if (class_exists(\App\Exports\TemplateExport::class)) {
            $headings = ['nama', 'email', 'role', 'nip', 'unit_kerja', 'jabatan', 'password'];
            return Excel::download(new \App\Exports\TemplateExport($headings, 'Template User'), 'template-user.xlsx');
        }
        return back()->with('error', 'Template download belum tersedia.');
    }
}
