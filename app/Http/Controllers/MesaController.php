<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\AreaMesa;

class MesaController extends Controller
{
    public function index()
    {
        $areas = AreaMesa::with('mesas')->get();
        return view('mesas.index', compact('areas'));
    }

    public function create(Request $request)
    {
        $areaId = $request->get('area');
        $areas = AreaMesa::all();
        return view('mesas.create', compact('areas', 'areaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_area_mesas' => 'required|exists:area_mesas,id',
            'numero' => 'required|integer',
            'capacidad' => 'required|integer|min:1',
        ]);

        Mesa::create($request->all());

        return redirect()->route('mesas.index')->with('success', 'Mesa creada correctamente.');
    }

    public function edit(Mesa $mesa)
    {
        $areas = AreaMesa::all();
        return view('mesas.edit', compact('mesa', 'areas'));
    }

    public function update(Request $request, Mesa $mesa)
    {
        $request->validate([
            'id_area_mesas' => 'required|exists:area_mesas,id',
            'numero' => 'required|integer',
            'capacidad' => 'required|integer|min:1',
        ]);

        $mesa->update($request->all());

        return redirect()->route('mesas.index')->with('success', 'Mesa actualizada correctamente.');
    }

    public function destroy(Mesa $mesa)
    {
        $mesa->delete();
        return redirect()->route('mesas.index')->with('success', 'Mesa eliminada correctamente.');
    }

    // 🔹 Método para obtener mesas disponibles en un área específica
    public function mesasDisponibles($areaId)
    {
        $mesas = \App\Models\Mesa::where('id_area_mesas', $areaId)
                    ->where('estado', 'disponible')
                    ->get();

        return response()->json($mesas);
    }

}
