<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de Impresión</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { font-size: 16px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }
        th { background: #ddd; }
        .estado { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Reporte de Solicitudes de Impresión</h1>
    <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Solicitante</th>
                <th>Institución</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Gramos PLA</th>
                <th>Costo (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->id }}</td>
                    <td>{{ $pedido->user?->name ?? '-' }}</td>
                    <td>{{ $pedido->institucion?->nombre ?? '-' }}</td>
                    <td>{{ $pedido->fecha_solicitud?->format('d/m/Y') ?? '-' }}</td>
                    <td class="estado">{{ $pedido->estado }}</td>
                    <td>{{ $pedido->total_gramos_pla }}</td>
                    <td>{{ number_format($pedido->costo_total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay solicitudes registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
