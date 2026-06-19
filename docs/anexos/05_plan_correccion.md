# 05 — Plan de Corrección

## Sistema Braille Inclusivo — PSCP
## Instituto Técnico "Federico Alvarez Plata"
## Fecha: 19 de junio de 2026

---

## FASE 1: Correcciones Críticas

### 1.1 — Completar secciones "No definido"

#### Secciones a REDACTAR CON CONTENIDO:

| Sección | Párrafo | Contenido a generar |
|---|---|---|
| Requerimientos funcionales | 377 | 14 RF basados en los 11 Casos de Uso (ver lista abajo) |
| Requerimientos no funcionales | 379 | 10 RNF de rendimiento, seguridad y usabilidad (ver lista abajo) |
| Tecnologías utilizadas | 387 | Stack: Laravel 13, PHP 8.3+, MySQL, Python 3, AdminLTE, Marlin 1.1.x |
| Herramientas de seguimiento | 389 | Git/GitHub para control de versiones |
| Actividades ejecutadas | 370 | Resumen de actividades de software y hardware realizadas |
| Participación comunitaria | 372 | Colaboración con IBC e instituciones piloto |
| Desarrollo técnico del producto | 374 | Resumen de módulos implementados (Usuarios, Recursos, Instituciones) |

#### Secciones a marcar como [PENDIENTE DE EJECUCIÓN FÍSICA]:

| Sección | Párrafo | Razón |
|---|---|---|
| DEDICATORIA | 1 | Requiere decisión personal del equipo |
| AGRADECIMIENTOS | 3 | Requiere reconocimiento a colaboradores reales |
| Dificultades y soluciones | 391 | Requiere datos de problemas reales encontrados durante ensamblaje |
| Resultados cualitativos | 394 | Requiere encuestas de satisfacción aplicadas a docentes |
| Resultados cuantitativos | 396 | Requiere métricas reales de costo, tiempo impresión, encuestas |
| Impacto en la comunidad | 398 | Requiere evidencia de impacto post-piloto |
| CONCLUSIONES | 400 | Requiere datos de resultados obtenidos |
| Recomendaciones | 402 | Requiere análisis de resultados y lecciones aprendidas |

**Razón:** Estas secciones requieren datos de pruebas de campo, ensamblaje de hardware y validación comunitaria que aún no se han ejecutado. No se pueden inventar.

#### Requerimientos funcionales sugeridos (14 RF):

```
RF-01: El sistema permitirá el inicio y cierre de sesión con email y contraseña
RF-02: El sistema permitirá al Administrador gestionar el catálogo de recursos (CRUD)
RF-03: El sistema permitirá al Solicitante visualizar el catálogo de recursos
RF-04: El sistema permitirá al Administrador gestionar instituciones (CRUD)
RF-05: El sistema permitirá al Administrador gestionar usuarios (CRUD)
RF-06: El sistema traducirá texto en español a Braille Grado 1
RF-07: El sistema generará archivos G-Code para la impresora 3D
RF-08: El sistema mostrará una previsión 2D del recurso antes de solicitar
RF-09: El sistema permitirá al Solicitante solicitar impresión de recursos
RF-10: El sistema calculará automáticamente el consumo de PLA y costo de producción
RF-11: El sistema permitirá al Administrador gestionar solicitudes y actualizar estados
RF-12: El sistema permitirá al Administrador descargar archivos G-Code exclusivamente
RF-13: El sistema generará reportes en PDF y Excel de cada módulo
RF-14: El sistema implementará papelera (SoftDeletes) para todas las entidades
```

#### Requerimientos no funcionales sugeridos (10 RNF):

