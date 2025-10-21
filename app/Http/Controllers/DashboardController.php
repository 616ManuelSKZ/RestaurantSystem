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

        $totalVentas   = Factura::sum('total');

        $ventasPorDia = Factura::whereDate('fecha_emision', now())->sum('total');
        $ventasPorSemana = Factura::whereBetween('fecha_emision', [now()->startOfWeek(), now()->endOfWeek()])->sum('total');
        $ventasPorMes = Factura::whereMonth('fecha_emision', now()->month)->sum('total');
        $ventasPorAnio = Factura::whereYear('fecha_emision', now()->year)->sum('total');

        $ventasPorMesChart = Factura::select(
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
            'ventasPorDia', 
            'ventasPorSemana', 
            'ventasPorMes', 
            'ventasPorAnio', 
            'ventasPorMesChart',
            'ordenes',
            'mesas'
        ));
    }
    

}
