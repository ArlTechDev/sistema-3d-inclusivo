# Anexo 17: Registro de Pruebas con Lectores de Pantalla (NVDA y TalkBack)

**Proyecto:** Sistema Braille Inclusivo Táctil3D  
**Evaluador:** Jose Matias Aramayo Eguino  
**Fecha de Validación:** Septiembre 2026  

---

## 1. Entornos de Prueba
| Herramienta | Versión | Navegador | Sistema Operativo |
|---|---|---|---|
| **NVDA** | 2024.2 | Mozilla Firefox 128 ESR | Windows 11 / Linux |
| **Google TalkBack** | 14.1 | Chrome Mobile 126 | Android 14 |
| **Navegación por Teclado** | N/A | Chromium 128 | Linux Arch / Ubuntu |

---

## 2. Casos de Prueba Ejecutados

### Caso 1: Inicio de Sesión y Registro
- **Acción:** Acceso a formulario con `Tab` e ingreso de credenciales erróneas.
- **Resultado esperado:** El lector debe anunciar inmediatamente el campo con error y leer el texto de alerta.
- **Resultado obtenido:** `Conforme`. NVDA anuncia: *"Correo Electrónico, edición, requerido, no válido: Estas credenciales no coinciden con nuestros registros"*.

### Caso 2: Catálogo de Recursos Táctiles
- **Acción:** Filtrado de piezas por categoría y selección de ficha Braille.
- **Resultado esperado:** Anuncio del cambio de lista sin recargar la página bruscamente.
- **Resultado obtenido:** `Conforme`. `aria-live="polite"` permite lectura fluida.

### Caso 3: Solicitud con Texto Personalizado
- **Acción:** Ingreso de texto para generación de placa en relieve.
- **Resultado esperado:** Anuncio del contador de caracteres disponibles y costo estimado.
- **Resultado obtenido:** `Conforme`. El lector verbaliza actualización de costo en Bolivianos.
