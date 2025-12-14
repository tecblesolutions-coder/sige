<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\State;
use Database\Seeders\PositionsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeePositionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_employee_with_position_and_location()
    {
        $this->seed([RolesAndPermissionsSeeder::class, PositionsSeeder::class]);

        $country = Country::create(['country_code' => 'CO', 'name' => 'Colombia']);
        $state = State::create(['country_id' => $country->id, 'name' => 'Antioquia']);
        $city = City::create(['state_id' => $state->id, 'name' => 'Medellín']);
        $department = Department::create(['name' => 'Tecnología']);
        $position = Position::first();

        $employee = Employee::create([
            'last_name' => 'Pérez',
            'first_name' => 'Juan',
            'address' => 'Calle 123',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'job_title' => null,
            'status' => 'activo',
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'zip_code' => '050001',
        ]);

        $this->assertEquals($position->id, $employee->position_id);
        $this->assertEquals('Medellín', $employee->city->name);
    }
}
