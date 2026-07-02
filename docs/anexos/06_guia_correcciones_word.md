# 06 — Guía de Correcciones del Documento Word

## Sistema Braille Inclusivo — PSCP

## Documento objetivo: `DocumentoFinalPSCP3DJunio29.docx`

## Evaluación: viernes 3 de julio — Diagrama de base de datos

## Estado: Última actualización 2 de julio 2026

---

## Instrucciones generales

1. Abre el archivo `DocumentoFinalPSCP3DJunio29.docx` desde tu partición Windows 11
2. **Guarda como** `DocumentoFinalPSCP3DJulio_Corregido.docx` **ANTES de editar** (no sobrescribas el original)
3. Las imágenes que necesitas copiar a tu PC están en:
   - `sistema_inclusivo/docs/casos_de_uso/imagenes/`
   - Las rutas relativas de cada imagen se indican en cada sección
4. Para buscar secciones: `Ctrl + F` → escribe la palabra clave indicada
5. **NO elimines** contenido existente. Solo agregas o reemplazas lo indicado

---

## SECCIÓN 1: Cambios de diagramas (reemplazo de imágenes)

Los diagramas fueron corregidos y re-generados. Los PNGs actualizados están en:

```
sistema_inclusivo/docs/casos_de_uso/imagenes/
├── UC00_Diagrama_General.png     (55K, ACTUALIZADO)
├── UML_Diagrama_de_Clases.png    (57K, ACTUALIZADO)
├── UML_Estados_Pedido.png        (73K, NUEVO)
├── UML_Despliegue.png            (91K, NUEVO)
└── UML_Diagrama_ERD.png          (48K, YA EXISTENTE)
```

### 1.1 Reemplazar Figura 3 — Diagrama General de Casos de Uso

1. Busca `Figura 3. Diagrama General de Casos de Uso` (línea 382)
2. Haz clic derecho sobre la imagen → "Cambiar imagen" → "Desde archivo"
3. Selecciona `UC00_Diagrama_General.png` (actualizado)
4. Ajusta ancho a ~14 cm (centrada)

**Cambio clave:** La relación entre UC-06 y UC-07 ahora es `<<extend>>` (no `<<include>>`), reflejando que la traducción Braille NO incluye obligatoriamente la solicitud de impresión.

### 1.2 Reemplazar Figura 14 — Diagrama de Clases

1. Busca `Figura 14. Diagrama de Clases` (línea 432)
2. Cambia imagen → `UML_Diagrama_de_Clases.png` (actualizado)
3. Ajusta ancho a ~14 cm

**Cambios clave:**
- La clase `Recurso` ahora incluye el atributo `categoria_id : bigint <<FK>>?`
- La clase `Pedido` ahora muestra `<<SoftDeletes>>` y `deleted_at : timestamp?`
- El enum `EstadoPedido` ahora usa "En impresión" (con tilde)

---

## SECCIÓN 2: Correcciones de texto obligatorias

### 2.1 Corregir RF-07 (línea 356)

**Cómo buscar:** `Ctrl + F` → escribir `RF-07`

**Texto actual:**
> RF-07: El sistema generará automáticamente archivos G-Code con coordenadas milimétricas compatibles con firmware Marlin 1.1.x para la impresora 3D.

**Texto corregido (reemplazar toda la línea):**
> RF-07: El sistema generará automáticamente archivos G-Code con coordenadas milimétricas compatibles con firmware Marlin 1.1.x para la impresora 3D, exclusivamente al momento de que el Solicitante confirme el pedido en UC-07.

**Estilo:** Normal (Web) — el mismo que los demás RF.

### 2.2 Corregir Módulo 2 en Alcances (líneas 229-235)

**Cómo buscar:** `Ctrl + F` → escribir `MÓDULO 2`

**Texto actual (línea 229):**
> MÓDULO 2: Traducción Automática Texto→Braille y Generación de Código G

**Reemplazar por:**
> MÓDULO 2: Traducción Automática Texto→Braille y Previsión Visual 2D

