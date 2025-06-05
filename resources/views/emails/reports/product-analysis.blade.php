@component('mail::message')
# Informe de Análisis de Productos

Adjunto encontrarás el reporte en PDF con los 25 productos más vendidos por periodo y sus totales de venta.

@foreach ($data as $days => $group)
- **Últimos {{ $days }} días:** {{ number_format($group['period_total_sales'], 0, '.', ',') }}
@endforeach

Gracias por tu atención.  
@endcomponent
