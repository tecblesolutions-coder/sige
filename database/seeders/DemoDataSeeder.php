<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 users with a corresponding employee profile
        User::factory(20)->create()->each(function ($user) {
            $user->assignRole('Empleado');

            // Create an employee profile for the user, ensuring key details are consistent
            Employee::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);
        });
    }
}