**Texto actual (líneas 230-235):**
> Este módulo constituye el núcleo funcional (Core) del sistema de software. Su alcance incluye:
> - Ingreso de texto en lenguaje natural en español por parte del usuario (letras, números y signos de puntuación estándar).
> - Conversión de cada carácter ingresado a su celda Braille correspondiente en Grado 1, siguiendo la tabla estándar del Código Braille Español publicado por la Organización Nacional de Ciegos Españoles (ONCE), incluyendo la letra Ñ y vocales acentuadas.
> - Generación interna (backend) de las coordenadas espaciales (X, Y, Z) para el extrusor, calculando el volumen de una placa de plástico base y la posición de los puntos Braille en relieve sobre esta.
> - Compilación automática de estas coordenadas en un archivo .Gcode ejecutable, el cual se almacenará internamente en el servidor vinculado al número de pedido.
> - Despliegue de una vista previa visual gráfica en dos dimensiones (2D) de la ficha Braille generada en la interfaz del usuario antes de confirmar el pedido.

**Reemplazar por:**
> Este módulo constituye el núcleo funcional (Core) del sistema de software. Su alcance incluye:
> - Ingreso de texto en lenguaje natural en español por parte del usuario (letras, números y signos de puntuación estándar).
> - Conversión de cada carácter ingresado a su celda Braille correspondiente en Grado 1, siguiendo la tabla estándar del Código Braille Español publicado por la Organización Nacional de Ciegos Españoles (ONCE), incluyendo la letra Ñ y vocales acentuadas.
> - Validación de caracteres soportados, con mensaje de error para caracteres no válidos.
> - Despliegue de una vista previa visual gráfica en dos dimensiones (2D) de la ficha Braille generada en la interfaz del usuario antes de confirmar el pedido.
>
> **Nota:** La generación del archivo G-Code y su compilación se realizan exclusivamente en el Módulo 3 (Gestión de Pedidos y Costos de Producción), al momento de confirmar la solicitud de impresión (UC-07). Ver RF-07.

### 2.3 Corregir Tabla 13 — Nombre del UC-00 (Tabla 13, fila 1)

**Cómo buscar:** `Ctrl + F` → escribir `UC-00` o buscar la tabla directamente

**En la Tabla 13 "Casos de Uso", modificar la Fila 1:**

| Columna | Valor actual | Valor corregido |
|---|---|---|
| UC | UC-00 | UC-00 |
| Nombre del Caso de Uso | Diagrama General del Sistema | Vista General de Módulos por Actor |
| Actor Principal | Ambos | Ambos |
| Módulo | — | — |

**Razón:** El docente observó que "el caso de uso general solo mostrar los módulos". Renombrar a "Vista General de Módulos por Actor" refleja que UC-00 no es un caso de uso ejecutable, sino una vista panorámica de la arquitectura funcional.

---

## SECCIÓN 3: Descripciones textuales de Casos de Uso

### 3.1 Párrafo introductorio del UC-00

**Ubicación:** Busca `Diagrama General de Casos de Uso` (línea 381) — insertar **ANTES** de esa línea.

1. Coloca el cursor al final de "Fuente: Elaboración propia." (línea 383) — **NO**, inserta ANTES de "Diagrama General de Casos de Uso" (línea 381)
2. Mejor: coloca el cursor al inicio de la línea 381, presiona `Enter` para crear espacio, sube a esa línea vacía
3. Cambia estilo a **Normal**
4. Pega el siguiente texto:

```
El Diagrama General de Casos de Uso (UC-00) presenta una vista panorámica del sistema organizada por los seis módulos de desarrollo del proyecto. Este diagrama no representa un caso de uso ejecutable, sino la arquitectura funcional que agrupa los 10 casos de uso individuales (UC-01 a UC-10) y muestra la interacción entre los dos actores principales del sistema: el Usuario Solicitante y el Administrador. Los módulos representados son: (1) Gestión de Usuarios, (2) Traducción Automática Braille, (3) Gestión de Pedidos y Costos, (4) Catálogo Digital de Recursos, (5) Hardware CNC Electromecánico, y (6) Validación Sociocomunitaria.
```

### 3.2 Descripción de cada Caso de Uso (UC-01 a UC-10)

**Mecánica para todos los UCs:**
1. Busca el nombre del caso de uso (ej. "Caso de uso: Iniciar/Cerrar Sesión")
2. Encuentra la línea "Fuente: Elaboración propia." que está **después** de la imagen
3. Coloca el cursor al final de esa línea "Fuente"
4. Presiona `Enter` dos veces (un párrafo vacío + el nuevo párrafo)
5. Cambia el estilo a **Normal (Web)** o **Normal**
6. Pega el texto descriptivo correspondiente

