<h2>Centro Ferretero Caupolicán</a> 
<hr>
<h3>Listado de Atrasos - {{ now()->format('F Y') }}</h3>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Empleado</th>
            <th>Atrasos</th>
            <th>Tiempo perdido</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            @php
                $hours = intdiv($item['total_minutes'], 60);
                $minutes = $item['total_minutes'] % 60;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['employee'] }}</td>
                <td>{{ $item['count'] }}</td>
                <td>{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
