# Sistema Web y Electromecánico de Impresión 3D con Materiales Reciclados para la Producción de Recursos Educativos Táctiles en Braille

> **Proyecto Sociocomunitario Productivo (PSCP)**
> Instituto Técnico "Federico Alvarez Plata" — Cochabamba, Bolivia
> Mayo – Septiembre 2026

---

## Descripción

Este proyecto desarrolla un **sistema integral** que combina una plataforma web de traducción automática texto→Braille con una impresora 3D cartesiana construida a partir de componentes electrónicos recuperados (e-waste), para producir recursos educativos táctiles en Braille a bajo costo (~Bs. 5 por pieza).

**Problema que resuelve:** Limitado acceso a recursos educativos tridimensionales táctiles duraderos para estudiantes con discapacidad visual en instituciones de educación especial del municipio de Cochabamba.

---

## Objetivo General

Desarrollar un sistema web y electromecánico de impresión 3D con materiales reciclados para la producción automatizada de recursos educativos táctiles en Braille.

---

## Equipo de Desarrollo

| Miembro | Rol | Alcance |
|---|---|---|
| Rosales Mamani Ariel Edson | Software Backend y Algoritmos | Laravel, Python, traducción Braille, G-Code |
| Aguilar Castellon Cristhian Alessandro | Hardware y Electromecánica | Arduino, RAMPS, Marlin, ensamblaje |
| Aramayo Eguino Jose Matias | Software Frontend, UI/UX y Validación | AdminLTE, Bootstrap, pruebas de usabilidad |

---

## Estructura del Repositorio

```
sistema-3d-inclusivo/
├── software/
│   ├── laravel_web/          # Laravel 13 — Plataforma web (AdminLTE, Blade, MySQL)
│   └── python_core/          # Python 3 — Algoritmo de generación de G-Code
├── hardware/
│   ├── cad_planos/           # FreeCAD (.FCStd) — Chasis, piezas mecánicas
│   ├── marlin_firmware/      # Marlin 1.1.x — Configuration.h, pines, calibración
│   ├── exportaciones_3d/     # Exportaciones STL desde CAD
│   └── fotos_avance/         # Fotos de progreso de ensamblaje
├── docs/
│   ├── documento_pscp/       # Documento PSCP (.docx) — aplica protocolo de bloqueo
│   ├── anexos/               # Anexos técnicos, contexto sociocomunitario
│   └── casos_de_uso/         # Diagramas UML (PlantUML + PNGs)
├── AGENTS.md                 # Configuración del equipo de desarrollo
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
| UI Theme | AdminLTE 3 (Bootstrap 4) |
| Autenticación | Laravel Auth + Bcrypt |
| Exportaciones | DomPDF (PDF), Maatwebsite/Excel |
| Algoritmo Braille | Python 3 (planificado) |
| Control de versiones | Git / GitHub |

### Hardware
| Componente | Especificación |
|---|---|
| Arquitectura | Prusa i3 cartesiana |
| Controlador | Arduino Mega 2560 + RAMPS 1.4 |
| Drivers | 4× A4988 |
| Motores | 3× NEMA 17 (recuperados de e-waste) |
| Tracción | Correa GT2 (X/Y), varillas roscadas (Z) |
| Extrusor | MK8 directo, boquilla 0.8mm |
| Material | Filamento PLA 1.75mm biodegradable |
| Firmware | Marlin 1.1.x |
| Conexión | USB directa (Tethered Printing) |

---

## Módulos del Sistema

| # | Módulo | Descripción | Estado |
|---|---|---|---|
| 1 | Gestión de Usuarios | Autenticación con roles (Administrador/Solicitante), CRUD, papelera | ✅ Implementado |
| 2 | Traducción Braille | Texto→Braille Grado 1→G-Code (Código Braille Español ONCE) | 📋 Planificado |
| 3 | Gestión de Pedidos | Solicitudes de impresión, estados, cálculo de PLA y costo | 📋 Planificado |
| 4 | Catálogo Digital | CRUD de recursos táctiles, imágenes, G-Code, exportaciones | ✅ Implementado |
| 5 | Hardware CNC | Impresora 3D Prusa i3 con e-waste, Marlin 1.1.x | 📋 Planificado |
| 6 | Validación Sociocomunitaria | Pruebas piloto en instituciones de educación especial | ⏳ Pendiente |

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

### Diagramas de Diseño
- **Diagrama de Clases del Dominio** (User, Institucion, Recurso, Pedido, ConfiguracionSistema)
- **Diagrama de Secuencia UC-06/UC-07** (2 fases: Previsualización 2D + Confirmación/G-Code/Pedido)
- **Diagrama de Gantt** (Mayo–Septiembre 2026)

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

# 4. Configurar base de datos en .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sistema_braille
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Ejecutar migraciones y seed
php artisan migrate:fresh --seed

# 6. Iniciar servidor
php artisan serve
```

### Con Docker
```bash
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

## Presupuesto Estimado

| Categoría | Monto (Bs.) | Nota |
|---|---|---|
| Electrónica (RAMPS, drivers, sensores) | ~600 | Componentes críticos |
| Mecánica (varillas, rodamientos, correas) | ~400 | Complementos |
| Extrusor y filamento PLA | ~200 | Consumibles |
| Validación y pruebas | ~200 | Encuestas, transporte |
| **Total** | **~1.400** | **≈ $200 USD** |

*Hardware e-waste y software Open Source tienen costo cero.*

---

## Licencia

Este proyecto está bajo la Licencia MIT — ver el archivo [LICENSE](LICENSE) para más detalles.

---

## Contacto

**Equipo de Desarrollo — PSCP Sistema Braille Inclusivo**
Instituto Técnico "Federico Alvarez Plata"
Cochabamba, Bolivia
