<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Orden;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with('user', 'orden')->orderBy('fecha_emision', 'desc');

        // Filtros
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('nombre')) {
            $query->where('nombre_cliente', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_emision', $request->fecha);
        }

        $facturas = $query->paginate(15)->withQueryString(); // withQueryString mantiene los filtros en la paginación

        return view('facturas.index', compact('facturas'));
    }

    public function create($idOrden)
    {
        $orden = Orden::with('detalles_orden.menu')->findOrFail($idOrden);
        return view('facturas.create', compact('orden'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_orden' => 'required|exists:ordenes,id',
            'id_users' => 'required|exists:users,id',
            'tipo_factura' => 'required|string',
            'nombre_cliente' => 'required|string|max:255',
            'nit_cliente' => 'nullable|string|max:20',
            'direccion_cliente' => 'nullable|string|max:255',
            'telefono_cliente' => 'nullable|string|max:20',
        ]);

        $orden = Orden::with('detalles_orden.menu')->findOrFail($request->id_orden);

        // Crear la factura con los totales de la orden
        $factura = Factura::create([
            'id_orden'    => $orden->id,
            'id_users'    => $request->id_users,
            'tipo_factura'=> $request->tipo_factura,
            'fecha_emision' => now(),

            'nombre_cliente' => $request->nombre_cliente,
            'nit_cliente' => $request->nit_cliente,
            'direccion_cliente' => $request->direccion_cliente,
            'telefono_cliente' => $request->telefono_cliente,

            'subtotal'    => $orden->subtotal,
            'impuestos'   => $orden->impuestos,
            'totaliva'    => $orden->totaliva,
            'total'       => $orden->totaliva, // si quieres que "total" sea igual al total con IVA
        ]);

        // Copiar detalles de la orden a la factura
        foreach ($orden->detalles_orden as $detalle) {
            DetalleFactura::create([
                'id_factura'      => $factura->id,
                'id_menu'         => $detalle->menu->id ?? null,
                'cantidad'        => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario, // ya tienes precio del detalle
                'subtotal'        => $detalle->subtotal,       // nuevo: subtotal por platillo
                'nombre_menu'     => $detalle->nombre_menu,
                'precio_menu'     => $detalle->precio_menu,
            ]);
        }

        return redirect()->route('facturas.show', $factura)
                        ->with('success', 'Factura generada correctamente');
    }

    public function show(Factura $factura)
    {
        $factura->load('detalles_factura', 'user', 'orden.mesa');
        return view('facturas.show', compact('factura'));
    }

    /** Exportar a PDF */
    public function exportPDF(Factura $factura)
    {
        $pdf = PDF::loadView('facturas.pdf', compact('factura'));
        return $pdf->stream("factura_{$factura->id}.pdf");
    }

    /** Exportar a XML */
    /** Exportar Factura a XML */
    public function exportXML(Factura $factura)
    {
        $xml = new \SimpleXMLElement('<factura/>');

        // Información general
        $xml->addChild('id', $factura->id);
        $xml->addChild('tipo_factura', htmlspecialchars($factura->tipo_factura));
        $xml->addChild('fecha_emision', $factura->fecha_emision);
        $xml->addChild('total', number_format($factura->totaliva, 2, '.', ''));

        // Datos del cliente
        $cliente = $xml->addChild('cliente');
        $cliente->addChild('nombre', htmlspecialchars($factura->nombre_cliente ?? ''));
        $cliente->addChild('nit', htmlspecialchars($factura->nit_cliente ?? 'N/A'));
        $cliente->addChild('direccion', htmlspecialchars($factura->direccion_cliente ?? 'N/A'));
        $cliente->addChild('telefono', htmlspecialchars($factura->telefono_cliente ?? 'N/A'));

        // Datos de la orden
        $orden = $xml->addChild('orden');
        $orden->addChild('id_orden', $factura->orden->id ?? '');
        $orden->addChild('mesa', $factura->orden->mesa->numero ?? 'Sin mesa');
        $orden->addChild('mesero', htmlspecialchars($factura->user->name ?? 'Desconocido'));

        // Totales
        $totales = $xml->addChild('totales');
        $totales->addChild('subtotal', number_format($factura->subtotal, 2, '.', ''));
        $totales->addChild('impuestos', number_format($factura->impuestos, 2, '.', ''));
        $totales->addChild('total_con_iva', number_format($factura->totaliva, 2, '.', ''));

        // Detalles de productos
        $detalles = $xml->addChild('detalles');
        foreach ($factura->detalles_factura as $detalle) {
            $d = $detalles->addChild('detalle');
            $d->addChild('nombre_menu', htmlspecialchars($detalle->nombre_menu));
            $d->addChild('cantidad', $detalle->cantidad);
            $d->addChild('precio_unitario', number_format($detalle->precio_unitario, 2, '.', ''));
            $d->addChild('subtotal', number_format($detalle->subtotal, 2, '.', ''));
        }

        // Generar respuesta descargable
        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="factura_'.$factura->id.'.xml"');
    }

    public function listarOrdenesPendientes()
    {
        $ordenes = Orden::with(['user', 'mesa', 'detalles_orden'])
                        ->doesntHave('factura') // si tienes relación factura
                        ->get();

        return view('ordenes.index', compact('ordenes'));
    }

    public function exportPDFResumen(Request $request)
    {
        $query = Factura::query();
        $titulo = 'Ventas Totales';

        // Validar fechas si se enviaron ambas
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {

            // Verificar que la fecha fin no sea menor que la inicio
            if ($request->fecha_fin < $request->fecha_inicio) {
                return back()->with('error', 'La fecha final no puede ser menor que la fecha inicial.');
            }

            $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
            $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();

            $query->whereBetween('fecha_emision', [$fechaInicio, $fechaFin]);
            $titulo = "Ventas del {$fechaInicio->format('d/m/Y')} al {$fechaFin->format('d/m/Y')}";
        }

        // Manejar botones rápidos (día, semana, mes, año, total)
        elseif ($request->filled('periodo')) {
            switch ($request->periodo) {
                case 'dia':
                    $query->whereDate('fecha_emision', today());
                    $titulo = 'Ventas del Día';
                    break;

                case 'semana':
                    $query->whereBetween('fecha_emision', [now()->startOfWeek(), now()->endOfWeek()]);
                    $titulo = 'Ventas de la Semana';
                    break;

                case 'mes':
                    $query->whereMonth('fecha_emision', now()->month)
                        ->whereYear('fecha_emision', now()->year);
                    $titulo = 'Ventas del Mes';
                    break;

                case 'anio':
                    $query->whereYear('fecha_emision', now()->year);
                    $titulo = 'Ventas del Año';
                    break;

                case 'general':
                    $titulo = 'Ventas Totales';
                    break;
            }
        }

        // Obtener facturas filtradas
        $facturas = $query->orderBy('fecha_emision', 'desc')->get();
        $total = $facturas->sum('total');

        // Si no hay facturas, mostrar mensaje de advertencia
        if ($facturas->isEmpty()) {
            return back()->with('warning', 'No se encontraron facturas en el rango seleccionado.');
        }

        // Generar el PDF
        $pdf = PDF::loadView('facturas.pdf_resumen', compact('facturas', 'total', 'titulo'));

        // Nombre del archivo más descriptivo
        $nombreArchivo = Str::slug($titulo, '_') . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
}
