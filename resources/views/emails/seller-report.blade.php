@component('mail::message')
# Informe de Ventas

Hola,  
Adjunto encontrarás el informe de ventas por vendedor correspondiente a los últimos **{{ $period }} días**.

Gracias,<br>
{{ config('app.name') }}
@endcomponent