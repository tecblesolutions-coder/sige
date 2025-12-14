<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'SIGE',
                'email' => 'admin@sige.test',
                'password' => 'Admin123*',
                'role' => 'Admin',
            ],
            [
                'username' => 'analista',
                'first_name' => 'Analista',
                'last_name' => 'SIGE',
                'email' => 'analista@sige.test',
                'password' => '123456789',
                'role' => 'Analista',
            ],
            [
                'username' => 'auditor',
                'first_name' => 'Auditor',
                'last_name' => 'SIGE',
                'email' => 'auditor@sige.test',
                'password' => '123456789',
                'role' => 'Auditor',
            ],
            [
                'username' => 'empleado',
                'first_name' => 'Empleado',
                'last_name' => 'SIGE',
                'email' => 'empleado@sige.test',
                'password' => '123456789',
                'role' => 'Empleado',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );

            if ($role = Role::where('name', $data['role'])->first()) {
                $user->syncRoles([$role->name]);
            }

            // Ensure every default user also has an employee profile
            if (! $user->employee) {
                \App\Models\Employee::factory()->create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                ]);
            }
        }
    }
}
