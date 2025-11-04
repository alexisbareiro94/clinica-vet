<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use App\Models\Categoria;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run() :void {
        //User::factory(1)->create();

        $this->call([            
            RolSeeder::class,
            UserSeeder::class,
            PermisoSeeder::class,
            ModulosSeeder::class,
            PlaneSeeder::class,
            PlanModuloSeeder::class,
        ]);
    }
}