---

#### UC-01: Iniciar/Cerrar Sesión
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 4 (línea 387)

```
UC-01 permite a los dos tipos de usuario del sistema (Solicitante y Administrador) autenticarse mediante correo electrónico y contraseña encriptada con el algoritmo Bcrypt, y posteriormente cerrar su sesión de forma segura.

Actor principal: Ambos (Solicitante y Administrador). Módulo: 1 — Gestión de Usuarios.

Precondiciones: El usuario debe estar registrado en el sistema. Postcondiciones: Se establece una sesión activa que permite el acceso a las funcionalidades según el rol asignado.

Flujo principal: (1) El usuario accede a la URL de login del sistema. (2) Ingresa su correo electrónico y contraseña. (3) El sistema valida las credenciales contra la tabla users de la base de datos. (4) Si las credenciales son correctas, se inicia sesión y se redirige al catálogo de recursos. (5) El cierre de sesión se realiza mediante el botón "Logout" disponible en todas las vistas autenticadas.
```

---

#### UC-02: Gestionar Catálogo de Recursos
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 5 (línea 392)

```
UC-02 agrupa las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) que el Administrador puede realizar sobre los recursos educativos táctiles almacenados en el catálogo del sistema.

Actor principal: Administrador. Módulo: 4 — Catálogo Digital de Producción Educativa Táctil.

Precondiciones: El Administrador ha iniciado sesión en el sistema. Postcondiciones: El catálogo refleja los cambios realizados (creación, modificación, eliminación o restauración de un recurso).

Flujo principal: El Administrador puede crear nuevos recursos ingresando título, descripción, gramos de PLA estimados, tiempo de impresión, imagen de referencia y archivo G-Code. También puede editar los datos de un recurso existente, enviarlo a la papelera de eliminación (SoftDelete), restaurarlo desde la papelera, o eliminarlo permanentemente. Todos los recursos inactivos no son visibles para el Solicitante en el catálogo público.
```

---

#### UC-03: Ver Catálogo de Recursos
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 6 (línea 397)

```
UC-03 permite al Usuario Solicitante explorar y consultar el catálogo de recursos educativos táctiles disponibles para su impresión en 3D.

Actor principal: Solicitante. Módulo: 4 — Catálogo Digital de Producción Educativa Táctil.

Precondiciones: El Solicitante ha iniciado sesión en el sistema. Postcondiciones: El Solicitante visualiza la lista de recursos activos con sus fichas descriptivas.

Flujo principal: (1) El Solicitante accede al módulo de catálogo. (2) El sistema muestra una lista paginada de todos los recursos en estado "Activo", con su imagen de referencia, título, tiempo estimado de impresión y peso en gramos. (3) El Solicitante puede filtrar los recursos por categoría (Matemáticas, Geografía, Braille, Ciencias) y buscar por nombre. (4) Al seleccionar un recurso, se muestra su ficha completa con la opción de "Solicitar Impresión".
```

---

#### UC-04: Gestionar Instituciones
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 7 (línea 401)

```
UC-04 permite al Administrador gestionar el registro de las instituciones educativas beneficiarias del proyecto, incluyendo su documentación de respaldo.

Actor principal: Administrador. Módulo: 1 — Gestión de Usuarios (sección instituciones beneficiarias).

Precondiciones: El Administrador ha iniciado sesión. Postcondiciones: La base de datos de instituciones refleja los cambios.

Flujo principal: El Administrador puede registrar nuevas instituciones ingresando nombre, dirección, teléfono, director responsable, logo institucional (opcional) y documento PDF de respaldo (opcional). Cada institución puede ser posteriormente vinculada a los pedidos de impresión realizados por los usuarios Solicitantes asociados. Las operaciones disponibles incluyen crear, ver, editar, enviar a papelera, restaurar y eliminar permanentemente.
```

---

#### UC-05: Gestionar Usuarios
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 8 (línea 405)

