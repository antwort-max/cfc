<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Ventas por Vendedor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; }
        td.text-left, th.text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>Centro Ferretero Caupolicán</h2>
    <br>
    <h3>Informe de Ventas por Vendedor (Últimos {{ $period }} días)</h3>
    <p>Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th class="text-left">#</th>
                <th class="text-left">Vendedor</th>
                <th>Total Ventas</th>
                <th>% Part.</th>
                <th>Cantidad Docs</th>
                <th>Promedio por Doc</th>
                <th>Margen</th>
                <th>Contribución</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topSellers as $index => $seller)
                <tr>
                    <td class="text-left">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $seller->seller_name }}</td>
                    <td>${{ number_format($seller->products_sales, 0, ',', '.') }}</td>
                    <td>{{ number_format($seller->percentage, 2,',','.') }} %</td>
                    <td>{{ $seller->document_count }}</td>
                    <td>${{ number_format($seller->avg_per_document, 0, ',', '.') }}</td>
                    <td>{{ number_format($seller->margin, 1, ',', '.') }}%</td>
                    <td>${{ number_format($seller->profit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