```
RNF-01: El sistema será una plataforma web responsiva accesible desde navegador
RNF-02: El sistema requerirá conexión activa a internet para su funcionamiento
RNF-03: Las contraseñas se almacenarán encriptadas con algoritmo Bcrypt
RNF-04: El sistema será compatible con lectores de pantalla (NVDA, TalkBack)
RNF-05: Los archivos de imagen no excederán 2 MB de tamaño
RNF-06: Los archivos PDF no excederán 4 MB de tamaño
RNF-07: El sistema responderá en menos de 3 segundos para operaciones CRUD
RNF-08: El sistema será compatible con navegadores Chrome y Firefox
RNF-09: La base de datos se respaldará manualmente en dispositivos locales
RNF-10: El G-Code generado será compatible con firmware Marlin 1.1.x
```

---

### 1.2 — Eliminar párrafo triple en PerfilProyecto

| Campo | Detalle |
|---|---|
| Archivo | PerfilProyectojunio.docx |
| Párrafos a eliminar | 46 y 47 |
| Párrafo a mantener | 45 |
| Texto a mantener | "Para los estudiantes con discapacidad visual: accederán a mapas topográficos, figuras geométricas, reglas de medición y fichas de vocabulario en Braille impresos en relieve de plástico rígido (PLA), un material de alta durabilidad que resiste el tacto frecuente sin deterioro." |

---

### 1.3 — Unificar nombre del rol

| Campo | Detalle |
|---|---|
| Decisión | Mantener "Docente" en código, documentar como "Usuario Solicitante (Docente)" |
| Código | Sin cambios (migración mantiene `enum('Administrador', 'Docente')`) |
| Documentación | Primera mención: "Usuario Solicitante (Docente, Directivo o Tutor)". Menciones posteriores: solo "Solicitante" |
| Diagramas UML | Actor visual: "Solicitante" |
| Nota | Agregar aclaración en 00_indice_convenciones.md |

---

### 1.4 — Implementar migración y modelo de Pedido

#### Migración (schema):

