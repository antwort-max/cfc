<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 0.5rem; }
        th, td { border: 1px solid #ccc; padding: 0.25rem; text-align: left; }
        th { background: #f5f5f5; font-size: 9px; }
        td { font-size: 8px; }
        .period-header { margin-top: 1rem; font-size: 11px; font-weight: bold; }
        .generated-at { font-size: 8px; color: #555; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <h2 style="font-size:14px; margin-bottom:0.25rem;">Centro Ferretero Caupolicán</h2>
    <h2 style="font-size:14px; margin-bottom:0.5rem;">Análisis de Stock productos más vendidos</h2>

    {{-- Fecha y hora de generación --}}
    <div class="generated-at">
        Generado el: {{ now()->format('d-m-Y H:i') }}
    </div>

    @foreach ($data as $days => $group)
        {{-- Cabecera del periodo --}}
        <div class="period-header">
            Últimos {{ $days }} días — Total Ventas: {{ number_format($group['period_total_sales'], 0, '.', ',') }}
        </div>
        <br>
        {{-- Tabla de items --}}
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Producto</th>
                    <th>Ventas</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($group['items'] as $item)
                    <tr>
                        <td>{{ $item->product_sku }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ number_format($item->total_sold, 0, '.', ',') }}</td>
                        <td>{{ $item->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>