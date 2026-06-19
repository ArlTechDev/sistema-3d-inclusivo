# 01 — Contexto Socio-Comunitario

## 1. Definición del Problema Social

### Problema Central
Limitado acceso a recursos educativos tridimensionales táctiles duraderos para estudiantes con discapacidad visual en instituciones de educación especial del municipio de Cochabamba.

### Causas Raíces
- Alto costo de impresoras 3D y equipos de relieve comercial (superan $3,000 USD).
- Rápido deterioro del material táctil tradicional (papel punzado, cartón).
- Carencia de plataformas locales que integren traducción automatizada a Braille con catálogo de modelos 3D.
- Desaprovechamiento de basura tecnológica (e-waste) con componentes reutilizables.

### Consecuencias
- Dificultad para comprender conceptos espaciales (Geografía, Geometría, Matemáticas).
- Exclusión educativa y dependencia de terceros para adaptación manual de materiales.
- Pérdida constante de material didáctico por desgaste.
- Menor autonomía del estudiante con discapacidad visual respecto a sus pares videntes.

### Interrogante de Investigación
¿En qué medida el desarrollo de un sistema web y electromecánico de impresión 3D con materiales reciclados optimiza la producción, reduce los costos y mejora el acceso a recursos educativos táctiles para personas con discapacidad visual?

---

## 2. Objetivo Comunitario

**Objetivo General:** Desarrollar un sistema web y electromecánico de impresión 3D con materiales reciclados para la producción automatizada de recursos educativos táctiles en Braille.

### Beneficios para la Comunidad

| Beneficiario | Beneficio esperado |
|---|---|
| Estudiantes con discapacidad visual | Acceso a mapas táctiles, figuras geométricas, reglas y textos en Braille impresos en PLA duradero |
| Docentes de educación especial | Plataforma web para traducir texto a Braille sin conocimientos técnicos |
| Instituciones educativas | Diversificación de material didáctico sin depender de donaciones ni presupuestos altos |
| Medio ambiente | Reutilización de e-waste (motores, varillas, fuentes de poder) en modelo de economía circular |

---

## 3. Mapeo de Actores

### Beneficiarios Indirectos (NO actores del sistema)
- Estudiantes con discapacidad visual (ceguera y baja visión) — ~200 en Cochabamba.
- Familias de los estudiantes.
- Comunidad educativa en general.

### Usuarios del Sistema (Actores)

| Actor | Rol en el sistema | Perfil digital | Interacciones principales |
|---|---|---|---|
| **Usuario Solicitante** (Docente, Directivo, Tutor) | Acceso limitado | Bajo-medio (navegador web, posible uso de lector de pantalla NVDA/TalkBack) | Ver catálogo, traducir texto a Braille, solicitar impresión, ver estado de pedidos |
| **Administrador/Operador** (Equipo de desarrollo) | Control total | Alto (gestión de plataforma, descarga de archivos, manejo de impresora 3D) | Gestionar usuarios, catálogo, instituciones, solicitudes, descargar G-Code, generar reportes |

### Aliados Estratégicos
- Instituto Boliviano de la Ceguera (IBC) — sede Cochabamba: Validación técnica del material Braille.
- Instituciones de educación especial: Pilotaje y pruebas con usuarios reales.

---

## 4. Restricciones Tecnológicas del Entorno

| Aspecto | Restricción | Impacto en los Casos de Uso |
|---|---|---|
| Conectividad | Online 100% — servidor en la nube | Precondición: conexión activa a internet en todos los UC |
| Dispositivos del Solicitante | PC gama baja o celular con navegador | UI responsiva, diseño simple, compatibilidad con lectores de pantalla |
| Dispositivos del Operador | PC + impresora 3D Prusa i3 | Descarga de G-Code, transferencia a impresora vía USB |
| Comunicación hardware | USB directo (PC→Impresora), sin IoT ni conexión a internet en impresora | UC-09: descarga exclusiva del Admin, no transferencia automática |
| Límites del sistema | Sin pagos en línea, sin app móvil, sin inventario PLA, sin modelado CAD | UC limitados a funcionalidades definidas en la tesis |

---

## 5. Fuentes de Información

### Fuentes Primarias (Técnicas Participativas)
- Observación participante en centros de educación especial de Cochabamba.
- Entrevistas semiestructuradas: 3 especialistas IBC + 4 docentes de educación especial.
- Encuestas estructuradas: 12 docentes (Likert 5 puntos) + 8 estudiantes (dicotómica).

### Fuentes Secundarias (Documentos)
- Ley N° 223 de Educación (Bolivia).
- Decreto Supremo N° 1893.
- Estándares UEB (Unified English Braille).
- Documentación técnica: Marlin 1.1.x, Laravel 13, Arduino Mega 2560.
