# Plantilla rapida para modulo de examen Laravel

Usa este archivo durante el examen como guia de reemplazo. Cambia todos los textos entre corchetes:

- `[NombreModelo]`: singular PascalCase. Ejemplo: `Libro`
- `[nombreModelo]`: singular camelCase. Ejemplo: `libro`
- `[NombreModeloPlural]`: plural PascalCase. Ejemplo: `Libros`
- `[NombreTabla]`: plural snake_case. Ejemplo: `libros`
- `[CampoArchivo]`: nombre del campo que guarda la ruta. Ejemplo: `archivo_pdf`
- `[CamposReporte]`: solo las columnas pedidas para PDF y Excel. Ejemplo: `titulo`, `resumen`

En este proyecto ya estan instalados `barryvdh/laravel-dompdf` y `maatwebsite/excel`.

## 1. Modelo y migracion

```bash
php artisan make:model [NombreModelo] -m
```

En la migracion generada en `database/migrations/...create_[NombreTabla]_table.php`:

```php
public function up(): void
{
    Schema::create('[NombreTabla]', function (Blueprint $table) {
        $table->id();
        $table->string('titulo', 255);       // Cambiar por los campos del examen
        $table->text('descripcion');         // Cambiar por los campos del examen
        $table->integer('cantidad');         // Cambiar o borrar si no aplica
        $table->decimal('precio', 10, 2);    // Cambiar o borrar si no aplica
        $table->string('[CampoArchivo]', 255);
        $table->timestamps();
    });
}
```

Equivalencias rapidas de tipos:

```php
$table->string('nombre', 255);      // VARCHAR
$table->text('descripcion');        // TEXT
$table->integer('cantidad');        // entero
$table->unsignedBigInteger('x_id'); // bigint unsigned
$table->decimal('precio', 10, 2);   // decimal
$table->date('fecha');              // fecha
$table->boolean('activo');          // booleano
```

En `app/Models/[NombreModelo].php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class [NombreModelo] extends Model
{
    use HasFactory;

    protected $table = '[NombreTabla]';

    protected $fillable = [
        'titulo',
        'descripcion',
        'cantidad',
        'precio',
        '[CampoArchivo]',
    ];
}
```

## 2. Seeder

En `database/seeders/DatabaseSeeder.php`, agrega el `use` arriba:

```php
use App\Models\[NombreModelo];
```

Dentro de `run()`:

```php
[NombreModelo]::updateOrCreate(
    ['titulo' => 'Registro de prueba 1'],
    [
        'descripcion' => 'Descripcion del registro 1',
        'cantidad' => 10,
        'precio' => 25.50,
        '[CampoArchivo]' => '[NombreTabla]/archivo-prueba.pdf',
    ]
);

[NombreModelo]::updateOrCreate(
    ['titulo' => 'Registro de prueba 2'],
    [
        'descripcion' => 'Descripcion del registro 2',
        'cantidad' => 20,
        'precio' => 40.00,
        '[CampoArchivo]' => '[NombreTabla]/archivo-prueba-2.pdf',
    ]
);
```

Ejecutar cuando corresponda:

```bash
php artisan migrate --seed
```

## 3. Export Excel

Crea la clase:

```bash
php artisan make:export [NombreModeloPlural]Export --model=[NombreModelo]
```

En `app/Exports/[NombreModeloPlural]Export.php`:

```php
<?php

namespace App\Exports;

use App\Models\[NombreModelo];
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class [NombreModeloPlural]Export implements FromCollection, WithHeadings
{
    public function collection()
    {
        return [NombreModelo]::select([
            'titulo',
            'descripcion',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Titulo',
            'Descripcion',
        ];
    }
}
```

Importante: en `select()` y `headings()` deja unicamente los campos solicitados para el reporte.

## 4. Controlador

```bash
php artisan make:controller [NombreModelo]Controller
```

En `app/Http/Controllers/[NombreModelo]Controller.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Exports\[NombreModeloPlural]Export;
use App\Models\[NombreModelo];
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class [NombreModelo]Controller extends Controller
{
    public function index()
    {
        $[NombreTabla] = [NombreModelo]::all();

        return view('[NombreTabla].index', compact('[NombreTabla]'));
    }

    public function create()
    {
        return view('[NombreTabla].create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            '[CampoArchivo]' => 'required|file|mimes:pdf|max:2048',
        ]);

        $data['[CampoArchivo]'] = $request->file('[CampoArchivo]')
            ->store('[NombreTabla]', 'public');

        [NombreModelo]::create($data);

        return redirect()->route('[NombreTabla].index')
            ->with('success', 'Registro creado correctamente.');
    }

    public function exportarPdf()
    {
        $[NombreTabla] = [NombreModelo]::select([
            'titulo',
            'descripcion',
        ])->get();

        $pdf = Pdf::loadView('[NombreTabla].pdf', compact('[NombreTabla]'));

        return $pdf->stream('[NombreTabla].pdf');
    }

    public function exportarExcel()
    {
        return Excel::download(new [NombreModeloPlural]Export, '[NombreTabla].xlsx');
    }
}
```

