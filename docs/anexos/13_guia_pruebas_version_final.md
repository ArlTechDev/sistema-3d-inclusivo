# 13 — Guía de Pruebas de la Versión Final

Guía para probar el sistema completo en tu PC antes de la defensa: qué verificar, cómo y qué resultado esperar. Cubre los 10 casos de uso, la seguridad y la salida G-Code.

## 1. Preparación del entorno

### Opción A — Docker (recomendada, es el entorno de despliegue)

```bash
cd software/laravel_web
docker compose up -d --build

# (Solo la primera vez, o para datos de prueba limpios)
docker exec -it laravel_app php artisan migrate:fresh --seed

# La app se sirve dentro del contenedor (arranca con tail -f /dev/null):
docker exec -it laravel_app php artisan serve --host=0.0.0.0 --port=8000
#   → abre http://localhost:8000
```

> **Importante — `.env` apuntando a la BD de Docker**: el contenedor lee el `.env` del proyecto (bind mount) y `artisan serve` relanza el servidor sin heredar variables del entorno, así que el `.env` debe tener `DB_HOST=db`, `DB_PORT=3306`, `DB_USERNAME=admin`, `DB_PASSWORD=password` (no `127.0.0.1:3307`). Para correr nativo sin tocar `.env`, `scripts/start_server.sh` ya exporta SQLite automáticamente.

MySQL: `localhost:3307` (usuario `admin` / password `password`, BD `sistema_inclusivo`).

### Opción B — Nativo (PHP local + SQLite)

```bash
cd software/laravel_web
cp .env.example .env      # si no existe
php artisan key:generate  # si no existe APP_KEY
php artisan migrate --seed
composer dev              # o: php artisan serve
#   → abre http://localhost:8000
```

### Credenciales del seed

| Rol | Email | Password |
|---|---|---|
| **Administrador** | `admin@admin.com` | `admin123` |
| **Solicitante** (docente/directivo/tutor) | `docente@test.com` | `12345678` |

## 2. Verificación automatizada previa (30 segundos)

```bash
cd software/laravel_web
composer test      # 42+ tests — debe terminar en OK
composer analyse   # PHPStan nivel 5 — debe decir "No errors"
./vendor/bin/pint --test  # debe decir PASS

# Smoke test HTTP de las rutas y roles (app corriendo en :8000):
bash ../scripts/pruebas/smoke_test.sh   # esperado: 20 PASS · 0 FAIL
```

Si algo falla aquí, no sigas: corregir primero.

## 3. Checklist funcional por caso de uso

Convención de resultado esperado: ✅ = comportamiento correcto. Cada fila se repite en la checklist imprimible (§5).

| # | Prueba | Pasos | Resultado esperado |
|---|---|---|---|
| 1 | **UC-01 Login OK (admin)** | `http://localhost:8000/login` → `admin@admin.com` / `admin123` → Entrar | Redirige a `/recursos` mostrando la **tabla de gestión** |
| 2 | **UC-01 Login OK (solicitante)** | Cerrar sesión → `docente@test.com` / `12345678` | Redirige a `/recursos` mostrando el **catálogo de cards** |
| 3 | **UC-01 Login fallido** | Email o password incorrectos | Mensaje «Credenciales inválidas», sin entrar |
| 4 | **UC-01 Fuerza bruta** | 5 intentos fallidos seguidos; en el 6º intentar de nuevo | El 6º intento responde **429** (Too Many Requests) aunque la credencial sea correcta; esperar 1 minuto para reintentar |
| 5 | **UC-01 Logout** | Botón Salir | Redirige a `/login`; `/recursos` sin sesión redirige a `/login` (302) |
| 6 | **UC-02 Usuarios** | Admin → menú Usuarios → Nuevo: crear con rol `Solicitante` | Se guarda y aparece en la tabla |
| 7 | **UC-02 Email duplicado** | Editar un usuario y poner un email ya existente | Validación: «El email ya está en uso» (sin 500) |
| 8 | **UC-02 Papelera** | Eliminar un usuario → entrar a Papelera → Restaurar | El usuario vuelve a la lista; también funciona Eliminación definitiva |
| 9 | **UC-03 Catálogo (solicitante)** | Sesión Solicitante → `/recursos` | Cards de solo recursos `Activo`, con imagen, gramos, tiempo y botón «Solicitar Impresión» |
| 10 | **UC-03 Filtro por categoría** | Seleccionar una categoría en el catálogo | Solo se muestran recursos de esa categoría |
| 11 | **UC-04 Recursos (admin)** | Admin → Recursos → Nuevo (título, descripción, gramos, tiempo, **fecha**, imagen, G-Code opcional, estado) | Se guarda; aparece en la tabla con su imagen |
| 12 | **UC-04 Editar/Eliminar recurso** | Editar datos o eliminar | Los cambios persisten; el eliminado va a la papelera (restaurar/eliminar definitivo) |
| 13 | **UC-05 Instituciones (admin)** | Crear/editar/eliminar institución (nombre, dirección, teléfono, director, logo) | CRUD + papelera funcionan igual que recursos |
| 14 | **UC-06/07 Traductor + pedido** | Sesión Solicitante → «Solicitar Impresión» → seleccionar recurso, cantidad y **texto personalizado** `ÑANDÚ` → Enviar | El pedido se registra con estado `Pendiente`, costo calculado (gramos × precio) y se genera su **G-Code** |
| 15 | **UC-06 Caracteres inválidos** | Texto personalizado con `@#%` | Validación: «El texto contiene caracteres no soportados» (no se crea el pedido) |
| 16 | **UC-08 Estados del pedido** | Admin → Pedidos → «En impresión» → luego «Completado» | El estado cambia según el flujo; se rechaza solo desde Pendiente/En impresión |
| 17 | **UC-08 Rechazo con motivo** | Admin → Rechazar sin motivo, y luego con motivo | Sin motivo: error de validación; con motivo: el pedido queda `Rechazado` y el Solicitante ve el motivo |
| 18 | **UC-09 Descarga G-Code (admin)** | Admin → Pedidos → Descargar G-Code | Descarga un `.gcode` (ver §4 para validar el contenido) |
| 19 | **UC-09 Solicitante NO descarga** | Sesión Solicitante → abrir la URL del G-Code de un pedido | **403 Forbidden** (nunca puede descargar) |
| 20 | **Exports solo admin** | Solicitante → `/recursos/exportar/pdf`, `/instituciones/exportar/excel`, `/pedidos/exportar/excel` | **403** en todos; como admin descargan PDF/Excel correctos |
| 21 | **XSS (manual)** | Admin → crear recurso con título `<script>alert(1)</script>` | El título se guarda **escapado** (se ve el texto, no se ejecuta el script) |
| 22 | **Navegación y roles** | Solicitante no debe ver menús de Usuarios/Instituciones/Papelera | Solo menú de su rol; las rutas directas responden 403 (`/pedidos`, `/usuarios`, `/instituciones`, `/recursos/papelera`, `/instituciones/papelera`, `/usuarios/papelera`) |

