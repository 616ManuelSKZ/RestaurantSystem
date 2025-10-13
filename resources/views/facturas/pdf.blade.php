<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $factura->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Factura #{{ $factura->id }}</h2>
    <p><strong>Cliente:</strong> {{ $factura->user->name ?? 'Sin cliente' }}</p>
    <p><strong>Mesa:</strong> {{ $factura->orden->mesa->numero ?? 'Sin mesa' }}</p>
    <p><strong>Fecha de emisión:</strong> {{ $factura->fecha_emision }}</p>
    <p><strong>Tipo de factura:</strong> {{ $factura->tipo_factura }}</p>

    <h3>Detalles de la factura</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factura->detalles_factura as $detalle)
                <tr>
                    <td>{{ $detalle->nombre_menu }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->precio_menu * $detalle->cantidad, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align: right; font-weight: bold;">
        Total: ${{ number_format($factura->total, 2) }}
    </p>
</body>
</html>
