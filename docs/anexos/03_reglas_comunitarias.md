# 03 — Reglas del Sistema y Normativas

## 1. Reglas de Acceso y Autenticación

| Regla | Descripción |
|---|---|
| RA-01 | Todo acceso requiere autenticación previa (email + contraseña) |
| RA-02 | Las contraseñas se almacenan encriptadas con Bcrypt |
| RA-03 | El Solicitante NO puede descargar archivos G-Code |
| RA-04 | Solo el Administrador puede gestionar cuentas de usuario |
| RA-05 | El Solicitante solo puede cancelar solicitudes en estado "Pendiente" |

---

## 2. Reglas de Validación de Datos

### Recursos Educativos
| Campo | Regla |
|---|---|
| Título | Obligatorio, 5-150 caracteres |
| Descripción | Obligatorio, mínimo 10 caracteres |
| Gramos PLA | Obligatorio, numérico, mínimo 0.1 g |
| Tiempo impresión | Obligatorio, entero, mínimo 1 minuto |
| Imagen | Opcional, máx 2 MB, formatos: jpg, png |
| Archivo G-Code | Opcional, formatos: .gcode, .txt |
| Estado | Obligatorio: Activo / Inactivo |

### Instituciones
| Campo | Regla |
|---|---|
| Nombre | Obligatorio, 3-100 caracteres |
| Dirección | Obligatorio, 5-200 caracteres |
| Teléfono | Obligatorio, máx 30 caracteres, formato telefónico |
| Director | Opcional, máx 100 caracteres |
| Logo | Opcional, máx 2 MB, formato imagen |
| Documento PDF | Opcional, máx 4 MB |

### Usuarios
| Campo | Regla |
|---|---|
| Nombre | Obligatorio, máx 100 caracteres |
| Email | Obligatorio, formato válido, único en el sistema |
| Contraseña | Obligatorio al crear, mínimo 8 caracteres, confirmación requerida |
| Rol | Obligatorio: Administrador / Solicitante |
| Foto perfil | Opcional, máx 2 MB, formato imagen |

---

## 3. Reglas de Traducción Braille

| Regla | Descripción |
|---|---|
| RB-01 | Solo se soporta Braille Grado 1 (alfabeto alfabético, sin estenografía) |
| RB-02 | Caracteres soportados: letras A-Z, números 0-9, signos de puntuación básicos |
| RB-03 | No se soportan caracteres acentuados complejos ni símbolos especiales |
| RB-04 | El texto tiene longitud máxima limitada por el tamaño de la ficha |
| RB-05 | La traducción se realiza en el backend (Service PHP: App\Services\BrailleTranslator; python_core archivado) |
| RB-06 | Se muestra previsión visual 2D antes de confirmar el pedido |

---

## 4. Reglas de Solicitudes y Pedidos

| Regla | Descripción |
|---|---|
| RS-01 | Todo pedido inicia en estado "Pendiente" |
| RS-02 | Ciclo de estados: Pendiente → Aprobado → En impresión → Completado (o Rechazado en cualquier fase previa) |
| RS-03 | El Administrador puede rechazar un pedido (con motivo) |
| RS-04 | El Solicitante solo puede cancelar si el estado es "Pendiente" |
| RS-05 | El G-Code se genera automáticamente al confirmar la solicitud |
| RS-06 | El consumo de PLA se calcula automáticamente en el backend |
| RS-07 | El costo de producción = gramos PLA × costo por gramo (parametrizable) |
| RS-08 | El costo por gramo de PLA es configurable por el Administrador |

---

## 5. Reglas de Gestión de Datos

| Regla | Descripción |
|---|---|
| RG-01 | Todas las entidades principales usan SoftDeletes (papelera) |
| RG-02 | Los archivos eliminados se borran del almacenamiento físico |
| RG-03 | Las exportaciones PDF usan DomPDF |
| RG-04 | Las exportaciones Excel usan Maatwebsite/Excel |
| RG-05 | Los reportes están disponibles solo para el Administrador |

---

## 6. Normativas y Marco Legal

| Normativa | Aplicación |
|---|---|
| Ley N° 223 de Educación (Bolivia) | Marco legal para educación inclusiva |
| Decreto Supremo N° 1893 | Reglamentación de atención a personas con discapacidad |
| Constitución Política del Estado (CPE) | Derecho a la educación inclusiva (Art. 17, 61, 112) |
| Estándares UEB | Unified English Braille — estándar internacional de codificación |
| WCAG 2.1 | Pautas básicas de accesibilidad web (no certificada) |

---

## 7. Límites del Sistema (Exclusiones)

Conforme a los límites definidos en la tesis:

- NO hay control de inventarios de PLA ni alertas de stock.
- NO hay pasarelas de pago en línea.
- NO hay aplicación móvil nativa (solo web responsiva).
- NO hay comunicación directa servidor-impresora (flujo: servidor → PC Operador → USB → impresora).
- NO hay modelado CAD dentro de la plataforma.
- NO hay funciones médicas ni diagnósticas.
- NO se sustituye impresoras Braille industriales.
