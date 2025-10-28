<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    CategoriaController,
    DetalleFacturaController,
    DetalleOrdenController,
    FacturaController,
    MenuController,
    AreaMesaController,
    MesaController,
    OrdenController,
    UserController,
    DashboardController
};

Route::get('/', function () {
    if (Auth::check()) {
        // Si ya inició sesión, redirigir al dashboard
        return redirect()->route('dashboard');
    }
    // Si no, mostrar login
    return view('auth.login');
})->name('login')->middleware('guest');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->middleware('guest');

// Dashboard — todos los roles pueden acceder
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ORDENES
Route::middleware(['auth', 'rol:mesero,cocinero'])->group(function () {
    // Crear orden (solo mesero)
    Route::get('/ordenes/create', [OrdenController::class, 'create'])->name('ordenes.create');
    Route::post('/ordenes', [OrdenController::class, 'store'])->name('ordenes.store');
    Route::get('/ordenes/{orden}/edit', [OrdenController::class, 'edit'])->name('ordenes.edit');
    Route::put('/ordenes/{orden}', [OrdenController::class, 'update'])->name('ordenes.update');
    Route::get('/ordenes/{orden}', [OrdenController::class, 'show'])->name('ordenes.show');
    Route::put('/ordenes/{id}/estado', [OrdenController::class, 'actualizarEstado'])->name('ordenes.actualizarEstado');
    Route::patch('ordenes/{id}/finalizar', [OrdenController::class, 'finalizar'])->name('ordenes.finalizar');
    Route::patch('ordenes/{id}/cancelar', [OrdenController::class, 'cancelar'])->name('ordenes.cancelar');
    Route::post('/ordenes/{orden}/agregar-ajax', [OrdenController::class, 'agregarAjax'])->name('ordenes.agregarAjax');
    Route::post('/ordenes/{orden}/eliminar-ajax', [OrdenController::class, 'eliminarPlatilloAjax']);

    // Crear orden desde sala/mesa
    Route::get('/ordenes/salas', [OrdenController::class, 'seleccionarSala'])->name('ordenes.salas');
    Route::get('/ordenes/salas/{sala}', [OrdenController::class, 'seleccionarMesa'])->name('ordenes.mesas');
    Route::get('/ordenes/crear/{sala}/{mesa}', [OrdenController::class, 'crearDesdeSeleccion'])->name('ordenes.crear');
});

// Index de órdenes — accesible por admin, mesero, cajero, cocinero
Route::middleware(['auth', 'rol:administrador,mesero,cajero,cocinero'])->get('/ordenes', [OrdenController::class, 'index'])->name('ordenes.index');

// Show de orden — solo mesero y cocinero
Route::middleware(['auth', 'rol:mesero,cocinero'])->get('/ordenes/{orden}', [OrdenController::class, 'show'])->name('ordenes.show');

// MESAS
Route::middleware(['auth', 'rol:administrador,mesero'])->group(function () {
    Route::resource('mesas', MesaController::class);
    Route::resource('area_mesas', AreaMesaController::class);
    Route::get('/areas/{area}/mesas-disponibles', [MesaController::class, 'mesasDisponibles']);
});

// MENÚS
Route::middleware(['auth', 'rol:administrador,mesero,cocinero'])->group(function () {
    Route::get('/menus/buscar', [MenuController::class, 'buscar'])->name('menus.buscar');
    Route::resource('menus', MenuController::class);
    Route::post('/menus/toggle-disponibilidad', [MenuController::class, 'toggleDisponibilidad'])->name('menus.toggleDisponibilidad');
});

// FACTURAS
Route::middleware(['auth', 'rol:administrador,cajero'])->group(function () {
    Route::get('/facturas/crear/{idOrden}', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::resource('facturas', FacturaController::class)->only(['show', 'edit', 'update', 'destroy']);
    Route::get('/facturas/{factura}/pdf', [FacturaController::class, 'exportPDF'])->name('facturas.exportPDF');
    Route::get('/facturas/{factura}/xml', [FacturaController::class, 'exportXML'])->name('facturas.exportXML');
    Route::get('/facturas/pdf/resumen', [FacturaController::class, 'exportPDFResumen'])->name('facturas.pdf.resumen');
});

// USUARIOS
Route::middleware(['auth', 'rol:administrador'])->resource('users', UserController::class);
Route::post('/users/verificar-unico', [UserController::class, 'verificarUnico'])->name('users.verificarUnico');

// CATEGORÍAS Y DETALLES (todos los logueados)
Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoriaController::class);
    Route::resource('detalles_facturas', DetalleFacturaController::class);
    Route::resource('detalles_ordenes', DetalleOrdenController::class);
});

require __DIR__.'/auth.php';
