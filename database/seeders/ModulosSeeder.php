<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //los módulos que se agregan son los que se van a bloquear
        DB::table('modulos')->insert([ //1
            'nombre' => 'consulta: agregar insumos',
            'descripcion' => 'no permite agregar insumos a la consulta',
            'clave' => 'agregar_insumos',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('modulos')->insert([ //2
            'nombre' => 'consulta y tarjeta: recordatorios',
            'descripcion' => 'no permite enviar recordatorios de coonsulta y vacunacion',
            'clave' => 'recordatorio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('modulos')->insert([ //3
            'nombre' => 'inventario: opciones de uso interno',
            'descripcion' => 'no aparece la opción de uso interno en el inventario cuando se agrega un producto',
            'clave' => 'uso_interno',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //tambien se usa en el plan basico
        DB::table('modulos')->insert([ //4
            'nombre' => 'todo el sistema: limite de 50 consultas',
            'descripcion' => 'no permite agregar más de 50 consultas',
            'clave' => 'limite_50_consultas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

          DB::table('modulos')->insert([ //5
            'nombre' => 'todo el sistema: limite de 150 consultas',
            'descripcion' => 'no permite agregar más de 150 consultas',
            'clave' => 'limite_150_consultas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

          DB::table('modulos')->insert([ //6
            'nombre' => 'todo el sistema: limite de 250 consultas',
            'descripcion' => 'no permite agregar más de 250 consultas',
            'clave' => 'limite_250_consultas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

          DB::table('modulos')->insert([ //7
            'nombre' => 'todo el sistema: limite de 500 consultas',
            'descripcion' => 'no permite agregar más de 500 consultas',
            'clave' => 'limite_500_consultas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('modulos')->insert([ //8
            'nombre' => 'reportes: no permite exportar reportes',
            'descripcion' => 'no permite exportar reportes',
            'clave' => 'exportar_reportes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('modulos')->insert([ //9
            'nombre' => 'reportes: solo entradas',
            'descripcion' => 'solo permite ver reportes de entradas',
            'clave' => 'solo_entradas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);       
    }
}
