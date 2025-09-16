<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DetallesFacturaController;
use App\Http\Controllers\DetallesOrdenController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('categorias', CategoriaController::class)->middleware('auth');
Route::resource('detalles_facturas', DetallesFacturaController::class)->middleware('auth');
Route::resource('detalles_ordenes', DetallesOrdenController::class)->middleware('auth');
Route::resource('facturas', FacturaController::class)->middleware('auth');
Route::resource('menus', MenuController::class)->middleware('auth');
Route::resource('mesas', MesaController::class)->middleware('auth');
Route::resource('ordenes', OrdenController::class)->middleware('auth');
Route::resource('users', UserController::class)->middleware('auth');

Route::post('/menus/toggle-disponibilidad', [MenuController::class, 'toggleDisponibilidad'])->name('menus.toggleDisponibilidad');

require __DIR__.'/auth.php';
