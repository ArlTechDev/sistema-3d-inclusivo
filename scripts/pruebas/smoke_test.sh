#!/usr/bin/env bash
# =============================================================================
#  Sistema Inclusivo — Smoke test de la versión final
# =============================================================================
#  Verifica con curl el estado HTTP de las rutas clave y el flujo de roles.
#
#  Uso:  bash smoke_test.sh [-u http://localhost:8000]
#  Requiere: curl, y la app corriendo (docker compose up + artisan serve).
#  Salida: PASS/FAIL por ítem y resumen; exit 0 si todo pasa.
# =============================================================================
set -uo pipefail

BASE="http://localhost:8000"
while [ $# -gt 0 ]; do
    case "$1" in
        -u|--url) BASE="${2:-$BASE}"; shift 2 ;;
        *) echo "Uso: $0 [-u http://localhost:8000]"; exit 1 ;;
    esac
done

JAR="$(mktemp)"
PASS=0; FAIL=0; FALLIDOS=()

ADMIN_MAIL="admin@admin.com";    ADMIN_PASS="admin123"
SOLIC_MAIL="docente@test.com";   SOLIC_PASS="12345678"

echo "════════════════════════════════════════════════════════"
echo "  Smoke test → $BASE"
echo "════════════════════════════════════════════════════════"

# --- helpers ----------------------------------------------------------------
code() { # code <url> [metodo] [data...]  → imprime código HTTP usando el jar
    local url="$1"; shift
    local metodo="${1:-GET}"
    [ $# -gt 0 ] && shift
    curl -s -o /dev/null -w "%{http_code}" -b "$JAR" -c "$JAR" \
        -X "$metodo" "$BASE$url" "$@"
}

check() { # check <nombre> <esperado> <obtenido>
    local nombre="$1" esperado="$2" obtenido="$3"
    if [ "$esperado" = "$obtenido" ]; then
        PASS=$((PASS+1)); printf "  ✅ %-46s %s\n" "$nombre" "$obtenido"
    else
        FAIL=$((FAIL+1)); FALLIDOS+=("$nombre")
        printf "  ❌ %-46s esperado %s, obtenido %s\n" "$nombre" "$esperado" "$obtenido"
    fi
}

login() { # login <email> <password>  → hace el baile CSRF y deja la sesión en $JAR
    local email="$1" password="$2"
    rm -f "$JAR"
    local html token
    html="$(curl -s -b "$JAR" -c "$JAR" "$BASE/login")"
    token="$(printf '%s' "$html" | grep -oP 'name="_token" value="\K[^"]+' | head -1)"
    [ -n "$token" ] || { echo "    ⚠ no se pudo extraer _token"; return 1; }
    curl -s -o /dev/null -b "$JAR" -c "$JAR" -X POST "$BASE/login" \
        --data-urlencode "_token=$token" \
        --data-urlencode "email=$email" \
        --data-urlencode "password=$password"
}

logout() {
    local html token
    html="$(curl -s -b "$JAR" -c "$JAR" "$BASE/recursos")"
    token="$(printf '%s' "$html" | grep -oP 'name="_token" value="\K[^"]+' | head -1)"
    if [ -n "$token" ]; then
        curl -s -o /dev/null -b "$JAR" -c "$JAR" -X POST "$BASE/logout" \
            --data-urlencode "_token=$token"
    else
        rm -f "$JAR"
    fi
}

# --- 1. Sin sesión ----------------------------------------------------------
echo "── Sin sesión"
check "GET /login → 200"            200 "$(code /login)"
check "GET / → 302 (a /login)"      302 "$(code /)"
check "GET /recursos → 302"         302 "$(code /recursos)"
check "GET /recursos/exportar/excel → 302" 302 "$(code /recursos/exportar/excel)"

# --- 2. Login Administrador ------------------------------------------------
echo "── Administrador"
login "$ADMIN_MAIL" "$ADMIN_PASS"
check "GET /recursos → 200 (tabla)" 200 "$(code /recursos)"
check "GET /pedidos → 200"          200 "$(code /pedidos)"
check "GET /usuarios → 200"         200 "$(code /usuarios)"
check "GET /instituciones → 200"    200 "$(code /instituciones)"
check "GET /recursos/exportar/excel → 200" 200 "$(code /recursos/exportar/excel)"
check "GET /pedidos/exportar/excel → 200"  200 "$(code /pedidos/exportar/excel)"
check "GET /recursos/papelera → 200" 200 "$(code /recursos/papelera)"

# --- 3. Solicitante ---------------------------------------------------------
logout
echo "── Solicitante"
login "$SOLIC_MAIL" "$SOLIC_PASS"
check "GET /recursos → 200 (catálogo)"  200 "$(code /recursos)"
check "GET /recursos/exportar/excel → 403" 403 "$(code /recursos/exportar/excel)"
check "GET /recursos/exportar/pdf → 403"    403 "$(code /recursos/exportar/pdf)"
check "GET /pedidos → 403"                  403 "$(code /pedidos)"
check "GET /usuarios → 403"                 403 "$(code /usuarios)"
check "GET /instituciones → 403"            403 "$(code /instituciones)"
check "GET /recursos/papelera → 403"        403 "$(code /recursos/papelera)"
# G-Code de un recurso: 403 por rol (o 404 si no existe el id; nunca 200)
GCODE="$(code /recursos/gcode/1)"
if [ "$GCODE" = "200" ]; then
    FAIL=$((FAIL+1)); FALLIDOS+=("GET /recursos/gcode/1 (solicitante) → nunca 200")
    printf "  ❌ %-46s obtenido %s\n" "GET /recursos/gcode/1 (solicitante) → nunca 200" "$GCODE"
else
    PASS=$((PASS+1))
    printf "  ✅ %-46s %s\n" "GET /recursos/gcode/1 (solicitante) → nunca 200" "$GCODE"
fi

# --- 4. Fuerza bruta (email dedicado para no bloquear cuentas reales) ------
echo "── Fuerza bruta (throttle:login, 5/min)"
rm -f "$JAR"
html="$(curl -s -b "$JAR" -c "$JAR" "$BASE/login")"
token="$(printf '%s' "$html" | grep -oP 'name="_token" value="\K[^"]+' | head -1)"
ULTIMO=""
for i in 1 2 3 4 5 6; do
    ULTIMO="$(curl -s -o /dev/null -w "%{http_code}" -b "$JAR" -c "$JAR" -X POST "$BASE/login" \
        --data-urlencode "_token=$token" \
        --data-urlencode "email=brute@test.com" \
        --data-urlencode "password=incorrecta")"
done
check "6º intento fallido → 429" 429 "$ULTIMO"

# --- Resumen ----------------------------------------------------------------
rm -f "$JAR"
echo "════════════════════════════════════════════════════════"
echo "  Resultado: $PASS PASS · $FAIL FAIL"
if [ "$FAIL" -gt 0 ]; then
    printf "  Fallidos:\n"
    for f in "${FALLIDOS[@]}"; do printf "    - %s\n" "$f"; done
    exit 1
fi
echo "  ✅ Smoke test superado"
echo "════════════════════════════════════════════════════════"
