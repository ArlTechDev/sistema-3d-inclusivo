# Sistema Web e Impresora 3D con Materiales Reciclados para la Creación de Recursos Táctiles Destinados a Personas No Videntes

> **Proyecto Sociocomunitario Productivo (PSCP)**
> Instituto Técnico "Federico Alvarez Plata" — Cochabamba, Bolivia
> Mayo – Septiembre 2026

---

## Descripción

Este proyecto desarrolla un **sistema integral** que combina una plataforma web de traducción automática texto→Braille con una impresora 3D cartesiana construida a partir de componentes electrónicos recuperados (e-waste), para producir recursos educativos táctiles en Braille a bajo costo (~Bs. 5 por pieza).

**Problema que resuelve:** Limitado acceso a recursos educativos tridimensionales táctiles duraderos para estudiantes con discapacidad visual en instituciones de educación especial del municipio de Cochabamba.

---

## Objetivo General

Desarrollar un sistema web e impresora 3D con materiales reciclados para la creación de recursos táctiles destinados a personas no videntes en instituciones de educación especial de Cochabamba.

---

## Equipo de Desarrollo

| Miembro | Rol | Alcance |
|---|---|---|
| Rosales Mamani Ariel Edson | Software Backend y Algoritmos | Laravel, PHP (App\Services\BrailleTranslator), traducción Braille, G-Code |
| Aguilar Castellon Cristhian Alessandro | Hardware y Electromecánica | Arduino, RAMPS, Marlin, ensamblaje |
| Aramayo Eguino Jose Matias | Software Frontend, UI/UX y Validación | AdminLTE, Blade accesible, pruebas de usabilidad |

---

## Estructura del Repositorio

```
sistema-3d-inclusivo/
├── software/
│   ├── laravel_web/          # Laravel 13 — Plataforma web (AdminLTE, Blade, MySQL, BrailleTranslator)
│   └── python_core/          # ARCHIVADO — algoritmo migrado a PHP; conservado como respaldo
├── hardware/
│   ├── cad_planos/           # FreeCAD (.FCStd) — Chasis, piezas mecánicas
│   ├── marlin_firmware/      # Marlin 1.1.x — Configuration.h, pines, calibración
│   ├── exportaciones_3d/     # Exportaciones STL desde CAD
│   └── fotos_avance/         # Fotos de progreso de ensamblaje
├── docs/
│   ├── documento_pscp/       # Documento PSCP (.docx) — aplica protocolo de bloqueo
│   ├── anexos/               # Anexos técnicos 01–14, contexto sociocomunitario y seguridad
│   └── casos_de_uso/         # Diagramas UML (PlantUML + PNGs)
├── .github/workflows/        # CI/CD automatizado (PHPUnit + Larastan)
├── AGENTS.md                 # Configuración del equipo y convenciones del monorepo
├── LICENSE                   # MIT License
└── README.md                 # Este archivo
```

---

## Stack Tecnológico

### Software
| Componente | Tecnología |
|---|---|
| Backend | Laravel 13 (PHP 8.3+) |
| Base de datos | MySQL 8.0 |
| UI Theme | AdminLTE 3 (Bootstrap 4) + Layout público accesible |
| Autenticación | Laravel Auth + Bcrypt + RateLimiting (anti-fuerza bruta) |
| Exportaciones | DomPDF (PDF), Maatwebsite/Excel |
| Algoritmo Braille | Laravel Service PHP (`App\Services\BrailleTranslator`, Grado 1 ONCE, PHP puro) |
| Seguridad | OWASP Top 10 (Sanitizer anti-XSS, SecurityHeaders, SafeRedirect, Throttle) |
| Control de versiones | Git / GitHub |

### Hardware
| Componente | Especificación |
|---|---|
| Arquitectura | Prusa i3 cartesiana |
| Controlador | Arduino Mega 2560 + RAMPS 1.4 |
| Drivers | 4× A4988 |
| Motores | 4× NEMA 17 (recuperados de e-waste: 3 para ejes X, Y, Z y 1 para extrusor MK8) |
| Tracción | Correa GT2 (X/Y), varillas roscadas M8 (Z) |
| Extrusor | MK8 directo, boquilla 0.8mm |
| Material | Filamento PLA 1.75mm biodegradable |
| Firmware | Marlin 1.1.x |
| Conexión | USB directa (Tethered Printing / tarjeta SD air-gapped) |

---

## Módulos del Sistema