## 4. Verificación del contenido G-Code (el CORE)

Al descargar el G-Code de un pedido con texto personalizado `ÑANDÚ`, abrir el archivo y comprobar:

- **Cabecera de inicialización**: `G21` (mm), `G28` (home), `M84` o similar.
- **Coordenadas coherentes**: líneas `G1 X… Y… Z…` con valores positivos dentro del área útil (p. ej. X 5–90, Y 5–90, Z 0.2–1.6 para una ficha de 100×100 mm).
- **Corresponde al texto**: el número de celdas Braille ≈ caracteres válidos del texto (ÑANDÚ = 5 letras → 5 celdas de 6 puntos).
- **Sin basura**: solo comandos G/M válidos y comentarios; sin caracteres raros ni texto plano intercalado.

> El traductor usa el **Código Braille Español Grado 1** (27 letras con ñ, dígitos con signo numeral, puntuación básica; sin estenografía). La traducción se cubre además con tests unitarios (27 letras + casos límite).

## 5. Checklist imprimible para la defensa

Imprimir y tildar:

- [ ] `composer test` → OK (43 tests)
- [ ] `composer analyse` → No errors
- [ ] Login Administrador
- [ ] Login Solicitante
- [ ] Login fallido muestra error
- [ ] 6º intento de login → 429
- [ ] Crear / editar usuario
- [ ] Email duplicado rechazado
- [ ] Papelera: eliminar + restaurar (usuario y recurso)
- [ ] Catálogo del Solicitante (solo Activos, filtro por categoría)
- [ ] Crear recurso con imagen y fecha
- [ ] Crear institución con logo
- [ ] Pedido con texto personalizado (ÑANDÚ) → estado Pendiente
- [ ] Texto con caracteres inválidos rechazado
- [ ] Avanzar estados: En impresión → Completado
- [ ] Rechazo con motivo obligatorio
- [ ] Admin descarga G-Code válido (G21/G28, coordenadas)
- [ ] Solicitante NO descarga G-Code (403)
- [ ] Exports PDF/Excel solo admin (403 para Solicitante)
- [ ] XSS escapado en título de recurso

## 6. Notas y discrepancias conocidas

- **No hay registro público ni recuperación de contraseña por email** (sin SMTP): los usuarios los crea el Administrador (UC-02) y el cambio de contraseña se hace desde la edición de usuario. La página de login muestra «¿Olvidaste tu contraseña? Contacta al administrador del sistema» (2026-08: se eliminaron los enlaces muertos «I forgot my password» / «Register a new membership» y el logo apunta a `/recursos`, no a `/home`).
- **El catálogo requiere sesión** (`/recursos` está dentro del grupo `auth`): el Solicitante lo ve como cards tras iniciar sesión. Esto **coincide con el documento** (UC-03: «El Solicitante ha iniciado sesión»). La frase del README «visible sin sesión» es imprecisa y se corrige.
- **Autorización de `/instituciones` corregida** (2026-08): el índice de instituciones (tabla de gestión, UC-05) quedó restringido a Administrador — antes cualquier usuario autenticado podía verlo por URL directa. Cubierto por el test `test_paginas_de_gestion_son_solo_para_administradores` y por el smoke test.
- **No hay previsualización 2D en la UI** (RF-08 del documento promete una «previsión visual 2D»): la verificación del traductor se hace sobre el G-Code generado y los tests unitarios. Si la defensa lo exige, se puede añadir después como mejora.
- **Precio del gramo de PLA**: configurable en la tabla `configuracion_sistema` (clave `precio_gramo_pla`, default 0.05 Bs). El costo del pedido = gramos × cantidad × precio.
- **Pendiente de hardware**: esta guía valida software y G-Code; la impresión física (calibración XYZ, repetibilidad G28) sigue el protocolo de 3 fases del proyecto (cubo 20 mm → regla → ficha Braille).

## 7. Si algo falla

1. Revisar `docker compose ps` (ambos contenedores Up) y `docker compose logs app --tail 50`.
2. Forzar datos de prueba: `docker exec -it laravel_app php artisan migrate:fresh --seed`.
3. Errores de sesión/APP_KEY: `.env` debe tener `APP_KEY` y `DB_HOST=db` (en Docker).
4. Reportar el fallo con el paso de la checklist donde ocurrió.
