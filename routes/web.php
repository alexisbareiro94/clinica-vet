<?php

use App\Helpers\Helper;
use App\Livewire\Planes;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\GestionUsuarioController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReportsController;
use App\Livewire\Consultas;
use App\Livewire\FormAddDueno;
use App\Livewire\FormAddMascota;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Login;
use App\Livewire\Alertas;
use App\Livewire\Caja;
use App\Livewire\GestionRoles;
use App\Livewire\HistorialCompleto;
use App\Livewire\Inventario;
use App\Livewire\Reportes;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;


Route::get('/registro', [AuthController::class, 'registerForm'])->name('auth.registerform');
Route::post('/registro/save', [AuthController::class, 'register'])->name('auth.register');
Route::post('/iniciar-sesion', [AuthController::class, 'login'])->name('auth.login');
Route::get('/planes', Planes::class)->name('planes');

Route::middleware(Login::class)->group(function () {
    Route::get('/', Home::class)->name('index');
    Route::get('/registrar/dueno', FormAddDueno::class)->name('add.dueno');
    Route::get('/registrar/mascota', FormAddMascota::class)->name('add.mascota');
    Route::get('/consultas', Consultas::class)->name('consultas');
    Route::get('/Gestion/usuario', GestionRoles::class)->name('gestion.roles');
    Route::get('/Inventario', Inventario::class)->name('inventario');
    Route::get('/Historial-completo/{id}/{url?}', HistorialCompleto::class)->name('historial.completo');

    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::post('/inventario/actualizar/{productoId}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::post('/inventario/eliminar', [InventarioController::class, 'destroy'])->name('inventario.destroy');

    Route::post('/registrar/mascota', [MascotaController::class, 'crearMascota'])->name('mascota.crear');
    Route::post('/editar/mascota', [MascotaController::class, 'editSave'])->name('mascota.editsave');
    Route::get('/crear-caja/{consultaId}', [CajaController::class, 'store'])->name('caja.store');
    Route::get('/caja', Caja::class)->name('caja');
    Route::get('/reportes', Reportes::class)->name('reportes');
    Route::post('/actualizar-user', [GestionUsuarioController::class, 'update'])->name('user.update');
    Route::get('/reporte-pdf/ventas', [ReportsController::class, 'exportarPdf'])->name('reporte.pdf');
    Route::post('/reporte-pdf/entradas', [ReportsController::class, 'reporteEntradas'])->name('reporte.entradas');

    Route::get('/alertas', Alertas::class)->name('alertas');
});


Route::get('/test-redis', function () {
    Cache::store('redis')->put('saludo', 'hola redis', 60);
    return Cache::store('redis')->get('saludo');
});


Route::get('borrar-session/{session}', function ($session) {
    Session::forget($session);
    return back();
});

Route::get('ver-sessiones/{session}', function ($session) {
    dd(session($session));
});

Route::get('ver-sessiones/all', function () {
    dd(session()->all());
});

Route::get('ver-caja', function () {
    $caja = session('caja', []);

    foreach ($caja as $item) {
        dd(Helper::caja($item['ownerId'], $item['consultaId']));
    }
});

Route::get('recordatorio', fn() => view('recordatorio-consulta'));