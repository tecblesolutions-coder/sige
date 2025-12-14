<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Position;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // This factory assumes that the CatalogSeeder and PositionsSeeder have been run.
        $country = Country::inRandomOrder()->first();
        $state = $country ? State::where('country_id', $country->id)->inRandomOrder()->first() : null;
        $city = $state ? City::where('state_id', $state->id)->inRandomOrder()->first() : null;

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'zip_code' => fake()->postcode(),
            'birthdate' => fake()->dateTimeBetween('-50 years', '-20 years'),
            'date_hired' => fake()->dateTimeBetween('-10 years', 'now'),
            'status' => 'activo',
            'country_id' => $country->id,
            'state_id' => $state ? $state->id : null,
            'city_id' => $city ? $city->id : null,
            'department_id' => Department::inRandomOrder()->first()->id,
            'position_id' => Position::inRandomOrder()->first()->id,
            // 'user_id' will be set by the seeder that calls this factory.
        ];
    }
}
