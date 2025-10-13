<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\DetalleOrdenController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AreaMesaController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoriaController::class)->middleware('auth');
    Route::resource('detalles_facturas', DetalleFacturaController::class)->middleware('auth');
    Route::resource('detalles_ordenes', DetalleOrdenController::class)->middleware('auth');
    Route::resource('facturas', FacturaController::class)->middleware('auth');
    Route::resource('menus', MenuController::class)->middleware('auth');
    Route::resource('area_mesas', AreaMesaController::class)->middleware('auth');
    Route::get('/areas/{area}/mesas-disponibles', [App\Http\Controllers\MesaController::class, 'mesasDisponibles']);
    Route::resource('mesas', MesaController::class)->middleware('auth');
    Route::resource('ordenes', OrdenController::class)->middleware('auth');
    // Ruta para agregar más platillos
    Route::post('ordenes/{id}/agregar-platillos', [OrdenController::class, 'agregarPlatillos'])->name('ordenes.agregarPlatillos');

    // Ruta para finalizar orden
    Route::put('/ordenes/{id}/estado', [OrdenController::class, 'actualizarEstado'])->name('ordenes.actualizarEstado');
    Route::patch('ordenes/{id}/finalizar', [OrdenController::class, 'finalizar'])->name('ordenes.finalizar');
    Route::patch('ordenes/{id}/cancelar', [OrdenController::class, 'cancelar'])->name('ordenes.cancelar');
    Route::post('/ordenes/{orden}/agregar-ajax', [OrdenController::class, 'agregarAjax'])->name('ordenes.agregarAjax');
    Route::post('/ordenes/{orden}/eliminar-ajax', [OrdenController::class, 'eliminarPlatilloAjax']);

    Route::get('/facturas/crear/{idOrden}', [FacturaController::class, 'create'])->name('facturas.create');
    Route::get('/facturas/{factura}/pdf', [FacturaController::class, 'exportPDF'])->name('facturas.exportPDF');
    Route::get('/facturas/{factura}/xml', [FacturaController::class, 'exportXML'])->name('facturas.exportXML');
    Route::get('/facturas/crear/{idOrden}', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');


    Route::resource('users', UserController::class)->middleware('auth');

    Route::post('/menus/toggle-disponibilidad', [MenuController::class, 'toggleDisponibilidad'])->name('menus.toggleDisponibilidad');

    Route::middleware(['auth'])->group(function() {
        Route::get('/ordenes/salas', [OrdenController::class, 'seleccionarSala'])->name('ordenes.salas');
        Route::get('/ordenes/salas/{sala}', [OrdenController::class, 'seleccionarMesa'])->name('ordenes.mesas');
        Route::get('/ordenes/crear/{sala}/{mesa}', [OrdenController::class, 'crearDesdeSeleccion'])->name('ordenes.crear');});
});


require __DIR__.'/auth.php';
