@component('mail::message')


📅 **Fecha:** {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('l d \d\e F \d\e Y') }}

Aquí está el resumen de métricas correspondientes al día de hoy y comparativas mensuales.

---

## 📅 Resumen Diario
@foreach ($data['daily'] as $card)
- **{{ $card['label'] }}:**  
  {{ $card['value'] }}  
  _({{ $card['description'] }})_
@endforeach

---

## 📊 Resumen Mensual
@foreach ($data['monthly'] as $card)
- **{{ $card['label'] }}:**  
  {{ $card['value'] }}  
  _({{ $card['description'] }})_
@endforeach

---

Gracias,<br>
**{{ config('app.name') }}**
@endcomponent
