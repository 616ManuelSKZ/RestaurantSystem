<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AreaMesa;

class AreaMesaController extends Controller
{
    public function index()
    {
        // Traemos las áreas con sus mesas
        $areas = AreaMesa::with('mesas')->get();
        return view('mesas.index', compact('areas'));
    }

    public function create()
    {
        return view('area_mesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:area_mesas,nombre',
        ]);

        AreaMesa::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('mesas.index')->with('success', 'Área creada correctamente.');
    }

    public function edit(AreaMesa $areaMesa)
    {
        return view('area_mesas.edit', compact('areaMesa'));
    }

    public function update(Request $request, AreaMesa $areaMesa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:area_mesas,nombre,' . $areaMesa->id,
        ]);

        $areaMesa->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('mesas.index')->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(AreaMesa $areaMesa)
    {
        $areaMesa->delete();
        return redirect()->route('mesas.index')->with('success', 'Área eliminada correctamente.');
    }
}
