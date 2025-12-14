<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'ver panel',
            'ver empleados',
            'crear empleados',
            'editar empleados',
            'eliminar empleados',
            'ver catalogos',
            'gestionar catalogos',
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'gestionar roles',
            'ver asistencia',
            'ver mi asistencia',
            'registrar asistencia',
            'gestionar asistencia',
            'ver reportes',
            'exportar reportes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        $empleado = Role::firstOrCreate(['name' => 'Empleado', 'guard_name' => 'web']);
        $empleado->syncPermissions([
            'ver panel',
            'ver mi asistencia',
            'registrar asistencia',
            'ver asistencia',
        ]);

        $analista = Role::firstOrCreate(['name' => 'Analista', 'guard_name' => 'web']);
        $analista->syncPermissions([
            'ver panel',
            'ver empleados',
            'ver catalogos',
            'ver asistencia',
            'ver mi asistencia',
            'ver reportes',
            'exportar reportes',
        ]);

        $auditor = Role::firstOrCreate(['name' => 'Auditor', 'guard_name' => 'web']);
        $auditor->syncPermissions([
            'ver panel',
            'ver mi asistencia',
            'ver reportes',
            'exportar reportes',
        ]);

        $firstUser = \App\Models\User::first();
        if ($firstUser && ! $firstUser->hasRole('Admin')) {
            $firstUser->assignRole($admin);
        }
    }
}
