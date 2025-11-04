<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('planes')->insert([
            'nombre' => 'Gratis',
            'descripcion' => '',            
            'precio' => 0,
            'duracion' => 0, // Duración en meses
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('planes')->insert([
            'nombre' => 'Básico',
            'descripcion' => '',            
            'precio' => 75000,
            'duracion' => 1, // Duración en meses
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('planes')->insert([
            'nombre' => 'Estándar',
            'descripcion' => '',            
            'precio' => 125000,
            'duracion' => 1, // Duración en meses
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('planes')->insert([
            'nombre' => 'Profesional',
            'descripcion' => '',            
            'precio' => 190000,
            'duracion' => 1, // Duración en meses
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('planes')->insert([
            'nombre' => 'Avanzado',
            'descripcion' => '',            
            'precio' => 210000,
            'duracion' => 1, // Duración en meses
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('planes')->insert([
            'nombre' => 'Premium',
            'descripcion' => '',            
            'precio' => 250000,
            'duracion' => 1, // Duración en meses
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
