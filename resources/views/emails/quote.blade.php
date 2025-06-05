@component('mail::message')
# Cotización de tu carrito

Hola {{ $customerName }},  

Gracias por tu interés. Aquí tienes los detalles de tu cotización:

## Detalle de la cotización

@if(count($items) === 0)
No hay productos en la cotización.
@else
@component('mail::table')
| Producto         | Cantidad | Precio Unitario | Subtotal      |
| ---------------- |:--------:| ---------------:| -------------:|
@foreach($items as $item)
| {{ $item->name }} | {{ $item->quantity }} | ${{ number_format($item->package_price, 0, ',', '.') }} | ${{ number_format($item->package_price * $item->quantity, 0, ',', '.') }} |
@endforeach
| **Impuestos**    |          |                 | ${{ number_format($taxes, 0, ',', '.') }} |
| **Total**        |          |                 | **${{ number_format($amount, 0, ',', '.') }}** |
@endcomponent
@endif

@if($explanation)
> **Comentarios del cliente:**  
> {{ $explanation }}
@endif

@component('mail::button', ['url' => route('checkout.thanks')])
Confirmar cotización
@endcomponent

Si tienes alguna duda, responde a este correo o escríbenos a {{ config('mail.from.address') }}.

Gracias por preferirnos,<br>
{{ config('app.name') }}
@endcomponent
