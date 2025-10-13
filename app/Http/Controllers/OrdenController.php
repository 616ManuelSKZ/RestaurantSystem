<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orden;
use App\Models\AreaMesa;
use App\Models\Mesa;
use App\Models\Menu;
use App\Models\Categoria;
use App\Models\DetalleOrden;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrdenController extends Controller
{
    public function index()
    {
        $ordenes = Orden::with('mesa.area', 'user', 'detalles_orden.menu')->get();
        return view('ordenes.index', compact('ordenes'));
    }

    public function show($id)
    {
        $orden = Orden::with(['mesa', 'user', 'detalles_orden.menu'])->findOrFail($id);
        return view('ordenes.show', compact('orden'));
    }

    public function actualizarEstado(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);
        $orden->estado = $request->estado;
        $orden->save();

        return redirect()->back()->with('success', 'Estado de la orden actualizado correctamente.');
    }

    public function create()
    {
        $areas = AreaMesa::all();
        $menus = Menu::all();
        $categorias = Categoria::with(['menus' => function($query) {
            $query->where('disponible', 1); // Solo menús disponibles en las categorías
        }])->get();
        return view('ordenes.create', compact('areas', 'menus', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mesas' => 'required|exists:mesas,id',
            'platillos' => 'required|array|min:1',
            'platillos.*.id_menu' => 'required|exists:menus,id',
            'platillos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 1. Crear la orden
        $orden = Orden::create([
            'id_mesas' => $request->id_mesas,
            'id_users' => Auth::id(),
            'estado' => 'En Preparación',
            'fecha_orden' => now(),
        ]);

        // 2. Insertar los platillos en detalles_orden
        foreach ($request->platillos as $platillo) {
            $menu = Menu::find($platillo['id_menu']);

            DetalleOrden::create([
                'id_orden' => $orden->id,
                'id_menu' => $menu->id,
                'cantidad' => $platillo['cantidad'],
                'precio_unitario' => $menu->precio,
                'subtotal' => $menu->precio * $platillo['cantidad'],
                'nombre_menu' => $menu->nombre,
                'precio_menu' => $menu->precio,
            ]);
        }

        // 3. Cambiar mesa a ocupada
        $mesa = Mesa::find($request->id_mesas);
        $mesa->estado = 'ocupada';
        $mesa->save();

        return redirect()->route('ordenes.index', $orden->id)
                         ->with('success', 'Orden creada con éxito.');
    }

    public function edit($id)
    {
        $orden = Orden::with(['mesa.area', 'detalles_orden.menu'])->findOrFail($id);
        $areas = AreaMesa::all();
        $categorias = Categoria::with(['menus' => function($query) {
            $query->where('disponible', 1); // Solo menús disponibles
        }])->get();
        return view('ordenes.edit', compact('orden', 'areas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);
        $orden->id_mesas = $request->id_mesas;
        $orden->save();

        // Actualizar los detalles
        $orden->detalles_orden()->delete();
        foreach ($request->platillos as $platillo) {
            $orden->detalles_orden()->create([
                'id_menu' => $platillo['id_menu'],
                'cantidad' => $platillo['cantidad'],
                'precio_unitario' => Menu::find($platillo['id_menu'])->precio,
                'subtotal' => Menu::find($platillo['id_menu'])->precio * $platillo['cantidad'],
                'nombre_menu' => Menu::find($platillo['id_menu'])->nombre,
                'precio_menu' => Menu::find($platillo['id_menu'])->precio,
            ]);
        }

        return redirect()->route('ordenes.index')->with('success', 'Orden actualizada correctamente.');
    }

    public function agregarPlatillos(Request $request, $idOrden)
    {
        $request->validate([
            'platillos' => 'required|array|min:1',
            'platillos.*.id_menu' => 'required|exists:menus,id',
            'platillos.*.cantidad' => 'required|integer|min:1',
        ]);

        $orden = Orden::findOrFail($idOrden);

        foreach ($request->platillos as $platillo) {
            $menu = Menu::find($platillo['id_menu']);

            DetalleOrden::create([
                'id_orden' => $orden->id,
                'id_menu' => $menu->id,
                'cantidad' => $platillo['cantidad'],
                'precio_unitario' => $menu->precio,
                'subtotal' => $menu->precio * $platillo['cantidad'],
                'nombre_menu' => $menu->nombre,
                'precio_menu' => $menu->precio,
            ]);
        }

        return redirect()->route('ordenes.show', $orden->id)
                         ->with('success', 'Platillos agregados a la orden.');
    }

    public function finalizar($idOrden)
    {
        $orden = Orden::findOrFail($idOrden);
        $orden->estado = 'Completada';
        $orden->save();

        // Liberar la mesa
        $mesa = Mesa::find($orden->id_mesas);
        $mesa->estado = 'disponible';
        $mesa->save();

        return redirect()->route('ordenes.index')
                         ->with('success', 'Orden Completada y mesa liberada.');
    }

    public function cancelar($idOrden)
    {
        $orden = Orden::findOrFail($idOrden);
        $orden->estado = 'Cancelada';
        $orden->save();

        // Liberar la mesa
        $mesa = Mesa::find($orden->id_mesas);
        $mesa->estado = 'disponible';
        $mesa->save();

        return redirect()->route('ordenes.index')
                         ->with('success', 'Orden Cancelada y mesa liberada.');
    }

    public function agregarAjax(Request $request, Orden $orden)
    {
        $menu = Menu::findOrFail($request->id_menu);
        $cantidad = (int) $request->cantidad;

        // Buscar si ya existe ese platillo en la orden
        $detalle = $orden->detalles_orden()->where('id_menu', $menu->id)->first();

        if ($detalle) {
            $detalle->cantidad += $cantidad;
            $detalle->subtotal = $detalle->precio_unitario * $detalle->cantidad;
            $detalle->save();
        } else {
            $detalle = $orden->detalles_orden()->create([
                'id_menu' => $menu->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $menu->precio,
                'subtotal' => $menu->precio * $cantidad,
                'nombre_menu' => $menu->nombre,
                'precio_menu' => $menu->precio,
            ]);
        }

        return response()->json([
            'detalle' => [
                'id' => $detalle->menu->id,
                'nombre' => $detalle->menu->nombre,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario,
                'subtotal' => $detalle->subtotal,
            ],
            'total' => $orden->detalles_orden()->sum('subtotal'),
        ]);
    }

    public function eliminarPlatilloAjax(Request $request, Orden $orden)
    {
        $menuId = $request->id_menu;

        // Buscar el detalle en la orden
        $detalle = $orden->detalles_orden()->where('id_menu', $menuId)->first();

        if ($detalle) {
            if ($detalle->cantidad > 1) {
                $detalle->cantidad -= 1;
                $detalle->subtotal = $detalle->precio_unitario * $detalle->cantidad;
                $detalle->save();
            } else {
                $detalle->delete();
            }
        }

        return response()->json([
            'success' => true,
            'total'   => $orden->detalles_orden()->sum('subtotal')
        ]);
    }
}
