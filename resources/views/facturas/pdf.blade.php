<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $factura->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
        }

        h1, h2, h3 {
            margin-bottom: 5px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 22px;
            margin: 0;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 3px 0;
        }

        .cliente-info, .empresa-info {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .empresa-info {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #888;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 12px;
        }

        tfoot td {
            border: none;
            padding: 5px 8px;
        }

        .totales {
            margin-top: 10px;
            width: 40%;
            float: right;
        }

        .totales table {
            border: none;
        }

        .totales td {
            text-align: right;
            padding: 5px 8px;
        }

        .totales tr td:first-child {
            text-align: left;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>Restaurante ""</h1>
        <p>Boulevard Orden de Malta, San Salvador | Tel: (503) 2222-0000</p>
        <p><strong>Factura #{{ $factura->id }}</strong></p>
    </div>

    {{-- Información general --}}
    <div class="info">
        <div class="cliente-info">
            <h3>Datos del Cliente</h3>
            <p><strong>Nombre:</strong> {{ $factura->nombre_cliente }}</p>
            <p><strong>NIT:</strong> {{ $factura->nit_cliente ?? 'N/A' }}</p>
            <p><strong>Dirección:</strong> {{ $factura->direccion_cliente ?? 'N/A' }}</p>
            <p><strong>Teléfono:</strong> {{ $factura->telefono_cliente ?? 'N/A' }}</p>
        </div>

        <div class="empresa-info">
            <h3>Datos de la Orden</h3>
            <p><strong>Mesa:</strong> {{ $factura->orden->mesa->numero ?? 'Sin mesa' }}</p>
            <p><strong>Fecha de Emisión:</strong> {{ $factura->fecha_emision }}</p>
            <p><strong>Tipo de Factura:</strong> {{ ucfirst($factura->tipo_factura) }}</p>
            <p><strong>Atendido por:</strong> {{ $factura->user->name ?? 'No asignado' }}</p>
        </div>
    </div>

    {{-- Detalles --}}
    <h3>Detalles de la Factura</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Producto</th>
                <th style="width: 15%;">Cantidad</th>
                <th style="width: 15%;">Precio Unitario</th>
                <th style="width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factura->detalles_factura as $detalle)
                <tr>
                    <td>{{ $detalle->nombre_menu }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totales --}}
    <div class="totales">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>${{ number_format($factura->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IVA (13%):</strong></td>
                <td>${{ number_format($factura->impuestos, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong>${{ number_format($factura->totaliva, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Gracias por su preferencia. ¡Vuelva pronto!</p>
    </div>
</body>
</html>
