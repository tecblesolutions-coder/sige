<?php

namespace Tests\Feature;

use App\Http\Livewire\Attendance\AttendanceIndex;
use App\Models\Attendance;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AttendanceMarkingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function empleado_puede_marcar_entrada_y_salida_sin_duplicar()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $empleado = User::factory()->create(['username' => 'empleado1']);
        $empleado->assignRole('Empleado');

        $this->actingAs($empleado)->get('/asistencia')->assertStatus(200);

        Livewire::actingAs($empleado)
            ->test(AttendanceIndex::class)
            ->call('markIn')
            ->call('markIn')
            ->call('markOut')
            ->call('markOut');

        $attendance = Attendance::where('user_id', $empleado->id)->whereDate('date', now()->toDateString())->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->time_in);
        $this->assertNotNull($attendance->time_out);
        $this->assertEquals('cerrada', $attendance->status);
    }
}