Validaciones rapidas para archivo:

```php
'[CampoArchivo]' => 'required|file|mimes:pdf|max:2048',
'[CampoArchivo]' => 'required|image|mimes:jpg,jpeg,png|max:2048',
'[CampoArchivo]' => 'required|file|mimes:doc,docx,pdf|max:4096',
```

## 5. Vistas AdminLTE

Crea la carpeta:

```bash
mkdir -p resources/views/[NombreTabla]
```

### `resources/views/[NombreTabla]/index.blade.php`

```blade
@extends('adminlte::page')

@section('title', '[NombreModeloPlural]')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>[NombreModeloPlural]</h1>
        <a href="{{ route('[NombreTabla].create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Archivo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($[NombreTabla] as $[nombreModelo])
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $[nombreModelo]->titulo }}</td>
                        <td>{{ $[nombreModelo]->descripcion }}</td>
                        <td>{{ $[nombreModelo]->cantidad }}</td>
                        <td>{{ $[nombreModelo]->precio }}</td>
                        <td>
                            @if($[nombreModelo]->[CampoArchivo])
                                <a href="{{ asset('storage/' . $[nombreModelo]->[CampoArchivo]) }}"
                                   class="btn btn-sm btn-info"
                                   target="_blank">
                                    <i class="fas fa-file"></i> Ver archivo
                                </a>
                            @else
                                <span class="text-muted">Sin archivo</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('[NombreTabla].pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('[NombreTabla].excel') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>
</div>
@stop
```

### `resources/views/[NombreTabla]/create.blade.php`

```blade
@extends('adminlte::page')

@section('title', 'Crear [NombreModelo]')

@section('content_header')
    <h1>Crear [NombreModelo]</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Formulario de registro</h3>
    </div>

    <form action="{{ route('[NombreTabla].store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label for="titulo">Titulo</label>
                <input type="text" name="titulo" id="titulo"
                       class="form-control @error('titulo') is-invalid @enderror"
                       value="{{ old('titulo') }}">
                @error('titulo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripcion</label>
                <textarea name="descripcion" id="descripcion"
                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad"
                           class="form-control @error('cantidad') is-invalid @enderror"
                           value="{{ old('cantidad') }}">
                    @error('cantidad') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 form-group">
                    <label for="precio">Precio</label>
                    <input type="number" step="0.01" name="precio" id="precio"
                           class="form-control @error('precio') is-invalid @enderror"
                           value="{{ old('precio') }}">
                    @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="[CampoArchivo]">Archivo</label>
                <input type="file" name="[CampoArchivo]" id="[CampoArchivo]"
                       class="form-control @error('[CampoArchivo]') is-invalid @enderror"
                       accept=".pdf">
                @error('[CampoArchivo]') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
            <a href="{{ route('[NombreTabla].index') }}" class="btn btn-default">Cancelar</a>
        </div>
    </form>
</div>
@stop
```

### `resources/views/[NombreTabla]/pdf.blade.php`

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de [NombreModeloPlural]</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de [NombreModeloPlural]</h1>

    <table>
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Descripcion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($[NombreTabla] as $[nombreModelo])
                <tr>
                    <td>{{ $[nombreModelo]->titulo }}</td>
                    <td>{{ $[nombreModelo]->descripcion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```

## 6. Rutas

En `routes/web.php`, agrega el `use` arriba:

```php
use App\Http\Controllers\[NombreModelo]Controller;
```

Dentro del grupo `Route::middleware('auth')->group(function () { ... });`, coloca las exportaciones antes del resource:

```php
Route::get('[NombreTabla]/exportar/pdf', [[NombreModelo]Controller::class, 'exportarPdf'])
    ->name('[NombreTabla].pdf');

Route::get('[NombreTabla]/exportar/excel', [[NombreModelo]Controller::class, 'exportarExcel'])
    ->name('[NombreTabla].excel');

Route::resource('[NombreTabla]', [NombreModelo]Controller::class)
    ->only(['index', 'create', 'store']);
```

No agregues `role:Administrador` si el enunciado dice que cualquier rol autenticado puede acceder.

## 7. Menu AdminLTE

En `config/adminlte.php`, dentro de `'menu' => [ ... ]`:

```php
[
    'text' => '[NombreModeloPlural]',
    'route' => '[NombreTabla].index',
    'icon' => '[IconoFontAwesome]',
],
```

Ejemplo:

```php
[
    'text' => 'Libros',
    'route' => 'libros.index',
    'icon' => 'fas fa-book',
],
```

## 8. Checklist final del examen

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan route:list --name=[NombreTabla]
```

Revisa en navegador:

- `http://localhost:8000/[NombreTabla]`
- Crear registro con archivo real.
- Abrir archivo desde el boton de la tabla.
- Exportar PDF.
- Exportar Excel.