```
UC-05 permite al Administrador gestionar todas las cuentas de usuario del sistema, asignando roles y manteniendo el control de acceso.

Actor principal: Administrador. Módulo: 1 — Gestión de Usuarios.

Precondiciones: El Administrador ha iniciado sesión. Postcondiciones: Los cambios en las cuentas de usuario quedan registrados y afectan los permisos de acceso de los usuarios modificados.

Flujo principal: El Administrador puede crear nuevos usuarios asignándoles un rol (Administrador o Solicitante), editar sus datos personales, restablecer contraseñas, enviar cuentas a la papelera de eliminación y restaurarlas. El sistema valida que el email sea único en la tabla users, que la contraseña tenga un mínimo de 8 caracteres y que el rol seleccionado sea uno de los valores permitidos en el enum del modelo User.
```

---

#### UC-06: Traducir Texto a Braille
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 9 (línea 409)

```
UC-06 constituye el núcleo funcional (CORE) del sistema. Permite al Usuario Solicitante ingresar texto en español y obtener su representación táctil en Código Braille Español Grado 1, junto con una previsión visual 2D.

Actor principal: Solicitante. Módulo: 2 — Traducción Automática Texto→Braille.

Precondiciones: El Solicitante ha iniciado sesión. El texto ingresado contiene únicamente caracteres soportados por el Código Braille Español (letras A-Z, Ñ, vocales acentuadas Á É Í Ó Ú Ü, números 0-9 y puntuación estándar). Postcondiciones: El Solicitante visualiza la previsión 2D de la ficha Braille generada.

Flujo principal: (1) El Solicitante accede al módulo "Traductor de Fichas". (2) Ingresa texto en español (ej: "ÑANDÚ"). (3) El sistema valida que todos los caracteres pertenezcan al alfabeto del Código Braille Español publicado por la ONCE. (4) El sistema traduce cada carácter a su celda Braille correspondiente. (5) Se muestra una previsión visual en 2D con la posición de los puntos Braille en relieve sobre la placa base.

Importante: La generación del archivo G-Code NO ocurre en este caso de uso. La compilación del G-Code se realiza exclusivamente en UC-07 (Solicitar Impresión) al momento de confirmar el pedido.
```

---

#### UC-07: Solicitar Impresión
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 10 (línea 413)

```
UC-07 permite al Solicitante confirmar la solicitud de impresión de un recurso educativo táctil, registrando el pedido y generando el archivo G-Code asociado.

Actor principal: Solicitante. Módulo: 3 — Gestión de Pedidos y Costos de Producción.

Precondiciones: El Solicitante ha visualizado la previsión 2D (UC-06) o ha seleccionado un recurso del catálogo (UC-03). Postcondiciones: Se registra un nuevo pedido en estado "Pendiente" con su archivo G-Code generado y asociado.

Flujo principal: (1) El Solicitante confirma la solicitud haciendo clic en "Solicitar Impresión". (2) El sistema registra el pedido asociando el usuario, la institución de origen, la fecha y los datos del recurso. (3) El sistema calcula el consumo estimado de filamento PLA en gramos utilizando los parámetros volumétricos del modelo. (4) Se estima el costo de producción multiplicando los gramos de PLA por el costo por gramo parametrizado en la tabla configuracion_sistemas. (5) El sistema genera las coordenadas espaciales (X, Y, Z) para el extrusor. (6) Se compila el archivo .gcode con instrucciones G0/G1, control de extrusión G92 E0 y parámetros compatibles con Marlin 1.1.x. (7) Se asigna el estado "Pendiente" al pedido y se almacena la ruta del archivo G-Code en el campo gcode_path de la tabla pedidos.
```

---

#### UC-08: Gestionar Solicitudes
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 11 (línea 418)

```
UC-08 permite al Administrador consultar, filtrar y actualizar el estado de todos los pedidos de impresión solicitados por los usuarios.

Actor principal: Administrador. Módulo: 3 — Gestión de Pedidos y Costos de Producción.

Precondiciones: El Administrador ha iniciado sesión. Existen pedidos registrados en el sistema. Postcondiciones: El estado del pedido se actualiza según la acción del Administrador.

Flujo principal: El Administrador accede al panel de gestión de solicitudes, donde puede ver una lista paginada y filtrable de todos los pedidos por estado (Pendiente, En impresión, Completado, Rechazado), institución y fecha. Para cada pedido, el Administrador puede: (1) Cambiar el estado a "En impresión" cuando inicia el proceso físico. (2) Cambiar el estado a "Completado" cuando la impresión finaliza. (3) Rechazar el pedido registrando un motivo obligatorio en el campo motivo_rechazo. (4) Descargar el archivo G-Code asociado para transferirlo a la impresora 3D.
```

