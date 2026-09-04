# Checklist de Usabilidad y Accesibilidad UI/UX (Vistas Blade)

**Responsable:** Jose Matias Aramayo Eguino (Frontend & Accesibilidad)  

---

- [x] **Contraste de color:** Fondo y texto cumplen ratio mínimo 4.5:1 (WCAG AA) y 7:1 (WCAG AAA).
- [x] **Salto al contenido principal:** Presente en `layouts/app.blade.php` y `layouts/admin.blade.php`.
- [x] **Atributos de estado:** `aria-invalid`, `aria-describedby` e `id` vinculados en todos los formularios.
- [x] **Mensajes de notificación:** Todos los bloques `.alert` cuentan con `role="alert"`.
- [x] **Modales y Diálogos:** Incorporan `role="dialog"`, `aria-modal="true"` y botón de cierre accesible.
- [x] **Iconografía:** Elementos visuales decorativos marcados con `aria-hidden="true"`.
- [x] **Enlaces externos:** Enlaces que abren en nueva pestaña incluyen `rel="noopener noreferrer"` y advertencia accesible.
- [x] **Formularios de Administración:** Tablas con `scope="col"` en encabezados y botones de acción con `aria-label`.
