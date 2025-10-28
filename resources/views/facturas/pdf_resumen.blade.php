<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #888;
            padding: 8px;
            text-align: center;
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
        <p><strong>{{ $titulo }}</strong></p>
    </div>

    {{-- Tabla de facturas --}}
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
                <tr>
                    <td>#{{ $factura->id }}</td>
                    <td>{{ $factura->nombre_cliente ?? 'Sin cliente' }}</td>
                    <td>{{ ucfirst($factura->tipo_factura) }}</td>
                    <td>{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</td>
                    <td>${{ number_format($factura->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total general:</strong></td>
                <td><strong>${{ number_format($total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <p>Reporte generado automáticamente — {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>
