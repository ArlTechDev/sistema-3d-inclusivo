# 12 — Guía de Migración Docker (offline, Arch → Windows/Linux)

Guía para mover el proyecto **Sistema Web + Impresora 3D** a otra PC con Docker **sin conexión a internet**. Pensada para el caso real: desarrollo en **Arch Linux** y despliegue/compañeros en **Windows** (Docker Desktop con backend WSL2).

Acompañan a esta guía los scripts en `scripts/docker/`:

| Script | Dónde se ejecuta | Qué hace |
|---|---|---|
| `exportar_proyecto.sh` | PC origen (Arch/Linux) | Empaqueta imágenes + BD + proyecto en `scripts/docker/salida/` |
| `importar_windows.ps1` | PC destino (Windows) | Carga imágenes, restaura BD, extrae proyecto, levanta el entorno |
| `importar_linux.sh` | PC destino (Linux) | Ídem, versión bash |

---

## 1. Qué viaja y qué no

| Elemento | ¿Viaja? | Cómo |
|---|---|---|
| Imagen de la app (`laravel_web-app:latest`) | ✅ | `docker save` → `imagenes.tar` |
| Imagen `mysql:8.0` | ✅ | ídem |
| Imágenes base `php:8.4-cli` y `composer:latest` | ✅ | ídem (permiten reconstruir offline con `docker compose build`) |
| Base de datos (volumen `laravel_web_db_data`) | ✅ | snapshot tar → `db_data.tar.gz` |
| Proyecto (`software/laravel_web/`) | ✅ | tar.gz con **`vendor/`, `node_modules/` y `.env`** incluidos |
| `storage/app/public` (subidas: imágenes de recursos) | ✅ | dentro del tar del proyecto (no se excluye) |
| `.git/`, logs, caché de Laravel, sqlite local | ❌ | excluidos a propósito |

> **¿Por qué viajan `vendor/` y `node_modules/`?** El `docker-compose.yml` monta `./:/var/www` (bind mount), así que las dependencias viven **en el host**, no dentro de la imagen. Verificado: este proyecto no usa módulos nativos (`binding.gyp`/`.so` ausentes), por lo que son **100% portables** entre Linux y Windows sin reinstalar.

## 2. Requisitos en la PC destino

- **Docker Desktop** (Windows) con backend **WSL2**, arrancado y en «Engine running» (Linux: Docker Engine).
- Espacio libre: **~6 GB** (imágenes ~2,4 GB + proyecto ~1–2 GB + copia extraída).
- Puertos libres: **8000** (app), **5173** (Vite), **3307** (MySQL host).
- (Windows) Si el firewall pregunta, permitir el acceso de Docker a la red privada.

## 3. Procedimiento

### Paso 1 — Origen (Arch Linux)

```bash
cd <ruta>/sistema_inclusivo
bash scripts/docker/exportar_proyecto.sh
```

Genera `scripts/docker/salida/` con:

```
imagenes.tar                 (~2,4 GB — puede tardar varios minutos)
db_data.tar.gz               (datos de MySQL)
proyecto_laravel_web.tar.gz  (proyecto con vendor, node_modules y .env)
volumen.txt                  (nombre del volumen exportado)
```

> El script detiene `laravel_db` unos segundos para un snapshot consistente y lo reinicia solo. Al final muestra los **SHA-256** de cada artefacto: anótalos o guarda el `salida/` intacto.

### Paso 2 — Transferencia

Copia la carpeta `salida/` completa a la otra PC (USB, disco de red, etc.). El tamaño total ronda **3,5–4,5 GB**.

### Paso 3 — Destino (Windows)

1. Abre **PowerShell** (puede ser PowerShell 5.1 o 7).
2. Crea la carpeta donde vivirá el proyecto, por ejemplo `C:\proyectos\sistema_inclusivo`, y copia `salida/` dentro.
3. Ejecuta:

```powershell
cd C:\proyectos\sistema_inclusivo
.\scripts\docker\importar_windows.ps1 -Directorio "C:\proyectos\sistema_inclusivo"
```

O si no quieres copiar `salida/` adentro (solo los 3 artefactos), indica la ruta donde estén:

```powershell
.\scripts\docker\importar_windows.ps1 -Directorio "D:\paquete_migracion"
```

El script: carga las imágenes → crea y restaura el volumen de BD → extrae el proyecto en `.\laravel_web\` → `docker compose up -d --no-build` → verifica `http://localhost:8000`.

### Paso 4 — Destino (Linux, alternativa)

```bash
cd <donde esté el paquete>
bash scripts/docker/importar_linux.sh
```

### Paso 5 — Verificación

- `http://localhost:8000` responde (login del sistema).
- MySQL: `localhost:3307`, usuario `admin`, password `password` (definidos en `docker-compose.yml`).
- Los datos migrados están: recursos, instituciones y pedidos existentes.

