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
        // Cargamos relaciones, ordenamos por la última orden agregada y paginamos
        $ordenes = Orden::with('mesa.area', 'user', 'detalles_orden.menu')
            ->orderBy('fecha_orden', 'desc') // Últimas primero
            ->paginate(15); // Límite de 15 por página

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

        // 1. Calcular el subtotal a partir de los platillos seleccionados
        $subtotal = 0;

        foreach ($request->platillos as $platillo) {
            $menu = Menu::find($platillo['id_menu']);
            $subtotal += $menu->precio * $platillo['cantidad'];
        }

        // 2. Calcular el impuesto (13%) y el total con IVA
        $impuestos = $subtotal * 0.13;
        $totaliva = $subtotal + $impuestos;

        // 3. Crear la orden con los totales
        $orden = Orden::create([
            'id_mesas' => $request->id_mesas,
            'id_users' => Auth::id(),
            'estado' => 'En Preparación',
            'fecha_orden' => now(),
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'totaliva' => $totaliva,
        ]);

        // 4. Insertar los platillos en detalles_orden
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

        // 5. Cambiar el estado de la mesa a "ocupada"
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
        $request->validate([
            'id_mesas' => 'required|exists:mesas,id',
            'platillos' => 'required|array|min:1',
            'platillos.*.id_menu' => 'required|exists:menus,id',
            'platillos.*.cantidad' => 'required|integer|min:1',
        ]);

        $orden = Orden::findOrFail($id);

        // 1️⃣ Recalcular los valores de la orden
        $orden->id_mesas = $request->id_mesas;

        $subtotal = 0;
        foreach ($request->platillos as $platillo) {
            $menu = Menu::find($platillo['id_menu']);
            $subtotal += $menu->precio * $platillo['cantidad'];
        }

        $impuestos = $subtotal * 0.13;
        $totaliva = $subtotal + $impuestos;

        // 2️⃣ Actualizar datos y devolver al estado "En Preparación"
        $orden->subtotal = $subtotal;
        $orden->impuestos = $impuestos;
        $orden->totaliva = $totaliva;
        $orden->estado = 'En Preparación'; // 👈 Aquí está el cambio
        $orden->save();

        // 3️⃣ Actualizar los platillos (borrar y volver a insertar)
        $orden->detalles_orden()->delete();
        foreach ($request->platillos as $platillo) {
            $menu = Menu::find($platillo['id_menu']);
            $orden->detalles_orden()->create([
                'id_menu' => $menu->id,
                'cantidad' => $platillo['cantidad'],
                'precio_unitario' => $menu->precio,
                'subtotal' => $menu->precio * $platillo['cantidad'],
                'nombre_menu' => $menu->nombre,
                'precio_menu' => $menu->precio,
            ]);
        }

        // 4️⃣ Si la mesa cambia, actualizar su estado también
        $mesa = Mesa::find($request->id_mesas);
        $mesa->estado = 'ocupada';
        $mesa->save();

        return redirect()->route('ordenes.index')
            ->with('success', 'Orden actualizada correctamente y vuelta a preparación.');
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
