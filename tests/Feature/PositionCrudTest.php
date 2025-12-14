<?php

namespace Tests\Feature;

use App\Http\Livewire\Position\PositionIndex;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\State;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PositionCrudTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Admin');

        return $user;
    }

    /** @test */
    public function admin_can_create_a_position(): void
    {
        $user = $this->actingAsAdmin();

        Livewire::actingAs($user)
            ->test(PositionIndex::class)
            ->set('name', 'Supervisor')
            ->call('storePosition')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('positions', ['name' => 'Supervisor']);
    }

    /** @test */
    public function it_validates_unique_name_on_update(): void
    {
        $user = $this->actingAsAdmin();
        $existing = Position::create(['name' => 'Supervisor']);
        $toUpdate = Position::create(['name' => 'Coordinador']);

        Livewire::actingAs($user)
            ->test(PositionIndex::class)
            ->set('positionId', $toUpdate->id)
            ->set('editMode', true)
            ->set('name', $existing->name)
            ->call('updatePosition')
            ->assertHasErrors(['name' => 'unique']);
    }

    /** @test */
    public function it_prevents_deleting_position_with_employees(): void
    {
        $user = $this->actingAsAdmin();
        $country = Country::create(['country_code' => 'CO', 'name' => 'Colombia']);
        $state = State::create(['country_id' => $country->id, 'name' => 'Antioquia']);
        $city = City::create(['state_id' => $state->id, 'name' => 'Medellin']);
        $department = Department::create(['name' => 'Tecnologia']);
        $position = Position::create(['name' => 'Analista']);

        Employee::create([
            'last_name' => 'Perez',
            'first_name' => 'Ana',
            'address' => 'Calle 123',
            'department_id' => $department->id,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'zip_code' => '050001',
            'status' => 'activo',
            'position_id' => $position->id,
        ]);

        Livewire::actingAs($user)
            ->test(PositionIndex::class)
            ->call('deletePosition', $position->id);

        $this->assertDatabaseHas('positions', ['id' => $position->id]);
        $this->assertSame(1, Position::count());
    }

    /** @test */
    public function it_filters_positions_by_search(): void
    {
        $user = $this->actingAsAdmin();
        Position::create(['name' => 'Desarrollador']);
        Position::create(['name' => 'QA Engineer']);

        Livewire::actingAs($user)
            ->test(PositionIndex::class)
            ->set('search', 'QA Engineer')
            ->call('render')
            ->assertSee('QA Engineer')
            ->assertDontSee('Desarrollador');
    }

    /** @test */
    public function pagination_limits_to_ten_per_page(): void
    {
        $user = $this->actingAsAdmin();
        Position::factory()->count(12)->create();

        Livewire::actingAs($user)
            ->test(PositionIndex::class)
            ->call('render')
            ->assertSee(Position::orderBy('name')->first()->name)
            ->assertDontSee(Position::orderBy('name')->skip(10)->first()->name);
    }
}
