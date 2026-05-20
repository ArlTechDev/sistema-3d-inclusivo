<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Recursos 3D Inclusivos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Catálogo de Recursos 3D Inclusivos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Gramos PLA</th>
                <th>Tiempo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recursos as $recurso)
                <tr>
                    <td>{{ $recurso->id }}</td>
                    <td>{{ $recurso->titulo }}</td>
                    <td>{{ $recurso->gramos_pla }}</td>
                    <td>{{ $recurso->tiempo_minutos }}</td>
                    <td>{{ $recurso->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
