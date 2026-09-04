# Diseño de Seguridad de la Información para el Proyecto

## "Sistema Web y Electromecánico de Impresión 3D con Materiales Reciclados para la Producción de Recursos Educativos Táctiles en Braille"

**Instituto Técnico "Federico Alvarez Plata"**
**Proyecto Sociocomunitario Productivo — PSCP**
**Equipo de Desarrollo:** Rosales Mamani Ariel Edson, Aguilar Castellon Cristhian Alessandro, Aramayo Eguino Jose Matias
**Fecha:** Julio 2026

---

## 1. Descripción del proyecto

El proyecto titulado **"Sistema Web y Electromecánico de Impresión 3D con Materiales Reciclados para la Producción de Recursos Educativos Táctiles en Braille"** es una plataforma tecnológica de código abierto que integra dos componentes principales: (1) una aplicación web desarrollada en Laravel 13/PHP 8.3+ con backend MVC y base de datos MySQL 8.0, que permite a docentes y usuarios solicitantes traducir texto en español al sistema Braille Español Grado 1 y solicitar la impresión de fichas táctiles en PLA; y (2) una impresora 3D cartesiana tipo Prusa i3 construida con componentes reciclados de basura tecnológica (e-waste), controlada por Arduino Mega 2560 + RAMPS 1.4 con firmware Marlin 1.1.x.

El problema que busca resolver es la limitada acceso a recursos educativos tridimensionales táctiles duraderos para estudiantes con discapacidad visual en instituciones de educación especial del municipio de Cochabamba, donde las impresoras Braille comerciales superan los $3,000 USD y los materiales táctiles tradicionales (papel punzado, cartón) se deterioran rápidamente.

**Usuarios del sistema:**

| Rol | Tipo | Acceso |
|---|---|---|
| Solicitante | Docente, Directivo o Tutor | Catálogo, traducción Braille, solicitud de impresión |
| Administrador / Operador | Equipo de desarrollo | Control total: gestión de usuarios, pedidos, descarga G-Code, reportes |

**Tecnologías principales:**
- **Backend:** Laravel 13, PHP 8.3+, MySQL 8.0, AdminLTE 3, Bootstrap 4
- **Hardware:** Arduino Mega 2560, RAMPS 1.4, drivers A4988, motores NEMA 17, extrusor MK8
- **Firmware:** Marlin 1.1.x configurado para arquitectura cartesiana
- **Entorno:** Docker Compose, Git/GitHub con LFS

**Información procesada o almacenada:**
- Credenciales de autenticación de usuarios (email, hash Bcrypt)
- Datos personales: nombres, emails, fotos de perfil
- Registros de instituciones educativas beneficiarias
- Catálogo de recursos educativos táctiles con imágenes y archivos G-Code
- Pedidos de impresión con estados, costos y rutas de archivos generados
- Parámetros de configuración del sistema (precio por gramo de PLA)
- Logs de sesiones y actividad del sistema

---

## 2. Activos de información

Un activo de información es cualquier dato o recurso que posee valor para la organización o para el funcionamiento del proyecto. A continuación se identifican los activos específicos del sistema:

| # | Activo | Descripción | Ubicación técnica | Criticidad |
|---|---|---|---|---|
| A1 | Credenciales de usuarios | Correo electrónico y hash Bcrypt de contraseñas | Tabla `users` (campo `password`) | **Alta** |
| A2 | Datos personales de usuarios | Nombre, email, foto de perfil, rol asignado | Tabla `users` (campos `name`, `email`, `foto_perfil`, `rol`) | **Alta** |
| A3 | Datos de instituciones beneficiarias | Nombre, dirección, teléfono, director, logo, documento PDF de respaldo | Tabla `instituciones` | **Media** |
| A4 | Catálogo de recursos educativos | Títulos, descripciones, peso en PLA, tiempo de impresión, imágenes y archivos G-Code | Tabla `recursos` + archivos en `public/recursos/` | **Alta** |
| A5 | Pedidos de impresión | Registros con institución de origen, fecha, estado, consumo PLA, costo total, ruta G-Code | Tabla `pedidos` + `detalle_pedidos` | **Alta** |
| A6 | Archivos G-Code generados | Archivos `.gcode` con coordenadas de impresión 3D (instrucciones G0/G1/G92) | Disco del servidor en `public/recursos/gcode/` | **Alta** |
| A7 | Parámetros de configuración | Clave-valor del sistema (precio_gramo_pla) | Tabla `configuracion_sistemas` | **Media** |
| A8 | Sesiones de usuario | Tokens de sesión activa, IP, user agent | Tabla `sessions` | **Media** |
| C9 | Código fuente y configuración | Código Laravel, dependencias, archivo `.env` con claves | Repositorio Git + disco del servidor | **Alta** |
| A10 | Backups manuales de BD | Exportaciones MySQL periódicas | Dispositivos de almacenamiento local | **Alta** |

