# Tailwind CSS (Versión ^4.0.0 / Plugin ^3.x)

## 1. Ficha Técnica
- **Nombre:** Tailwind CSS
- **Versión:** 4.x (Core), 3.x (Plugins)
- **Rol:** Framework CSS Utility-first.
- **Documentación Oficial:** https://tailwindcss.com/docs

## 2. Patrones de Diseño y Estándares Profesionales
- **Diseño:** Mobile-first (`md:`, `lg:`).
- **Colores:** Usar la paleta definida en `tailwind.config.js` (slate, indigo, etc.). No usar colores hex arbitrarios salvo excepción justificada.
- **Componentes:** Extraer patrones repetitivos a componentes Blade o directivas `@apply` (con moderación).

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Purge:** Asegurar que todas las clases dinámicas estén completas en el HTML para que el purgado funcione (`bg-{{ $color }}-500` es peligroso, mejor `:class`).
- **Accesibilidad:** Usar clases `sr-only` para textos destinados a lectores de pantalla.
- **Dark Mode:** Implementar siempre variantes `dark:` si el proyecto soporta modo oscuro.

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Crear archivos `.css` gigantes con lógica personalizada en lugar de usar utilidades.
- ❌ Usar `!important` arbitrario para sobrescribir estilos.
- ❌ Ignorar la accesibilidad (contraste, focus rings).

## 5. Checklist de Calidad
- [ ] ¿Se usaron utilidades estándar en lugar de CSS custom?
- [ ] ¿El diseño es responsivo (mobile-first)?
- [ ] ¿Se consideran los estados `hover`, `focus`, `active` y `dark`?