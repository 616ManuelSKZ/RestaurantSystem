<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Orden;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Response;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = Factura::with('user', 'orden')->get();
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
        ]);

        $orden = Orden::with('detalles_orden.menu')->findOrFail($request->id_orden);

        $factura = Factura::create([
            'id_orden' => $orden->id,
            'id_users' => $request->id_users,
            'tipo_factura' => $request->tipo_factura,
            'fecha_emision' => now(),
            'total' => $orden->total,
        ]);

        foreach ($orden->detalles_orden as $detalle) {
            DetalleFactura::create([
                'id_factura' => $factura->id,
                'id_menu' => $detalle->menu->id ?? null,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->menu->precio ?? 0,
                'nombre_menu' => $detalle->menu->nombre ?? '',
                'precio_menu' => $detalle->menu->precio ?? 0,
            ]);
        }

        return redirect()->route('facturas.show', $factura)->with('success', 'Factura generada correctamente');
    }

    public function show(Factura $factura)
    {
        $factura->load('detalles_factura', 'user', 'orden.mesa');
        return view('facturas.show', compact('factura'));
    }

    /** 🧾 Exportar a PDF */
    public function exportPDF(Factura $factura)
    {
        $pdf = PDF::loadView('facturas.pdf', compact('factura'));
        return $pdf->download("factura_{$factura->id}.pdf");
    }

    /** 🧩 Exportar a XML */
    public function exportXML(Factura $factura)
    {
        $xml = new \SimpleXMLElement('<factura/>');
        $xml->addChild('id', $factura->id);
        $xml->addChild('tipo', $factura->tipo_factura);
        $xml->addChild('fecha_emision', $factura->fecha_emision);
        $xml->addChild('total', $factura->total);

        $detalles = $xml->addChild('detalles');
        foreach ($factura->detalles_factura as $detalle) {
            $d = $detalles->addChild('detalle');
            $d->addChild('nombre', $detalle->nombre_menu);
            $d->addChild('cantidad', $detalle->cantidad);
            $d->addChild('precio_unitario', $detalle->precio_unitario);
        }

        return Response::make($xml->asXML(), 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="factura_'.$factura->id.'.xml"',
        ]);
    }

    public function listarOrdenesPendientes()
    {
        $ordenes = Orden::with(['user', 'mesa', 'detalles_orden'])
                        ->doesntHave('factura') // si tienes relación factura
                        ->get();

        return view('ordenes.index', compact('ordenes'));
    }

}
