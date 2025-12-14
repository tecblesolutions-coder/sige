<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PositionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_dashboard()
    {
        $this->seed([RolesAndPermissionsSeeder::class, PositionsSeeder::class]);
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200)->assertSee('Panel general');
    }
}
