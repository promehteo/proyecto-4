# Alpine.js (Versión ^3.4.2)

## 1. Ficha Técnica
- **Nombre:** Alpine.js
- **Versión:** 3.x
- **Rol:** Framework Frontend reactivo ligero (interacciones UI).
- **Documentación Oficial:** https://alpinejs.dev/

## 2. Patrones de Diseño y Estándares Profesionales
- **Alcance:** Usar `x-data` lo más cerca posible del elemento que interactúa.
- **Componentes:** Para lógica compleja, extraer a funciones globales o módulos JS importados, no acumular cientos de líneas en el HTML.
- **Directivas Comunes:** `x-show`, `x-bind` (`:`), `x-on` (`@`), `x-model`, `x-transition`.

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Seguridad:** Nunca confiar en lógica de negocio dentro de Alpine. Es solo vista. Validar siempre en backend.
- **Rendimiento:** Usar `x-show` para toggle visual rápido, `x-if` si la creación del DOM es costosa.
- **Eventos:** Usar delegación de eventos cuando sea posible.

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Llamar APIs complejas directamente desde `x-init` sin manejo de carga/error adecuado.
- ❌ Duplicar lógica de validación que ya existe en el backend.
- ❌ Manipular el DOM directamente con `document.querySelector` (usar referencias de Alpine `$refs`).

## 5. Checklist de Calidad
- [ ] ¿La lógica es puramente visual/interactiva?
- [ ] ¿Se evitó exponer datos sensibles en el estado reactivo?
- [ ] ¿Las transiciones son suaves y no bloquean el hilo principal?