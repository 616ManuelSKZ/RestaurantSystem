<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\AreaMesa;
use App\Models\Mesa;
use App\Models\User;
use App\Models\Orden;
use App\Models\Factura;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Contadores simples
        $totalPlatos   = Menu::where('disponible', 1)->count();
        $totalSalas    = AreaMesa::count();
        $totalUsuarios = User::count();
        $totalPedidos  = Orden::count();

        // Total ventas usando la tabla facturas
        $totalVentas   = Factura::sum('total');

        // Si quieres también las ventas por mes para la gráfica
        $ventasPorMes = Factura::select(
                DB::raw('MONTH(fecha_emision) as mes'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $ordenes = Orden::with(['mesa', 'user'])->latest()->take(5)->get();
        $mesas = Mesa::all();

        return view('dashboard', compact(
            'totalPlatos',
            'totalSalas',
            'totalUsuarios',
            'totalPedidos',
            'totalVentas',
            'ventasPorMes',
            'ordenes',
            'mesas'
        ));
    }
}
