<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cart->id }}</title>
    <style>
        /* Ajusta márgenes para impresión */
        @page { margin: 20mm; }
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Cotización #{{ $cart->id }}</h1>
    <p><strong>Cliente:</strong> {{ $customerName }}</p>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th class="text-right"> Precio U.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->package_price,0,',','.') }}</td>
                <td class="text-right">${{ number_format($item->package_price * $item->quantity,0,',','.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Impuestos</strong></td>
                <td class="text-right">${{ number_format($cart->taxes,0,',','.') }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td class="text-right"><strong>${{ number_format($cart->amount,0,',','.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($explanation)
    <p><strong>Comentarios:</strong> {{ $explanation }}</p>
    @endif
</body>
</html>
