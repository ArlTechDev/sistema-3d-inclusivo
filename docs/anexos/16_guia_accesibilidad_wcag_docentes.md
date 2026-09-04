# Anexo 16: Guía de Pautas de Accesibilidad Web WCAG 2.1 Nivel AAA para Educadores Inclusivos

**Proyecto:** Sistema Braille Inclusivo Táctil3D  
**Autor:** Jose Matias Aramayo Eguino (Software Frontend & UI/UX)  

---

## 1. Principios WCAG Implementados

### 1.1 Perceptible
- **Contraste de Color:** Relación de contraste mínima de 7:1 en textos principales y 4.5:1 en elementos interactivos, superando el nivel AA.
- **Alternativas Textuales:** Todas las figuras e imágenes cuentan con atributo `alt` enriquecido que describe su función didáctica táctil.
- **Iconografía Decorativa:** Los iconos FontAwesome y emojis decorativos incorporan `aria-hidden="true"` para evitar ruido en lectores de voz.

### 1.2 Operable
- **Navegación por Teclado:** Toda la interfaz es 100% operable mediante la tecla `Tab`, `Shift+Tab`, `Enter` y `Espacio`.
- **Salto al Contenido:** Enlaces de salto directo (`.salto`) en layouts público y administrativo para omitir barras de navegación repetitivas.
- **Sin Límite de Tiempo Compulsivo:** Las sesiones no caducan intempestivamente durante el ingreso de texto Braille.

### 1.3 Comprensible
- **Identificación de Idioma:** Cabecera HTML con `lang="es"`.
- **Asociación de Formularios:** Cada control de entrada posee su `<label for="...">` unívoco y mensajes de validación asociados mediante `aria-describedby`.

### 1.4 Robusto
- **Compatibilidad de Navegadores:** HTML5 semántico puro sin dependencias complejas de frameworks SPA, garantizando compatibilidad con lectores NVDA, JAWS y TalkBack.