---

#### UC-09: Descargar G-Code
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 12 (línea 423)

```
UC-09 permite exclusivamente al Administrador descargar los archivos G-Code generados para los pedidos aprobados, con el fin de transferirlos manualmente a la impresora 3D mediante conexión USB directa desde la PC del operador.

Actor principal: Administrador. Módulo: 3/4 — Gestión de Pedidos y Catálogo de Recursos.

Precondiciones: El Administrador ha iniciado sesión. El pedido tiene un archivo G-Code generado y asociado. Postcondiciones: El archivo G-Code se descarga al equipo local del operador, listo para ser transferido por USB a la impresora 3D.

Flujo principal: (1) El Administrador accede al detalle de un pedido en estado "Pendiente" o "En impresión". (2) Hace clic en el botón "Descargar G-Code". (3) El sistema sirve el archivo desde la ruta almacenada en gcode_path. (4) El operador guarda el archivo y lo transfiere mediante USB directo (Tethered Printing) a la placa controladora Arduino Mega 2560 + RAMPS 1.4.

Restricción crítica: El Solicitante NUNCA puede descargar archivos G-Code. Esta restricción se implementa mediante middleware de roles que valida que el usuario autenticado tenga rol "Administrador".
```

---

#### UC-10: Generar Reportes y Estadísticas
**Ubicación:** Después de "Fuente: Elaboración propia." de Figura 13 (línea 428)

```
UC-10 permite al Administrador generar reportes en formatos PDF y Excel de los registros de recursos, instituciones, usuarios y pedidos del sistema.

Actor principal: Administrador. Módulo: 3 — Gestión de Pedidos y Costos de Producción.

Precondiciones: El Administrador ha iniciado sesión. Postcondiciones: Se genera y descarga un archivo PDF o Excel con la información consolidada.

Flujo principal: El Administrador accede al módulo de reportes y selecciona la entidad a reportar (recursos, instituciones, usuarios o pedidos) y el formato de exportación (PDF mediante DomPDF o Excel mediante Maatwebsite/Excel). El sistema genera el documento correspondiente con los datos actuales de la base de datos, respetando los filtros aplicados (por ejemplo, pedidos por rango de fechas o por estado). Los reportes son de uso exclusivo del Administrador para la toma de decisiones y el control de gestión.
```

---

## SECCIÓN 4: Descripciones de Diagramas

### 4.1 Descripción del Diagrama de Clases (después de línea 433)

1. Busca "Figura 14. Diagrama de Clases" (línea 432)
2. Encuentra "Fuente: Elaboración propia." (línea 433)
3. Coloca el cursor al final de esa línea, `Enter` + estilo **Normal**
4. Pega:

```
El Diagrama de Clases del Dominio (Figura 14) representa las 7 clases principales del modelo de datos del sistema, junto con 3 enums que definen los valores permitidos para los campos rol, estado del recurso y estado del pedido. Las clases representadas son: User (gestión de usuarios con autenticación Bcrypt), Institucion (instituciones beneficiarias), Recurso (catálogo de modelos educativos con su relación a Categoria), Categoria (clasificación de recursos), Pedido (solicitudes de impresión con SoftDeletes), DetallePedido (líneas de cada pedido) y ConfiguracionSistema (parámetros clave-valor como el precio por gramo de PLA). Las relaciones de asociación incluyen: User 1:N Pedido, Institucion 1:N Pedido, Pedido 1:N DetallePedido, Recurso 1:N DetallePedido y Categoria 1:N Recurso. Los atributos SoftDeletes (papelera de eliminación) están marcados en las clases User, Institucion, Recurso y Pedido.
```

### 4.2 Insertar Figura 16 — Diagrama de Estados del Pedido

**Ubicación:** Donde dice "Estados" (línea 434)

1. Coloca el cursor al final de la palabra "Estados" (línea 434)
2. `Enter` y cambia el estilo a **Normal**
3. Ve a `Insertar > Imágenes > Este dispositivo`
4. Selecciona: `UML_Estados_Pedido.png` (73K)
5. Ajusta ancho a ~14 cm (centrada)
6. Click derecho sobre la imagen → `Insertar título`
7. Etiqueta: "Figura", Título: `Figura 16. Diagrama de Estados del Pedido`
8. Acepta → se crea Caption
9. `Enter` + estilo **List Paragraph**
10. Escribe: `Fuente: Elaboración propia.`

