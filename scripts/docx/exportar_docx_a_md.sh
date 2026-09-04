#!/usr/bin/env bash
# =============================================================================
#  Sistema Inclusivo — Exportación de Documento Maestro DOCX a Espejo Markdown
# =============================================================================
#  Convierte DocumentoFinalPSCP3DAgosto17.docx a Markdown GitHub Flavored (GFM)
#  con extracción de media y cabecera de advertencia SSOT.
# =============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"

DOCX_PATH="$REPO_ROOT/docs/documento_pscp/DocumentoFinalPSCP3DAgosto17.docx"
OUTPUT_DIR="$REPO_ROOT/docs/documento_pscp"
OUTPUT_MD="$OUTPUT_DIR/DocumentoFinalPSCP3DAgosto17.md"
SYMLINK_MD="$OUTPUT_DIR/DocumentoFinal.md"
TEMP_MD="/tmp/docx_export_$$.md"

echo "══════════════════════════════════════════════════════════════════════"
echo "  Exportación de Documento Maestro a Markdown (Espejo Canónico)"
echo "══════════════════════════════════════════════════════════════════════"

# 1. Validaciones
command -v pandoc >/dev/null 2>&1 || { echo "✗ Error: pandoc no está instalado."; exit 1; }
[ -f "$DOCX_PATH" ] || { echo "✗ Error: No se encontró el archivo $DOCX_PATH"; exit 1; }

echo "→ Origen:  $DOCX_PATH"
echo "→ Destino: $OUTPUT_MD"

# 2. Conversión con Pandoc (desde OUTPUT_DIR para generar rutas relativas ./media)
echo "→ Exportando con Pandoc (GFM, sin hard wraps, rutas relativas para media)…"
(
    cd "$OUTPUT_DIR"
    pandoc "$(basename "$DOCX_PATH")" \
        -f docx \
        -t gfm \
        --wrap=none \
        --extract-media=. \
        -o "$TEMP_MD"
)

# 3. Inyección de Cabecera de Advertencia SSOT
echo "→ Inyectando cabecera de advertencia y trazabilidad…"
FECHA_SYNC=$(date -u +"%Y-%m-%d %H:%M:%S UTC")

cat <<EOF > "$OUTPUT_MD"
<!--
════════════════════════════════════════════════════════════════════════════════
  SISTEMA BRAILLE INCLUSIVO — ESPEJO DE CONSULTA EN TEXTO PLANO (MARKDOWN)
════════════════════════════════════════════════════════════════════════════════
  AVISO IMPORTANTE (SSOT):
  Este archivo es un ESPEJO DE SOLO LECTURA generado automáticamente a partir de:
  docs/documento_pscp/DocumentoFinalPSCP3DAgosto17.docx

  La ÚNICA FUENTE DE LA VERDAD (SSOT) es el archivo .docx maestro.
  NO modifiques este archivo .md directamente.
  Toda edición debe realizarse sobre el archivo .docx y sincronizarse con:
    bash scripts/docx/exportar_docx_a_md.sh

  Última sincronización: $FECHA_SYNC
════════════════════════════════════════════════════════════════════════════════
-->

EOF

cat "$TEMP_MD" >> "$OUTPUT_MD"
rm -f "$TEMP_MD"

# 4. Enlace Simbólico Canónico
echo "→ Creando/actualizando enlace simbólico canónico DocumentoFinal.md…"
ln -sf "DocumentoFinalPSCP3DAgosto17.md" "$SYMLINK_MD"

# 5. Métricas y Validación
LINEAS=$(wc -l < "$OUTPUT_MD")
PALABRAS=$(wc -w < "$OUTPUT_MD")
BYTES=$(wc -c < "$OUTPUT_MD")

echo ""
echo "✅ Exportación completada con éxito:"
echo "   - Líneas:   $LINEAS"
echo "   - Palabras: $PALABRAS"
echo "   - Tamaño:   $((BYTES / 1024)) KB"
echo "   - Symlink:  $SYMLINK_MD -> DocumentoFinalPSCP3DAgosto17.md"
echo "══════════════════════════════════════════════════════════════════════"
