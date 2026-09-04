# 02 — Arquitectura y Viabilidad Técnica

## Pilar 1: Infraestructura del Sistema

| Componente | Especificación |
|---|---|
| Tipo de aplicación | Plataforma Web Responsiva (Cliente-Servidor) |
| Framework backend | Laravel 13 (PHP 8.3+) |
| Base de datos | MySQL (alojada en servidor en la nube) |
| UI Theme | AdminLTE 3 (Bootstrap 4) |
| Alojamiento | Servidor en la nube (Hostinger o similar) |
| Acceso | Navegador web estándar (Chrome, Firefox) |

---

## Pilar 2: Entorno de Ejecución

### Para el Usuario Solicitante (Docente/Directivo)
| Aspecto | Especificación |
|---|---|
| Hardware | Computadora de escritorio gama baja o dispositivo móvil |
| Software | Navegador web actualizado |
| Accesibilidad | Compatibilidad con lectores de pantalla (NVDA en Windows, TalkBack en Android) |
| Conexión | Internet activa requerida |

### Para el Administrador/Operador (Equipo de desarrollo)
| Aspecto | Especificación |
|---|---|
| Hardware de control | Computadora de escritorio |
| Impresora 3D | Arquitectura cartesiana modular tipo Prusa i3 |
| Controlador | Arduino Mega 2560 + RAMPS 1.4 |
| Drivers | 4× A4988 |
| Motores | 4× NEMA 17 (recuperados de e-waste: ejes X, Y, Z + extrusor MK8) |
| Tracción | Correa GT2 + poleas (X/Y), varillas roscadas (Z) |
| Extrusor | MK8 directo, boquilla 0.4 mm o 0.8 mm |
| Material | Filamento PLA 1.75 mm biodegradable |
| Cama | Superficie fría (sin cama caliente) |
| Firmware | Marlin 1.1.x configurado y calibrado |

---

## Pilar 3: Seguridad y Control de Acceso

### Autenticación
- Sistema: Laravel Auth con encriptación Bcrypt.
- Credenciales: Correo electrónico + contraseña.
- Sesiones: Tabla `sessions` en base de datos.

### Matriz de Roles y Permisos

| Funcionalidad | Solicitante | Administrador |
|---|---|---|
| Ver catálogo de recursos | ✓ | ✓ |
| Traducir texto a Braille | ✓ | ✓ |
| Ver previsión 2D | ✓ | ✓ |
| Solicitar impresión | ✓ | ✓ |
| Ver mis solicitudes | ✓ | ✓ |
| Cancelar solicitud (Pendiente) | ✓ | ✓ |
| Gestionar catálogo (CRUD) | ✗ | ✓ |
| Gestionar instituciones (CRUD) | ✗ | ✓ |
| Gestionar usuarios (CRUD) | ✗ | ✓ |
| Ver todas las solicitudes | ✗ | ✓ |
| Actualizar estado de pedidos | ✗ | ✓ |
| Parametrizar costo PLA | ✗ | ✓ |
| Descargar G-Code | ✗ | ✓ |
| Generar reportes PDF/Excel | ✗ | ✓ |
| Gestionar papelera | ✗ | ✓ |

---

## Pilar 4: Flujo de Comunicación (Web-to-Print)

```
┌─────────────────────────────────────────────────────────────────┐
│                    FLUJO DE COMUNICACIÓN                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  [Solicitante]                                                  │
│       │                                                         │
│       ▼                                                         │
│  [Plataforma Web] ──► [Backend Laravel (Service PHP)]           │
│       │                      │                                  │
│       │                      ▼                                  │
│       │              Traducción Braille                         │
│       │              Generación G-Code                          │
│       │                      │                                  │
│       ▼                      ▼                                  │
│  [Base de Datos MySQL] ◄── G-Code almacenado                   │
│       │                                                         │
│       ▼                                                         │
│  [Admin descarga G-Code]                                        │
│       │                                                         │
│       ▼                                                         │
│  [Transferencia: PC Operador → Impresora (USB)]                 │
│       │                                                         │
│       ▼                                                         │
│  [Impresora 3D Prusa i3] ──► [Ficha táctil en PLA]             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Límites de comunicación
- NO existe conexión directa entre servidor e impresora.
- NO hay APIs externas de pago ni telemetría remota.
- El operador actúa como intermediario físico de datos.

---

## Pilar 5: Respaldo de Datos

| Aspecto | Mecanismo |
|---|---|
| Tipo | Exportación manual de base de datos MySQL |
| Responsable | Administrador del sistema |
| Destino | Dispositivos de almacenamiento local |
| Frecuencia | Periódica (según volumen de datos) |

---

## Ficha de Contexto Técnico (Resumen para documentación)

| Campo | Valor |
|---|---|
| Arquitectura | Plataforma Web Responsiva (Cliente-Servidor MVC) |
| Stack | Laravel 13, PHP 8.3+, MySQL, AdminLTE 3 (traductor Braille→G-Code en PHP puro; `python_core` archivado) |
| Conectividad | Online 100% (backend en la nube) |
| Metodología | Scrum (sprints de 2 semanas) + Kanban/Trello, enfoque mixto sociocomunitario productivo |
| Hardware de control | Arduino Mega 2560 + RAMPS 1.4 + Marlin 1.1.x |
| Matriz de seguridad | Auth Bcrypt, roles Solicitante/Administrador |
| Flujo de datos | Web → G-Code en servidor → descarga a PC → impresora vía USB |