---

## 4. Sin los scripts (manual, referencia)

```bash
# Origen
docker save -o imagenes.tar laravel_web-app:latest mysql:8.0 php:8.4-cli composer:latest
docker stop laravel_db   # snapshot consistente
docker run --rm -v laravel_web_db_data:/data -v "$(pwd)":/backup \
    laravel_web-app:latest tar czf /backup/db_data.tar.gz -C /data .
docker start laravel_db
tar czf proyecto.tar.gz -C software/laravel_web \
    --exclude='./storage/logs' --exclude='./database/*.sqlite*' .

# Destino (Windows, desde la carpeta del paquete)
docker load -i imagenes.tar
docker volume create laravel_web_db_data
docker run --rm -v laravel_web_db_data:/data -v "$pwd\salida:/backup" \
    laravel_web-app:latest tar xzf /backup/db_data.tar.gz -C /data
tar -xzf salida/proyecto_laravel_web.tar.gz
cd laravel_web && docker compose up -d --no-build
```

## 5. Troubleshooting

| Síntoma | Causa y solución |
|---|---|
| `docker compose up` intenta *pull* o *build* | Las imágenes no están cargadas o el tag no coincide. Verificar `docker images` y que `docker-compose.yml` tenga `image: laravel_web-app:latest` (ya fijado). Nunca usar `--build` sin red. |
| `unknown volume laravel_web_db_data` | El `name:` del volumen en `docker-compose.yml` ya lo fija; si usas un compose viejo sin esa línea, el nombre depende de la carpeta. |
| La app responde pero sin datos | El volumen no se restauró o se reemplazó por uno vacío; reimportar `db_data.tar.gz` o hacer `docker compose exec app php artisan migrate --seed`. |
| `curl localhost:8000` no responde | El contenedor `laravel_app` arranca con `tail -f /dev/null`; la app se sirve con `docker compose exec app php artisan serve --host=0.0.0.0 --port=8000` (o `composer dev`). Revisar `docker compose ps`. |
| Scripts `.sh` con error `bad interpreter` en Linux | Líneas CRLF: convertir con `sed -i 's/\r$//' script.sh` o `dos2unix`. |
| Vite/HMR lento en Windows | Bind mount a través de WSL2; normal. Para producción usar `npm run build` (los assets ya están en `public/build`). |
| `APP_KEY` / error de sesión | El `.env` viaja en el tar; si se pierde, copiar `.env.example` y ejecutar `php artisan key:generate`. En Docker, `DB_HOST=db` (nombre del servicio). |
| Firewall de Windows bloquea 8000 | Permitir Docker en red privada o probar `http://localhost:8000` con Docker Desktop en WSL2 (suele funcionar sin cambios). |
| MySQL da error de versión al restaurar | Misma imagen `mysql:8.0` en ambos lados → no debería pasar. De respaldo existe la opción `mysqldump`: `docker compose exec -T db mysqldump -uadmin -ppassword sistema_inclusivo > dump.sql`. |

## 6. Notas de diseño (por qué funciona)

Los consejos habituales de migración offline aplicados a este proyecto, con un matiz que se corrigió en el compose:

1. **Tags exactos** — el `docker-compose.yml` ahora declara `image: laravel_web-app:latest` en el servicio `app`. Antes el nombre se derivaba de la carpeta (`<carpeta>-app`): al copiar el proyecto a una carpeta con otro nombre, Compose buscaba una imagen inexistente y, sin red, fallaba. Con el tag fijo, la carpeta destino puede llamarse como quieras.
2. **Volúmenes aparte** — `db_data` es un *named volume* y no viaja con la imagen; por eso se exporta con tar. Su `name:` también quedó fijo (`laravel_web_db_data`) para que la restauración no dependa del nombre de carpeta.
3. **vendor/ y node_modules/** — como son bind mount (no `COPY` en la imagen), viajan con la carpeta del proyecto. Verificado sin módulos nativos → portables.
4. **.env** — gitignored y fuera de la imagen; viaja dentro de `proyecto_laravel_web.tar.gz`.
5. **Sin `busybox`** — los scripts reutilizan la imagen `laravel_web-app` (trae `tar`) para exportar/importar el volumen, evitando una imagen extra que habría que descargar en el destino offline.
6. **`--no-build`** — los importadores usan `docker compose up -d --no-build` para garantizar que nada intente tocar la red.

## 7. Alternativa sin datos

Si en la PC destino no necesitas los datos actuales (solo el código + entorno):

1. Exporta solo imágenes y proyecto (omite `db_data.tar.gz`).
2. En destino: `docker compose up -d --no-build` y luego
   `docker compose exec app php artisan migrate --seed`.

Queda documentado como opción; por defecto el script exporta los datos.
