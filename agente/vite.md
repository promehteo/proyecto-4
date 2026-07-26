# Vite (Build Tool)

## 1. Ficha Técnica
- **Nombre:** Vite
- **Versión:** ^7.0.7
- **Rol:** Empaquetador y servidor de desarrollo.
- **Documentación Oficial:** https://vite.dev/docs/

## 2. Patrones de Diseño y Estándares Profesionales
- **Entradas:** Definir puntos de entrada claros en `vite.config.js` (ej. `resources/js/app.js`, `resources/css/app.css`).
- **Assets:** Importar imágenes y fuentes directamente en JS/CSS para que Vite las procese.

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Producción:** Ejecutar `npm run build` antes de desplegar. No subir `node_modules`.
- **Versionado:** Usar las directivas de Blade `@vite` para manejar el cache busting automático.

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Referenciar assets estáticos con rutas hardcodeadas en lugar de usar helpers de Vite.
- ❌ Committear la carpeta `build/` o `dist/` (debe estar en `.gitignore`).

## 5. Checklist de Calidad
- [ ] ¿Los assets compilan sin errores?
- [ ] ¿Se usaron las directivas `@vite` en los layouts?
- [ ] ¿El build de producción genera archivos optimizados?