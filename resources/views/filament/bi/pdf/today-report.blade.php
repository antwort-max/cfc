<h2>Resumen Diario</h2>
@foreach ($daily as $metric)
    <p><strong>{{ $metric['label'] }}:</strong> {{ $metric['value'] }} <br><small>{{ $metric['description'] }}</small></p>
@endforeach

<hr>

<h2>Resumen Mensual</h2>
@foreach ($monthly as $metric)
    <p><strong>{{ $metric['label'] }}:</strong> {{ $metric['value'] }} <br><small>{{ $metric['description'] }}</small></p>
@endforeach