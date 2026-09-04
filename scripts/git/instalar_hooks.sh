#!/usr/bin/env bash
# =============================================================================
#  Script: instalar_hooks.sh
#  Instala el hook de commit-msg y la plantilla .gitmessage en el repositorio local
# =============================================================================
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

echo "==> Configurando gobernanza Git en: $REPO_ROOT"

if [ ! -d "$REPO_ROOT/.git" ]; then
    echo "✗ Error: No se encontró el directorio .git en $REPO_ROOT"
    exit 1
fi

HOOKS_DIR="$REPO_ROOT/.git/hooks"
mkdir -p "$HOOKS_DIR"

# 1. Instalar hook commit-msg
cp "$REPO_ROOT/scripts/git/commit-msg" "$HOOKS_DIR/commit-msg"
chmod +x "$HOOKS_DIR/commit-msg"
echo "✔ Hook commit-msg instalado y ejecutable en .git/hooks/commit-msg"

# 2. Configurar plantilla .gitmessage
if [ -f "$REPO_ROOT/.gitmessage" ]; then
    git -C "$REPO_ROOT" config commit.template .gitmessage
    echo "✔ Plantilla .gitmessage configurada como commit.template"
fi

echo "✔ Gobernanza Git instalada exitosamente."
echo "  Todos los commits ahora validarán formato semántico y prevención de secretos."