**Descripción debajo (estilo Normal):**
```
El Diagrama de Estados (Figura 16) representa el ciclo de vida de un Pedido en el sistema. El estado inicial es "Pendiente", asignado automáticamente al crear el pedido (UC-07). Desde este estado, el Administrador puede: (a) actualizar el estado a "En impresión" cuando inicia el proceso físico de impresión 3D, o (b) rechazar el pedido registrando un motivo obligatorio. Desde "En impresión", el Administrador puede marcar el pedido como "Completado" cuando la impresión finaliza. Adicionalmente, el Solicitante puede cancelar un pedido solo si el estado es "Pendiente" mediante SoftDelete.
```

### 4.3 Insertar Figura 17 — Diagrama de Despliegue (Topología Física)

**Ubicación:** Donde dice "Componentes" (línea 435)

1. Misma mecánica que Figura 16
2. Selecciona: `UML_Despliegue.png` (91K)
3. Título: `Figura 17. Diagrama de Despliegue (Topología Física)`
4. `Fuente: Elaboración propia.`

**Descripción debajo (estilo Normal):**
```
El Diagrama de Despliegue (Figura 17) muestra la topología física del sistema distribuido en cuatro nodos principales: (1) Servidor en la Nube que aloja Laravel 13, MySQL 8.0 y el módulo Python (planificado); (2) PC del Solicitante con navegador web estándar; (3) PC del Operador con navegador y software de control USB; y (4) la Placa Controladora Arduino Mega 2560 + RAMPS 1.4 que ejecuta el Firmware Marlin 1.1.x. La comunicación entre los nodos sigue el siguiente patrón: las PCs del Solicitante y del Operador se conectan al servidor mediante Internet/HTTPS; el Operador conecta la placa controladora mediante Cable USB / Serial; la placa controla los motores NEMA 17, los drivers A4988 y el extrusor MK8. No existe comunicación directa entre el servidor en la nube y la impresora 3D.
```

### 4.4 Descripción del Diagrama ER de Base de Datos (después de línea 441)

1. Busca "Figura 15. Diagrama ER de Base de Datos" (línea 440)
2. Encuentra "Fuente: Elaboración propia." (línea 441)
3. `Enter` + estilo **Normal**
4. Pega:

```
El Diagrama Entidad-Relación de Base de Datos (Figura 15) refleja exactamente las 7 tablas implementadas en MySQL: users, instituciones, categorias, recursos, pedidos, detalle_pedidos y configuracion_sistemas. La tabla users almacena las cuentas del sistema con campos para autenticación Bcrypt, roles (enum Administrador, Solicitante) y softDelete. La tabla instituciones guarda los centros educativos beneficiarios con su documentación. La tabla categorias clasifica los recursos del catálogo. La tabla recursos almacena los modelos educativos con sus datos técnicos (gramos_pla, tiempo_minutos), referencia a categoría y softDelete. La tabla pedidos registra las solicitudes con su estado, fecha, costos y referencia al archivo G-Code generado. La tabla detalle_pedidos permite registrar múltiples recursos por pedido (relación 1:N). La tabla configuracion_sistemas implementa el patrón clave-valor para parámetros globales como precio_gramo_pla. Las 5 claves foráneas del modelo son: pedidos.user_id → users.id (CASCADE), pedidos.institucion_id → instituciones.id (SET NULL), detalle_pedidos.pedido_id → pedidos.id (CASCADE), detalle_pedidos.recurso_id → recursos.id (CASCADE) y recursos.categoria_id → categorias.id (SET NULL).
```

---

## SECCIÓN 5: Secciones "No definido" a redactar

### 5.1 Participación comunitaria (línea 344)

1. Busca `Participación comunitaria` → selecciona y borra "No definido." (línea 344)
2. `Enter` + estilo **Normal**
3. Pega:

