<?php

namespace Tests\Feature;

use App\Http\Livewire\Reports\ReportsIndex;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $this->seed(DatabaseSeeder::class);
        $admin = User::factory()->create(['username' => 'admin_reports']);
        $admin->assignRole('Admin');

        return $admin;
    }

    /** @test */
    public function reports_page_loads_for_admin()
    {
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin)->get('/reportes')->assertStatus(200);
    }

    /** @test */
    public function can_export_employees_csv()
    {
        $admin = $this->actingAsAdmin();
        Livewire::actingAs($admin)
            ->test(ReportsIndex::class)
            ->call('exportEmployees')
            ->assertFileDownloaded('reporte-empleados.csv');
    }
}
