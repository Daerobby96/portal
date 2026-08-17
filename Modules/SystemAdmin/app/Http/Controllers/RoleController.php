<?php

namespace Modules\SystemAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        $permissions = Permission::orderBy('name')->get();

        // Group permissions logically by prefix/domain
        $groupedPermissions = [];
        foreach ($permissions as $p) {
            $parts = explode('.', $p->name);
            $group = count($parts) > 1 ? ucfirst($parts[0]) : 'Umum';
            $groupedPermissions[$group][] = $p;
        }

        return Inertia::render('SystemAdmin/Roles/Index', [
            'roles'              => $roles,
            'groupedPermissions' => $groupedPermissions,
            'allPermissions'     => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:50|unique:roles,name',
            'display_name' => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:255',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name'         => strtolower(str_replace(' ', '_', $request->name)),
            'guard_name'   => 'web',
            'display_name' => $request->display_name ?? ucwords(str_replace('_', ' ', $request->name)),
            'description'  => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', "Role {$role->display_name} berhasil ditambahkan.");
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:255',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'exists:permissions,name',
        ]);

        $role->update([
            'display_name' => $request->display_name ?? $role->display_name,
            'description'  => $request->description ?? $role->description,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', "Hak akses role {$role->name} berhasil diperbarui.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super_admin', 'auditor', 'auditee', 'pimpinan', 'staff'])) {
            return back()->with('error', 'Role bawaan sistem inti tidak dapat dihapus.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', "Role {$role->name} masih memiliki {$role->users()->count()} pengguna aktif.");
        }

        $role->delete();

        return back()->with('success', 'Role berhasil dihapus.');
    }
}
