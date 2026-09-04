# Guía de Contribución y Gobernanza del Repositorio

Bienvenido/a al repositorio oficial del **Sistema Web e Impresora 3D con Materiales Reciclados para la Creación de Recursos Táctiles Destinados a Personas No Videntes** (Proyecto Sociocomunitario Productivo — INCOS / Federico Alvarez Plata, Cochabamba, Bolivia).

Este proyecto es un monorepo que integra software web (Laravel 13), firmware electromecánico (Marlin 1.1.x), diseño CAD (FreeCAD) y el documento de grado formal. Dado que el repositorio es **público**, todo colaborador y miembro del equipo debe seguir estrictamente las siguientes normas de seguridad, calidad y control de versiones.

---

## 1. Seguridad de Datos: Qué NUNCA Commitear

Para proteger la integridad del proyecto, las credenciales del sistema y la privacidad de terceros conforme a la **Ley General para Personas con Discapacidad (Ley N° 223)** y la **Constitución Política del Estado (Art. 21)**, está **ESTRICTAMENTE PROHIBIDO** incluir en commits:

| Categoría | Elementos Prohibidos | Razón / Consecuencia |
|---|---|---|
| **Secretos y Credenciales** | Archivos `.env`, `.env.production`, claves privadas (`*.key`, `*.pem`), tokens de API, contraseñas de correos o base de datos. | Compromiso de seguridad y filtración pública. |
| **Datos Personales Sensibles (PII)** | Nombres completos de menores o estudiantes con discapacidad visual, números de carnet de identidad (CI), teléfonos privados o direcciones particulares recopiladas en encuestas del diagnóstico. | Vulneración de la Ley N° 223 y normativas de confidencialidad en educación especial. |
| **Bases de Datos Reales y Logs** | Dumps SQL con datos reales (`*.sql`, `dump.sql`), bases de datos SQLite locales (`*.sqlite`), archivos de log (`storage/logs/*.log`). | Exposición de historiales operativos. |
| **Dependencias y Compilados** | Carpetas `vendor/`, `node_modules/`, `public/build/`, `.phpunit.result.cache`. | Ruido masivo y conflicto de dependencias; se generan con `composer install` o `npm run build`. |
| **Archivos Temporales de Oficina** | Archivos de bloqueo de Microsoft Word (`~$*.docx`, `~WRL*.tmp`), temporales de IDE (`.vscode/`, `.idea/`, `*.swp`). | Bloqueo de Git LFS y corrupción de diffs. |

> [!CAUTION]
> Si detectas que se ha commiteado por error cualquier secreto o dato personal sensible, notifícalo inmediatamente al equipo antes de hacer push para reescribir el historial local con `git reset` o `git filter-repo`.

---

## 2. Qué SÍ Commitear

- **Código fuente probado**: Controladores delgados, servicios (`App\Services\BrailleTranslator`), Form Requests con sanitización anti-XSS, migraciones y seeders con datos sintéticos/ficticios.
- **Pruebas automatizadas**: Tests unitarios y funcionales de PHPUnit (`tests/Unit/`, `tests/Feature/`) asegurando que los 85 tests sigan en verde.
- **Hardware y Firmware**: Planos CAD en FreeCAD (`.FCStd`), archivos de calibración Marlin (`Configuration.h`, `Configuration_adv.h`), exportaciones `.stl`.
- **Documentación oficial**: Documento maestro [DocumentoFinalPSCP3DAgosto17.docx](docs/documento_pscp/DocumentoFinalPSCP3DAgosto17.docx), espejo Markdown [DocumentoFinal.md](docs/documento_pscp/DocumentoFinal.md), diagramas PlantUML (`.puml`) y anexos técnicos.
- **Scripts de automatización**: Scripts bash/python verificados bajo `scripts/`.

---

## 3. Formato Obligatorio de Commits (Conventional Commits v1.0.0)

Todos los mensajes de commit deben redactarse en **español** en modo imperativo y seguir la convención estándar:

```text
<tipo>(<alcance>): <descripción corta en minúsculas>

[cuerpo opcional con detalles técnicos]

[pie opcional con referencias a tareas o issues]
```

### Tipos Permitidos

| Tipo | Propósito | Ejemplo |
|---|---|---|
| `feat` | Nueva funcionalidad en software o hardware | `feat(web): agregar exportación de pedidos a PDF` |
| `fix` | Corrección de un error o inconsistencia | `fix(infra): habilitar auto-start de servidor en docker-compose` |
| `docs` | Cambios exclusivos en documentación o diagramas | `docs(pscp): actualizar justificación y bibliografía APA 7` |
| `refactor` | Refactorización de código sin cambio de comportamiento | `refactor(api): extraer cálculo de costos a PedidoService` |
| `test` | Incorporación o ajuste de pruebas unitarias/funcionales | `test(web): agregar test para transición de estado aprobado` |
| `chore` | Mantenimiento de configuración, dependencias o monorepo | `chore(infra): configurar plantilla gitmessage y hooks` |

### Alcances (Scopes) del Monorepo

| Alcance | Componente |
|---|---|
| `web` | Vistas Blade, AdminLTE, controladores, Form Requests, estilos |
| `api` | Servicios Laravel, algoritmos de traducción Braille, endpoints |
| `hw` | Firmware Marlin, electrónica RAMPS/Arduino Mega, modelos CAD |
| `pscp` | Documento de grado formal (`.docx`, `.md`, anexos) |
| `infra` | Docker, Compose, scripts bash, CI/CD de GitHub Actions |
| `sec` | Middleware de seguridad, sanitización, rate limiting OWASP |

---

## 4. Asistencia y Hooks Locales

Para ayudarte a cumplir estas reglas sin esfuerzo, el repositorio incluye herramientas locales:

1. **Instalación automática de hooks y plantilla**:
   Ejecuta desde la raíz del proyecto:
   ```bash
   bash scripts/git/instalar_hooks.sh
   ```
   Esto activará:
   - **Plantilla `.gitmessage`**: Al ejecutar `git commit` sin `-m`, se abrirá una guía interactiva en tu editor con la estructura prellenada.
   - **Hook `commit-msg`**: Valida automáticamente que tu mensaje cumpla la sintaxis antes de confirmar el commit, bloqueando mensajes inválidos.

---

## 5. Git LFS (Archivos Grandes)

El proyecto rastrea archivos binarios pesados mediante Git LFS (`*.FCStd`, `*.stl`, `*.gcode`, `*.docx`, `*.pdf`, `*.png`, `*.jpg`).
- Tras clonar o hacer `git pull`, ejecuta siempre:
  ```bash
  git lfs pull
  ```
- No commitees binarios pesados nuevos sin verificar que su extensión esté cubierta en `.gitattributes`.

---

## 6. Verificación Previa al Push

Antes de enviar tus cambios al repositorio remoto (`origin/main`), ejecuta la suite de calidad:

```bash
# 1. Ejecutar pruebas unitarias de Laravel
docker exec laravel_app php artisan test

# 2. Análisis estático (PHPStan nivel 5)
docker exec laravel_app ./vendor/bin/phpstan analyse --memory-limit=512M

# 3. Formateo de código Laravel
docker exec laravel_app ./vendor/bin/pint --test

# 4. Smoke test de rutas y roles HTTP
bash scripts/pruebas/smoke_test.sh
```

Si todas las pruebas pasan y tu commit cumple la convención, puedes hacer push con confianza:
```bash
git push origin main
```
