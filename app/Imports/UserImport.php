<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip empty rows
            if (!isset($row['email'])) continue;

            $roleName = str_replace(' ', '_', strtolower($row['role'] ?? 'auditee'));
            $role = Role::where('name', $roleName)
                        ->orWhere('name', 'like', '%' . $row['role'] . '%')
                        ->first();

            $user = clone User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name'       => $row['nama'],
                    'nip'        => $row['nip'] ?? null,
                    'unit_kerja' => $row['unit_kerja'] ?? '-',
                    'jabatan'    => $row['jabatan'] ?? '-',
                    'password'   => Hash::make($row['password'] ?? 'password123'),
                    'is_active'  => true,
                ]
            );

            // Assign spatie role
            if ($role) {
                $user->syncRoles([$role->name]);
            } else {
                $user->syncRoles(['auditee']);
            }
        }
    }
}