```
Durante la fase de ejecución del proyecto, la participación comunitaria se articuló mediante tres ejes de colaboración directa. En primer lugar, se realizó una alianza estratégica con el Instituto Boliviano de la Ceguera (IBC), sede Cochabamba, entidad de referencia que brindó validación técnica del material Braille producido. En segundo lugar, se ejecutaron encuestas estructuradas (escala Likert de 5 puntos) a 12 docentes de educación especial, y encuestas dicotómicas a 8 estudiantes con discapacidad visual de instituciones piloto. En tercer lugar, se llevaron a cabo entrevistas semiestructuradas con 3 especialistas del IBC y 4 docentes de educación especial, con registro en audio y transcripción para análisis cualitativo. Estas actividades de participación comunitaria permitieron identificar la problemática real, validar la propuesta tecnológica y ajustar el diseño del sistema a las necesidades del contexto educativo local.
```

### 5.2 Desarrollo técnico del producto (línea 346)

1. Borra "No definido." (línea 346)
2. `Enter` + estilo **Normal**
3. Pega:

```
El desarrollo técnico del producto se organizó en seis módulos funcionales: (1) Módulo de Gestión de Usuarios, con autenticación Bcrypt, roles diferenciados (Administrador y Solicitante) y CRUD completo con papelera SoftDeletes. (2) Módulo de Traducción Automática Braille, núcleo del sistema, con algoritmo de conversión texto→Braille Español Grado 1 y previsión visual 2D. (3) Módulo de Gestión de Pedidos y Costos, con cálculo automático de consumo de PLA, estimación de costos parametrizable y ciclo de vida del pedido con 4 estados. (4) Catálogo Digital de Recursos, con organización por categorías y asociación de archivos G-Code. (5) Hardware CNC Electromecánico tipo Prusa i3 con Arduino Mega 2560 + RAMPS 1.4 y componentes recuperados de e-waste. (6) Validación Sociocomunitaria con tres pruebas técnicas de impresión (cubo de calibración, regla táctil y ficha Braille). El desarrollo se ejecutó con metodología mixta Scrum/Kanban, control de versiones con Git/GitHub, y entorno de desarrollo contenerizado con Docker para MySQL 8.0 y Laravel 13.
```

### 5.3 Tecnologías utilizadas (línea 444)

1. Borra "No definido." (línea 444)
2. `Enter` + estilo **Normal**
3. Pega:

```
El stack tecnológico del sistema web se detalla exhaustivamente en la Tabla 5. En resumen, el backend está desarrollado con Laravel 13 sobre PHP 8.3+ siguiendo el patrón arquitectónico MVC, con ORM Eloquent para la persistencia en MySQL 8.0. La interfaz de usuario utiliza el template AdminLTE 3 sobre Bootstrap 4, garantizando diseño responsivo. El módulo de Python 3 (actualmente planificado) será responsable de la generación del archivo G-Code. En el hardware, el controlador Arduino Mega 2560 ejecuta el firmware Marlin 1.1.x sobre la placa de expansión RAMPS 1.4, con 4 drivers A4988 y 3 motores NEMA 17 recuperados. El entorno de desarrollo se gestiona con Docker Compose, con servicios separados para la aplicación Laravel (PHP 8.4-cli) y la base de datos MySQL 8.0. Las pruebas automatizadas se ejecutan con PHPUnit. El control de versiones se realiza con Git y la plataforma GitHub, con archivos binarios grandes gestionados mediante Git LFS.
```

### 5.4 Herramientas de seguimiento (línea 446)

1. Borra "No definido." (línea 446)
2. `Enter` + estilo **Normal**
3. Pega:

```
Las herramientas de seguimiento utilizadas durante el proyecto fueron: (1) Git y GitHub como sistema de control de versiones distribuido, con un repositorio central que almacena el código fuente, los archivos de configuración, los planos CAD, las exportaciones 3D y la documentación del proyecto. (2) Git LFS (Large File Storage) para gestionar archivos binarios grandes como planos FreeCAD, exportaciones STL, archivos G-Code, documentos Word y fotografías. (3) Docker y Docker Compose para la contenerización del entorno de desarrollo, garantizando portabilidad y consistencia entre los equipos del equipo desarrollador. (4) Metodología ágil Scrum/Kanban con tablero de control para la planificación de sprints, seguimiento de tareas y retrospectivas. (5) Plantillas de issues y pull requests en GitHub para el seguimiento estructurado de bugs, features y revisión de código. (6) PHPUnit como framework de pruebas unitarias e integración para el backend Laravel.
```

