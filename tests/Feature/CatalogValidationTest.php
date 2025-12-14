<?php

namespace Tests\Feature;

use App\Http\Livewire\City\CityIndex;
use App\Http\Livewire\Country\CountryIndex;
use App\Http\Livewire\State\StateIndex;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogValidationTest extends TestCase
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
    public function country_requires_unique_code_and_name(): void
    {
        $user = $this->actingAsAdmin();
        Country::create(['country_code' => 'CO', 'name' => 'Colombia']);

        Livewire::actingAs($user)
            ->test(CountryIndex::class)
            ->set('countryCode', 'CO')
            ->set('name', 'Colombia Nueva')
            ->call('storeCountry')
            ->assertHasErrors(['countryCode' => 'unique']);

        Livewire::actingAs($user)
            ->test(CountryIndex::class)
            ->set('countryCode', 'MX')
            ->set('name', 'Colombia')
            ->call('storeCountry')
            ->assertHasErrors(['name' => 'unique']);
    }

    /** @test */
    public function state_requires_existing_country(): void
    {
        $user = $this->actingAsAdmin();

        Livewire::actingAs($user)
            ->test(StateIndex::class)
            ->set('countryId', 999)
            ->set('name', 'Nuevo Estado')
            ->call('storeState')
            ->assertHasErrors(['countryId' => 'exists']);
    }

    /** @test */
    public function city_requires_existing_state(): void
    {
        $user = $this->actingAsAdmin();
        $country = Country::create(['country_code' => 'CO', 'name' => 'Colombia']);
        $state = State::create(['country_id' => $country->id, 'name' => 'Antioquia']);

        Livewire::actingAs($user)
            ->test(CityIndex::class)
            ->set('stateId', $state->id + 100)
            ->set('name', 'Medellin')
            ->call('storeCity')
            ->assertHasErrors(['stateId' => 'exists']);
    }
}