```php
Schema::create('pedidos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('institucion_id')->nullable()->constrained()->nullOnDelete();
    $table->enum('estado', ['Pendiente', 'En impresión', 'Completado', 'Rechazado'])->default('Pendiente');
    $table->date('fecha_solicitud');
    $table->decimal('total_gramos_pla', 8, 2)->default(0);
    $table->decimal('costo_total', 8, 2)->default(0);
    $table->string('gcode_path')->nullable();
    $table->text('motivo_rechazo')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

#### Modelo Pedido.php:

```php
class Pedido extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'institucion_id', 'estado', 'fecha_solicitud',
        'total_gramos_pla', 'costo_total', 'gcode_path', 'motivo_rechazo',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function institucion() { return $this->belongsTo(Institucion::class); }
    public function detalles() { return $this->hasMany(DetallePedido::class); }
}
```

#### Archivos a crear/modificar:
- Crear: `app/Http/Controllers/PedidoController.php`
- Modificar: `routes/web.php` (agregar rutas de pedidos)
- Modificar: `resources/views/pedidos/` (crear vistas)

---

### 1.5 — Eliminar InventarioPla

| Acción | Archivo |
|---|---|
| ELIMINAR | `app/Models/InventarioPla.php` |
| ELIMINAR | `database/migrations/*_create_inventario_plas_table.php` |
| MANTENER | Límite "Exclusión de Control de Inventarios" en ambos documentos |

---

## FASE 2: Correcciones Altas

### 2.1 — Estandarizar "Código Braille Español" (AJUSTE: soporte ñ y acentos)

| Archivo | Cambio |
|---|---|
| DocumentoFinal para 246 | "Unified English Braille (UEB)" → "Código Braille Español publicado por la ONCE" |
| UC06_traducir_braille.puml | "Caracteres soportados (UEB Grado 1)" → "Caracteres soportados (Código Braille Español — Grado 1)" |
| 03_reglas_comunitarias.md | Actualizar regla RB-01 y RB-02 para incluir ñ, á, é, í, ó, ú, ü |
| 00_indice_convenciones.md | Agregar nota sobre estándar Braille utilizado |
| 02_arquitectura_tecnica.md | Actualizar referencia si existe |

**Caracteres a soportar (Código Braille Español):**
- Letras: A-Z, Ñ
- Vocales acentuadas: Á, É, Í, Ó, Ü
- Números: 0-9
- Puntuación: . , ; : ? ! ( ) " -
- Espacio

**Nota:** Mantener mención de UEB SOLO como contexto internacional en el Marco Teórico del DocumentoFinal.

---

### 2.2 — Insertar diagrama de Gantt real

| Campo | Detalle |
|---|---|
| Archivo | DocumentoFinal para 346 |
| Texto actual | "[Insertar diagrama de Gantt — ver Tabla 9]" |
| Acción | Reemplazar con diagrama real (imagen o PlantUML) |

---

### 2.3 — Reconciliar numeración de anexos

| Acción | Detalle |
|---|---|
| Decisión | DocumentoFinal como autoridad |
| PerfilProyecto | Eliminar referencia a "Anexo A: Cronograma" |
| DocumentoFinal | Mantener numeración A-J |

---

### 2.4 — Corregir WCAG (unificar a "pautas basadas en AA")

| Campo | Detalle |
|---|---|
| Archivo | DocumentoFinal para 294 |
| Texto actual | "no se implementará ni garantizará el cumplimiento estricto del estándar internacional de accesibilidad web WCAG 2.1 en su nivel AAA" |
| Texto corregido | "Accesibilidad Web No Certificada: La plataforma web integrará pautas básicas de diseño accesible basadas en WCAG 2.1 nivel AA, sin garantizar el cumplimiento exhaustivo de todos los criterios de éxito de nivel AAA." |

---

### 2.5 — Eliminar menciones a Pantalla LCD y Tarjeta SD

**Decisión técnica:** La máquina operará únicamente mediante conexión USB directa (Tethered Printing) desde la PC del operador. No hay pantalla LCD ni lector de tarjetas SD.

| Archivo | Párrafo | Texto a eliminar/modificar |
|---|---|---|
| PerfilProyecto | 158 | Eliminar "**pantalla LCD 20×4**" de la lista de Hardware de control |
| DocumentoFinal | 315 | Eliminar "**pantalla LCD 20×4**" de la lista de Hardware de control |
| DocumentoFinal | 108 | "transferencia... mediante **memoria SD o conexión local**" → "transferencia mediante **conexión USB directa desde la PC del operador**" |
| DocumentoFinal | 286 | "archivos vía **tarjeta SD o USB**" → "archivos vía **conexión USB directa**" |
| PerfilProyecto | 129 | "archivos vía **tarjeta SD o USB**" → "archivos vía **conexión USB directa**" |

**Archivos docs/ ya correctos (verificados):**
- 02_arquitectura_tecnica.md: ya dice "USB"
- 01_contexto_sociocomunitario.md: ya dice "USB"
- UC09_descargar_gcode.puml: ya dice "USB"

---

## FASE 3: Correcciones Medias

### 3.1 — Estandarizar boquilla a 0.8mm

| Archivo | Párrafo/Línea | Cambio |
|---|---|---|
| PerfilProyecto | 112 | "0.4 mm o 0.8 mm" → "0.8 mm" |
| 02_arquitectura_tecnica.md | 35 | "0.4 mm o 0.8 mm" → "0.8 mm" |
| DocumentoFinal | — | Ya dice 0.8mm consistentemente |

---

### 3.2 — Clarificar número de beneficiarios

| Archivo | Línea | Texto corregido |
|---|---|---|
| 01_contexto_sociocomunitario.md | 43 | "Se estima una población total de aproximadamente 200 estudiantes con discapacidad visual en instituciones de educación especial del municipio de Cochabamba, de los cuales se proyecta atender entre 80 y 150 en el primer año de implementación del servicio." |

---

### 3.3 — Unificar artículos CPE

| Archivo | Línea | Cambio |
|---|---|---|
| 03_reglas_comunitarias.md | 95 | "Art. 17, 61, 112" → "Art. 70, 71" |

---

### 3.4 — Documentar Python Core como "planificado"

| Archivo | Línea | Cambio |
|---|---|---|
| 02_arquitectura_tecnica.md | 81 | "Backend Laravel + Python Core" → "Backend Laravel + Python Core (planificado)" |
| 03_reglas_comunitarias.md | 57 | "La traducción se realiza en el backend (Python Core)" → "La traducción se realizará en el backend (Python Core — módulo planificado)" |

---

### 3.5 — Estandarizar distinción Administrador/Operador

Agregar en 00_indice_convenciones.md:

```
### Definición de roles operativos

| Término | Definición |
|---|---|
| Administrador | Rol del sistema web (columna `rol` en BD). Gestiona usuarios, catálogo, solicitudes, reportes |
| Operador | Persona que físicamente opera la impresora 3D: descarga G-Code, transfiere vía USB, inicia impresión |

Nota: En el equipo de desarrollo, ambas funciones las realiza la misma persona.
La distinción es conceptual, no funcional.
```

---

## FASE 4: Correcciones Bajas

### 4.1 — Corregir doble punto

| Archivo | Párrafo | Cambio |
|---|---|---|
| DocumentoFinal | 4 | "No definido aun.." → "No definido aun." |
| DocumentoFinal | 330 | "uso básico.." → "uso básico." |

---

### 4.2 — Corregir capitalización "3d"

| Archivo | Párrafo | Cambio |
|---|---|---|
| PerfilProyecto | 71 | "impresión 3d" → "impresión 3D" |

---

### 4.3 — Documentar razón de tiempo verbal

| Documento | Tiempo verbal | Razón |
|---|---|---|
| PerfilProyecto | Futuro ("se aplicarán") | Prospectus: describe lo que se planea hacer |
| DocumentoFinal | Pasado ("se aplicaron") | Informe: describe lo que se ejecutó |

**Corrección:** No requiere cambio. Solo aclarar en documento si se cuestiona.

---

### 4.4 — Verificar término "embozadoras"

| Contexto | Texto actual |
|---|---|
| Ambos documentos, sección límites | "embozadoras (impresoras) de papel de impacto industrial" |
| **Acción** | Verificar si "embozadora" es regionalismo boliviano aceptado. Si no: cambiar a "impresoras Braille de impacto industrial" |

---

## Resumen de cambios por archivo

| Archivo | Fase | Cantidad de cambios |
|---|---|---|
| DocumentoFinalPSCP3DJunio.docx | 1, 2, 3, 4 | ~25 cambios (secciones + texto) |
| PerfilProyectojunio.docx | 1, 2, 3, 4 | ~8 cambios (párrafo triple, boquilla, LCD, "3d") |
| 01_contexto_sociocomunitario.md | 3 | 1 cambio (beneficiarios) |
| 02_arquitectura_tecnica.md | 3 | 2 cambios (boquilla, Python Core) |
| 03_reglas_comunitarias.md | 2, 3 | 3 cambios (UEB, CPE, Python Core) |
| 00_indice_convenciones.md | 2, 3 | 2 cambios (Docente/Solicitante, Admin/Operador) |
| UC06_traducir_braille.puml | 2 | 1 cambio (UEB → Código Braille Español) |
| Pedido.php + migración | 1 | Crear modelo + migración |
| InventarioPla.php + migración | 1 | ELIMINAR |

---

## Estimación de esfuerzo

| Fase | Horas estimadas | Dependencias |
|---|---|---|
| Fase 1: Críticos | 8-12 horas | Redacción de contenido técnico |
| Fase 2: Altas | 3-4 horas | Diagramas + correcciones de texto |
| Fase 3: Medias | 2-3 horas | Correcciones de texto |
| Fase 4: Bajas | 1 hora | Correcciones menores |
| **TOTAL** | **14-20 horas** | |

---

## Nota final

Este plan NO incluye:
- Implementación del algoritmo Braille → G-Code (Python Core)
- Implementación del PedidoController y vistas de pedidos
- Pruebas de software ni validación de hardware

Estas son tareas de desarrollo, no de documentación.