| # | Módulo | Descripción | Estado |
|---|---|---|---|
| 1 | Gestión de Usuarios | Autenticación con roles (Administrador/Solicitante), CRUD, papelera | ✅ Implementado |
| 2 | Traducción Braille | Texto→Braille Grado 1→G-Code en PHP puro (`BrailleTranslator`) | ✅ Implementado |
| 3 | Gestión de Pedidos | Solicitudes, estados (`Pendiente`→`Aprobado`→`En impresión`→`Completado`), cálculo PLA y costo | ✅ Implementado |
| 4 | Catálogo Digital | CRUD de recursos táctiles, campos 3D (STL/GLB), exportaciones PDF/Excel | ✅ Implementado |
| 5 | Hardware CNC | Impresora 3D Prusa i3 con e-waste, Marlin 1.1.x | 📋 En calibración |
| 6 | Validación Sociocomunitaria | Pruebas piloto en instituciones de educación especial | ⏳ Pendiente |

---

## Calidad y Pruebas

- **Pruebas Automatizadas:** 85 tests PHPUnit (368 aserciones) con cobertura completa en traducción Braille, transiciones de estado, autorización de roles y seguridad OWASP.
- **Análisis Estático:** PHPStan / Larastan Nivel 5 (0 errores).
- **CI/CD:** Automatizado mediante GitHub Actions ([`.github/workflows/ci.yml`](.github/workflows/ci.yml)).

---

## Diagramas UML

Los diagramas UML se encuentran en `docs/casos_de_uso/`:

### Casos de Uso (11 diagramas)
- **UC-00:** Diagrama General del Sistema
- **UC-01:** Iniciar / Cerrar Sesión
- **UC-02:** Gestionar Catálogo de Recursos
- **UC-03:** Ver Catálogo de Recursos
- **UC-04:** Gestionar Instituciones
- **UC-05:** Gestionar Usuarios
- **UC-06:** Traducir Texto a Braille
- **UC-07:** Solicitar Impresión
- **UC-08:** Gestionar Solicitudes
- **UC-09:** Descargar G-Code
- **UC-10:** Generar Reportes y Estadísticas

### Diagramas de Arquitectura y Dominio
- **Diagrama de Clases del Dominio** (User, Institucion, Recurso, Pedido, DetallePedido, Categoria, ConfiguracionSistema)
- **Diagrama de Estados del Pedido** (Figura 15: Pendiente → Aprobado → En impresión → Completado / Rechazado)
- **Diagrama de Despliegue** (Figura 16: Topología física sin dependencias externas)
- **Diagrama Entidad-Relación** (Figura 17: Modelo relacional MySQL Workbench)

---

## Instalación (Software Web)

```bash
# 1. Clonar el repositorio
git clone https://github.com/ArlTechDev/sistema-3d-inclusivo.git
cd sistema-3d-inclusivo/software/laravel_web

# 2. Instalar dependencias PHP
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env y migrar con seeders
php artisan migrate:fresh --seed

# 5. Iniciar servidor de desarrollo
php artisan serve
```

### Con Docker
```bash
cd software/laravel_web
docker compose up -d --build
docker exec -it laravel_app php artisan migrate:fresh --seed
```

---

## Credenciales de Prueba

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@admin.com | admin123 |
| Solicitante | docente@test.com | 12345678 |

---

## Presupuesto Estandarizado

| Categoría de Gasto | Subtotal (Bs.) | % del Total | Detalle |
|---|---|---|---|
| Hardware electrónico (controladora, drivers, extrusor) | ~330 | 47,14% | Arduino Mega + RAMPS 1.4, drivers A4988, extrusor MK8 |
| Consumibles de impresión (PLA, boquilla) | ~120 | 17,14% | Filamento PLA 1.75mm (1 kg) + boquilla 0.8mm repuesto |
| Estructura mecánica (madera, tornillería, transmisión) | ~150 | 21,43% | Chasis de madera local, correas GT2, rodamientos LM8UU |
| Validación y contingencia (pruebas piloto, reserva) | ~100 | 14,29% | Calibración, traslados y reserva operativa |
| E-waste y software Open Source | 0 | 0% | 4× NEMA 17, fuente ATX, varillas ø8mm, stack de software |
| **TOTAL** | **~700** | **100%** | **≈ $100 USD (al tipo de cambio de mayo 2026)** |

*Representa una reducción del ~93% frente a equipos comerciales equivalentes (≈ 15× más costosos).*

---

## Licencia

Este proyecto está bajo la Licencia MIT — ver el archivo [LICENSE](LICENSE) para más detalles.

---

## Contacto

**Equipo de Desarrollo — PSCP Sistema Braille Inclusivo**  
Instituto Técnico "Federico Alvarez Plata"  
Cochabamba, Bolivia
