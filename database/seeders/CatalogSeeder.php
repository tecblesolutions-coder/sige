<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\State;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'México' => [
                'code' => 'MX',
                'states' => [
                    'Ciudad de México' => ['Benito Juárez', 'Miguel Hidalgo', 'Coyoacán'],
                    'Estado de México' => ['Naucalpan', 'Tlalnepantla', 'Toluca'],
                    'Jalisco' => ['Guadalajara', 'Zapopan', 'Tlaquepaque'],
                    'Nuevo León' => ['Monterrey', 'San Pedro Garza García', 'San Nicolás'],
                    'Puebla' => ['Puebla', 'San Andrés Cholula', 'Atlixco'],
                ],
            ],
            'Colombia' => [
                'code' => 'CO',
                'states' => [
                    'Cundinamarca' => ['Bogotá', 'Soacha', 'Chía'],
                    'Antioquia' => ['Medellín', 'Envigado', 'Bello'],
                    'Valle del Cauca' => ['Cali', 'Palmira', 'Buenaventura'],
                    'Atlántico' => ['Barranquilla', 'Soledad', 'Malambo'],
                    'Bolívar' => ['Cartagena', 'Turbaco', 'Arjona'],
                    'Santander' => ['Bucaramanga', 'Floridablanca', 'Girón'],
                ],
            ],
            'Argentina' => [
                'code' => 'AR',
                'states' => [
                    'Buenos Aires' => ['Buenos Aires', 'La Plata', 'Mar del Plata'],
                    'Córdoba' => ['Córdoba', 'Villa Carlos Paz', 'Río Cuarto'],
                    'Santa Fe' => ['Rosario', 'Santa Fe', 'Rafaela'],
                ],
            ],
            'Chile' => [
                'code' => 'CL',
                'states' => [
                    'Región Metropolitana' => ['Santiago', 'Las Condes', 'Providencia'],
                    'Valparaíso' => ['Valparaíso', 'Viña del Mar', 'Quilpué'],
                    'Biobío' => ['Concepción', 'Talcahuano', 'Los Ángeles'],
                ],
            ],
            'Perú' => [
                'code' => 'PE',
                'states' => [
                    'Lima' => ['Lima', 'Miraflores', 'San Isidro'],
                    'Arequipa' => ['Arequipa', 'Cayma', 'Yanahuara'],
                    'Cusco' => ['Cusco', 'Wanchaq', 'San Sebastián'],
                ],
            ],
            'España' => [
                'code' => 'ES',
                'states' => [
                    'Madrid' => ['Madrid', 'Alcobendas', 'Fuenlabrada'],
                    'Cataluña' => ['Barcelona', 'Hospitalet', 'Terrassa'],
                    'Andalucía' => ['Sevilla', 'Málaga', 'Granada'],
                ],
            ],
        ];

        foreach ($catalog as $countryName => $data) {
            $country = Country::firstOrCreate(
                ['name' => $countryName],
                ['country_code' => $data['code']]
            );

            foreach ($data['states'] as $stateName => $cities) {
                $state = State::firstOrCreate([
                    'name' => $stateName,
                    'country_id' => $country->id,
                ]);

                foreach ($cities as $cityName) {
                    City::firstOrCreate([
                        'name' => $cityName,
                        'state_id' => $state->id,
                    ]);
                }
            }
        }

        $departments = ['Recursos Humanos', 'Finanzas', 'Tecnología', 'Ventas', 'Operaciones'];
        foreach ($departments as $departmentName) {
            Department::firstOrCreate(['name' => $departmentName]);
        }
    }
}
