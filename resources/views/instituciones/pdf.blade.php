<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instituciones</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Instituciones</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Director</th>
                <th>Logo</th>
                <th>Documento PDF</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instituciones as $institucion)
                <tr>
                    <td>{{ $institucion->id }}</td>
                    <td>{{ $institucion->nombre }}</td>
                    <td>{{ $institucion->direccion }}</td>
                    <td>{{ $institucion->telefono }}</td>
                    <td>{{ $institucion->director ?? '-' }}</td>
                    <td>{{ $institucion->logo ?? '-' }}</td>
                    <td>{{ $institucion->documento_pdf ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