---

## 3. Análisis de riesgos

Se identificaron las siguientes amenazas que podrían afectar a los activos de información del proyecto. Para cada riesgo se evalúa la probabilidad de ocurrencia (Baja, Media, Alta) y el impacto en caso de materializarse (Bajo, Medio, Alto, Crítico).

| # | Activo afectado | Amenaza | Probabilidad | Impacto |
|---|---|---|---|---|
| R1 | A1, A2 (Credenciales y datos de usuarios) | **Robo de credenciales** mediante ataque de fuerza bruta, phishing o ingeniería social | Media | Alto |
| R2 | Base de datos completa | **Acceso no autorizado** a la base de datos mediante inyección SQL u otras vulnerabilidades web | Baja | **Crítico** |
| R3 | A8 (Sesiones) | **Secuestro de sesión** (session hijacking) mediante robo de cookies o tokens de sesión | Baja | Alto |
| R4 | A6 (Archivos G-Code) | **Manipulación maliciosa** de archivos G-Code generados, provocando daño físico a la impresora 3D | Baja | Alto |
| R5 | Interfaz web | **Cross-Site Scripting (XSS)** mediante inyección de código JavaScript en formularios de entrada | Media | Medio |
| R6 | A10 (Backups) | **Pérdida de datos** por fallo de hardware, error humano o eliminación accidental | Baja | **Crítico** |
| R7 | Disponibilidad del servicio | **Caída del servidor** en la nube por fallo de infraestructura o ataque DDoS | Media | Alto |
| R8 | Archivo .env (claves, contraseñas de BD) | **Exposición de credenciales** por configuración insegura del servidor o control de versiones | Baja | **Crítico** |

---

## 4. Aplicación de la Triada CIA

### 4.1 Confidencialidad

La confidencialidad garantiza que la información sensible sea accesible únicamente por personas autorizadas. En el proyecto se implementan los siguientes mecanismos:

**Implementados:**

| Mecanismo | Implementación técnica | Estado |
|---|---|---|
| Autenticación con credenciales | Laravel Auth con validación email + contraseña | ✅ Implementado |
| Cifrado de contraseñas | Algoritmo Bcrypt (hash con salt automático) via Laravel `Hash::make()` | ✅ Implementado |
| Roles y permisos | Middleware `CheckRole` que valida el campo `rol` contra lista de roles permitidos | ✅ Implementado |
| Protección CSRF | Tokens CSRF automáticos de Laravel en formularios web | ✅ Implementado |
| Sesiones en base de datos | Tabla `sessions` en MySQL (no en archivos del sistema) | ✅ Implementado |
| Exclusión de G-Code | Solo el Administrador puede descargar archivos G-Code (middleware restrictivo) | ✅ Implementado |

**No implementados (recomendados):**

| Mecanismo | Descripción | Prioridad |
|---|---|---|
| Autenticación multifactor (MFA) | Segundo factor de verificación (SMS, app, email) | Alta |
| Cifrado de datos en reposo | Cifrado AES-256 de datos sensibles en la BD | Media |
| Forzado HTTPS | Certificado SSL/TLS obligatorio en todo el sitio | Alta |
| Políticas de contraseñas | Historial de contraseñas, expiración, complejidad mínima | Media |

### 4.2 Integridad

La integridad garantiza que la información no sea modificada de manera indebida, ya sea por errores humanos o por acción maliciosa. En el proyecto se implementan:

| Mecanismo | Implementación técnica | Estado |
|---|---|---|
| Validación de datos de entrada | Reglas de validación en controladores Laravel (`$reglas`, `$mensajes`) | ✅ Implementado |
| SoftDeletes (papelera de eliminación) | Todos los modelos principales usan `SoftDeletes` para prevenir eliminación accidental | ✅ Implementado |
| Claves foráneas con integridad referencial | 5 foreign keys con `constrained()` y `cascadeOnDelete` / `nullOnDelete` | ✅ Implementado |
| Enums de valores permitidos | Campos `estado` y `rol` restringidos a valores válidos | ✅ Implementado |
| Tokens CSRF | Prevención de ataques de falsificación de solicitudes cruzadas | ✅ Implementado |
| Arquitectura MVC | Separación de responsabilidades Model-Vista-Controlador | ✅ Implementado |

**No implementados (recomendados):**

| Mecanismo | Descripción | Prioridad |
|---|---|---|
| Logs de auditoría detallados | Registro de quién realizó cada acción y cuándo | Alta |
| Hash de integridad de archivos G-Code | Verificación SHA-256 al descargar el archivo | Media |
| Control de versiones de datos | Historial de cambios en registros sensibles | Baja |
| Auditorías periódicas | Revisión programada de accesos y operaciones | Media |

### 4.3 Disponibilidad

La disponibilidad garantiza que los usuarios autorizados puedan acceder a la información y a los servicios del sistema cuando lo necesiten.

