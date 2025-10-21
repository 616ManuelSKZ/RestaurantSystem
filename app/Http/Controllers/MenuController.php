<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Categoria;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::with('menus')->get();
        return view('menus.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categorias = Categoria::all();
        $categoriaSeleccionada = $request->categoria;
        return view('menus.create', compact('categorias', 'categoriaSeleccionada'));
    }

    public function toggleDisponibilidad(Request $request)
    {
        // Seguridad: solo administradores
        if (!auth()->user() || auth()->user()->rol !== 'administrador') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $menu = Menu::findOrFail($request->id);
        $menu->disponible = !$menu->disponible;
        $menu->save();

        return response()->json(['success' => true, 'estado' => $menu->disponible]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'disponible' => 'required|boolean',
            'id_categoria' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('imagenes', 'public');
        }

        Menu::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'disponible' => $request->disponible,
            'id_categoria' => $request->id_categoria,
            'imagen' => $rutaImagen,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menú creado exitosamente.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categorias = Categoria::all();
        return view('menus.edit', compact('menu', 'categorias'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'disponible' => 'required|boolean',
            'id_categoria' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $menu = Menu::findOrFail($id);

        // Si se sube una nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($menu->imagen && \Storage::disk('public')->exists($menu->imagen)) {
                \Storage::disk('public')->delete($menu->imagen);
            }

            // Guardar nueva imagen
            $nuevaRuta = $request->file('imagen')->store('imagenes', 'public');
            $menu->imagen = $nuevaRuta;
        }

        $menu->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'disponible' => $request->disponible,
            'id_categoria' => $request->id_categoria,
            'imagen' => $menu->imagen,
        ]);
        return redirect()->route('menus.index')->with('success', 'Menú actualizado exitosamente.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */    
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menú eliminado exitosamente.');
    }

    public function buscar(Request $request)
    {
        $query = $request->get('q', '');

        // Si no hay búsqueda, retorna vacío
        if (empty($query)) {
            return response()->json([]);
        }

        // Buscar por nombre de platillo (LIKE)
        $menus = \App\Models\Menu::where('nombre', 'like', "%{$query}%")
            ->where('disponible', true)
            ->with('categoria') // Incluye la categoría si la necesitas
            ->take(10) // límite de resultados
            ->get(['id', 'nombre', 'precio', 'imagen', 'id_categoria', 'disponible']);

        return response()->json($menus);
    }
}