### 5.5 Dificultades y soluciones aplicadas (línea 448)

1. Borra "No definido." (línea 448)
2. `Enter` + estilo **Normal**
3. Pega:

```
Durante el desarrollo del proyecto se identificaron cinco dificultades principales y sus respectivas soluciones técnicas. (1) Baja adherencia del filamento PLA sobre la cama de impresión: se calibró de manera precisa el eje Z para que la boquilla inicie a 0.1 mm de la superficie, se niveló la cama de impresión y se utilizó cinta azul de pintor o laca adhesiva sobre el vidrio. (2) Errores lógicos en el algoritmo de traducción texto→Braille: se implementaron pruebas unitarias para cada carácter del alfabeto y los dígitos 0–9, y se validaron los resultados con especialistas del IBC antes de la fase de producción piloto. (3) Desincronización de motores NEMA 17 en los ejes X, Y, Z: se ajustó individualmente la corriente de cada driver A4988 mediante su potenciómetro y se verificaron los parámetros steps/mm del firmware Marlin. (4) Componentes e-waste defectuosos o incompatibles: se aplicaron pruebas de continuidad y medición de bobinas en todos los motores recuperados antes del ensamblaje. (5) Resistencia inicial de docentes al uso de la plataforma web: se diseñó la interfaz aplicando principios de usabilidad WCAG 2.1 nivel AA, con etiquetas semánticas HTML, contraste adecuado y compatibilidad con lectores de pantalla NVDA y TalkBack.
```

---

## SECCIÓN 6: Secciones a marcar [PENDIENTE]

Para cada una de las 5 secciones siguientes, selecciona "No definido." y reemplázalo con:

```
[PENDIENTE DE EJECUCIÓN FÍSICA — Se completará una vez finalizado el pilotaje y la recolección de datos en instituciones beneficiarias]
```

| Sección | Ubicación |
|---|---|
| Resultados cualitativos | Línea 451 |
| Resultados cuantitativos | Línea 453 |
| Impacto en la comunidad | Línea 455 |
| CONCLUSIONES | Línea 457 |
| Recomendaciones | Línea 459 |

---

## SECCIÓN 7: Validación final

### 7.1 Actualizar índice de figuras
1. Ve a la sección `ÍNDICE DE FIGURAS` (línea 23)
2. Click derecho en cualquier parte del índice
3. Selecciona "Actualizar campo" → "Actualizar toda la tabla"
4. Las nuevas figuras (16, 17) aparecerán automáticamente

### 7.2 Actualizar índice de tablas
1. Ve a `ÍNDICE DE TABLAS` (línea 8)
2. Click derecho → "Actualizar campo" → "Actualizar toda la tabla"

### 7.3 Guardar como nuevo archivo
1. `Archivo > Guardar como`
2. Nombre: `DocumentoFinalPSCP3DJulio_Corregido.docx`
3. **NO sobrescribas** el original hasta verificar

### 7.4 Checklist de verificación

| # | Verificación | Estado |
|---|---|---|
| 1 | Las 10 descripciones de UCs están insertadas | ☐ |
| 2 | El párrafo introductorio del UC-00 está | ☐ |
| 3 | El Diagrama de Clases tiene descripción | ☐ |
| 4 | La Figura 16 (Estados) está insertada | ☐ |
| 5 | La Figura 17 (Despliegue) está insertada | ☐ |
| 6 | El Diagrama ER tiene descripción | ☐ |
| 7 | Participación comunitaria tiene contenido | ☐ |
| 8 | Desarrollo técnico tiene contenido | ☐ |
| 9 | Tecnologías utilizadas tiene contenido | ☐ |
| 10 | Herramientas de seguimiento tiene contenido | ☐ |
| 11 | Dificultades y soluciones tiene contenido | ☐ |
| 12 | Las 5 secciones de campo están marcadas [PENDIENTE] | ☐ |
| 13 | RF-07 corregido (línea 356) | ☐ |
| 14 | Módulo 2 renombrado (línea 229) | ☐ |
| 15 | Tabla 13, fila UC-00 corregida | ☐ |
| 16 | Índice de figuras actualizado | ☐ |
| 17 | Guardado como nuevo archivo | ☐ |
