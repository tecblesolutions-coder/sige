<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Gerente',
            'Director',
            'Jefe de área',
            'Coordinador',
            'Analista',
            'Especialista',
            'Asistente',
            'Auxiliar',
            'Desarrollador',
            'Diseñador',
            'Soporte',
            'Ventas',
            'Recursos Humanos',
            'Contador',
            'Finanzas',
            'Operario',
        ];

        foreach ($positions as $name) {
            Position::firstOrCreate(['name' => $name]);
        }
    }
}
