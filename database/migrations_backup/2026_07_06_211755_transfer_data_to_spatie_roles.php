<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Transfer existing roles & users to Spatie permission tables,
     * then create module permissions and drop the old role_id column.
     */
    public function up(): void
    {
        // 1. Create module-level permissions
        $modulePermissions = [
            // SPMI (legacy roles control access; no separate permission needed)
            ['name' => 'access_spmi',          'display' => 'Akses Modul SPMI'],
            // Non-SPMI modules
            ['name' => 'access_data_akademik', 'display' => 'Akses Modul Data Akademik'],
            ['name' => 'access_tracer_study',  'display' => 'Akses Modul Tracer Study'],
            ['name' => 'access_data_master',   'display' => 'Akses Modul Data Master'],
            ['name' => 'access_tridharma',     'display' => 'Akses Modul Tridharma'],
            ['name' => 'access_kerjasama',     'display' => 'Akses Modul Kerjasama'],
            ['name' => 'access_rapat',         'display' => 'Akses Modul Manajemen Rapat'],
            ['name' => 'access_system_admin',  'display' => 'Akses Modul System Admin'],
        ];

        $now = now();
        foreach ($modulePermissions as $perm) {
            DB::table('permissions')->insertOrIgnore([
                'name'       => $perm['name'],
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 2. Transfer old roles to Spatie roles table
        $oldRoles = DB::table('old_roles')->get();
        foreach ($oldRoles as $oldRole) {
            DB::table('roles')->insertOrIgnore([
                'id'         => $oldRole->id,
                'name'       => $oldRole->name,
                'guard_name' => 'web',
                'created_at' => $oldRole->created_at,
                'updated_at' => $oldRole->updated_at,
            ]);
        }

        // 3. Transfer user role assignments to Spatie pivot table (model_has_roles)
        $users = DB::table('users')->whereNotNull('role_id')->get();
        foreach ($users as $user) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id'    => $user->role_id,
                'model_type' => 'App\\Models\\User',
                'model_id'   => $user->id,
            ]);
        }

        // 4. Grant super_admin all permissions
        $superAdminRole = DB::table('roles')->where('name', 'super_admin')->first();
        $allPermissions = DB::table('permissions')->pluck('id');
        if ($superAdminRole) {
            foreach ($allPermissions as $permId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permId,
                    'role_id'       => $superAdminRole->id,
                ]);
            }
        }

        // 5. Grant pimpinan read access to all modules
        $pimpinanRole = DB::table('roles')->where('name', 'pimpinan')->first();
        if ($pimpinanRole) {
            foreach ($allPermissions as $permId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permId,
                    'role_id'       => $pimpinanRole->id,
                ]);
            }
        }

        // 6. Grant SPMI-specific roles access to SPMI module
        $spmiPermId = DB::table('permissions')->where('name', 'access_spmi')->value('id');
        $spmiRoles  = ['auditor', 'auditee', 'staff'];
        foreach ($spmiRoles as $roleName) {
            $role = DB::table('roles')->where('name', $roleName)->first();
            if ($role && $spmiPermId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $spmiPermId,
                    'role_id'       => $role->id,
                ]);
            }
        }

        // 7. Drop old role_id column from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }

    public function down(): void
    {
        // Re-add role_id column
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('id');
        });

        // Restore role assignments from model_has_roles
        $assignments = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->get();
        foreach ($assignments as $assignment) {
            DB::table('users')
                ->where('id', $assignment->model_id)
                ->update(['role_id' => $assignment->role_id]);
        }

        // Clean up Spatie tables
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();
    }
};

