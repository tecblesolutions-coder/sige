<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutePermissionTest extends TestCase
{
    use RefreshDatabase;

    private array $protectedRoutes = [
        '/employees',
        '/users',
        '/roles',
        '/asistencia',
        '/reportes',
    ];

    /** @test */
    public function user_without_permissions_gets_403()
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::factory()->create(['username' => 'user_sin_permiso']);

        foreach ($this->protectedRoutes as $route) {
            $this->actingAs($user)->get($route)->assertStatus(403);
        }
    }

    /** @test */
    public function admin_can_access_all_protected_routes()
    {
        $this->seed(DatabaseSeeder::class);
        $admin = User::factory()->create(['username' => 'admin_test']);
        $admin->assignRole('Admin');

        foreach ($this->protectedRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $this->assertTrue($response->isOk(), "Route {$route} returned status {$response->status()}");
        }
    }
}
