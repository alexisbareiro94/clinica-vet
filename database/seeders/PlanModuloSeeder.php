<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //se guardan solo las restricciones de los modulos
        //osea que el plan 1 va tener restricciones de los modulos 1, 2 y asi...
        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 1,            
        ]);

        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 2,            
        ]);

        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 3,            
        ]);

        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 4,            
        ]);

        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 7,            
        ]);

        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 8,            
        ]);

        DB::table('plan_modulos')->insert([
            'plan_id' => 1,
            'modulo_id' => 9,            
        ]);

        //plan 2
        DB::table('plan_modulos')->insert([
            'plan_id' => 2,
            'modulo_id' => 4,            
        ]);

          DB::table('plan_modulos')->insert([
            'plan_id' => 2,
            'modulo_id' => 8,            
        ]);

          DB::table('plan_modulos')->insert([
            'plan_id' => 2,
            'modulo_id' => 9,            
        ]);

        //plan 3
          DB::table('plan_modulos')->insert([
            'plan_id' => 3,
            'modulo_id' => 5,            
        ]);

        //plan 4
          DB::table('plan_modulos')->insert([
            'plan_id' => 4,
            'modulo_id' => 6,            
        ]);

        //plan 5
        DB::table('plan_modulos')->insert([
            'plan_id' => 5,
            'modulo_id' => 7,            
        ]);
    }
}