| Mecanismo | Implementación técnica | Estado |
|---|---|---|
| Backups manuales de BD | El Administrador exporta MySQL periódicamente a dispositivos locales | ⚠️ Parcial |
| Sesiones en BD | Las sesiones sobreviven reinicios del servidor web | ✅ Implementado |
| Arquitectura cliente-servidor | El backend reside en servidor en la nube con alta disponibilidad | ✅ Implementado |
| SoftDeletes | Los datos eliminados accidentalmente pueden restaurarse desde la papelera | ✅ Implementado |

**No implementados (recomendados):**

| Mecanismo | Descripción | Prioridad |
|---|---|---|
| Backups automatizados | Cron job que exporta BD diariamente sin intervención manual | Alta |
| Replicación de BD | Replica de lectura para分散ar la carga | Baja |
| Sistema UPS | Protección contra cortes de energía para el servidor local | Media |
| Plan de recuperación ante desastres | Procedimiento documentado para restaurar el sistema desde cero | Alta |
| Monitoreo de disponibilidad | Alertas automáticas cuando el servicio cae | Media |

---

## 5. Controles de seguridad

Los controles propuestos están directamente relacionados con los riesgos identificados en la sección 3. Se clasifican en controles técnicos (implementados en software o hardware) y controles administrativos (políticas, procedimientos y capacitación).

| Riesgo | Control propuesto | Tipo | Responsable |
|---|---|---|---|
| **R1:** Robo de credenciales | Autenticación con Bcrypt + bloqueo temporal de cuentas tras intentos fallidos + política de contraseñas mínimas (8 caracteres) | Técnico | Backend/Laravel |
| **R2:** Acceso no autorizado a BD | Eloquent ORM con consultas preparadas (PDO) + permisos restrictivos de usuario MySQL + firewall de red | Técnico | DevOps |
| **R3:** Secuestro de sesión | Sesiones almacenadas en BD (no cookies firmadas) + invalidación al cerrar sesión + regeneración de token CSRF | Técnico | Backend/Laravel |
| **R4:** Manipulación de G-Code | Validación de tipo de archivo al subir + verificación de integridad (longitud mínima, cabecera G-Code válida) + acceso exclusivo Administrador | Técnico | Backend/Laravel |
| **R5:** Cross-Site Scripting (XSS) | Escapado automático de Blade `{{ variable }}` + Content-Security-Policy headers + sanitización de entradas en formularios | Técnico | Backend/Laravel |
| **R6:** Pérdida de datos | Backups manuales periódicos del MySQL + exportación a dispositivos locales separados + verificación de integridad | Administrativo | Administrador |
| **R7:** Caída del servidor | SLA del proveedor de hosting + monitoreo básico de uptime + documentación del plan de migración | Administrativo | Administrador |
| **R8:** Exposición de .env | Archivo `.env` excluido de Git (`.gitignore`) + permisos `chmod 600` en servidor + variables de entorno no hardcodeadas en código | Técnico | DevOps |

---

## 6. Conclusiones

La incorporación de medidas de seguridad de la Información desde la etapa de diseño fortalece significativamente la calidad y confiabilidad del proyecto. El análisis realizado permitió identificar ocho riesgos relevantes que afectan tanto a la disponibilidad del servicio como a la confidencialidad e integridad de los datos almacenados.

Los principales riesgos identificados están relacionados con el robo de credenciales de usuario, el acceso no autorizado a la base de datos y la pérdida de datos por falta de backups automatizados. Estos riesgos son comunes en aplicaciones web y requieren atención inmediata para garantizar la seguridad del sistema.

La implementación de controles técnicos —como el cifrado Bcrypt, el ORM Eloquent con consultas preparadas, el middleware de roles y los SoftDeletes— proporciona una base sólida de seguridad para el sistema. Sin embargo, se recomienda la incorporación progresiva de controles adicionales como la autenticación multifactor (MFA), los logs de auditoría detallados, los backups automatizados y un plan formal de recuperación ante desastres.

La incorporación de estos controles desde la etapa de diseño reduce significativamente la superficie de ataque, protege la información sensible de los usuarios y las instituciones beneficiarias, y garantiza la continuidad operativa del servicio de traducción Braille y producción de recursos educativos táctiles para personas con discapacidad visual.

---

**Referencias:**
- OWASP Foundation. (2021). *OWASP Top Ten Web Application Security Risks*. https://owasp.org/www-project-top-ten/
- Laravel Documentation. (2026). *Security*. https://laravel.com/docs/master/security
- Braille Authority of North America. (2013). *Standards for Braille Codes*. https://www.brailleauthority.org/
- Ley N° 223 de Educación (Bolivia). Marco legal para educación inclusiva.
- Decreto Supremo N° 1893 (Bolivia). Reglamentación de atención a personas con discapacidad.
