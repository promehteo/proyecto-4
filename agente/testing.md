# Testing (Pest PHP / PHPUnit)

## 1. Ficha Técnica
- **Nombre:** Pest PHP
- **Versión:** ^3.8
- **Rol:** Framework de pruebas automatizadas.
- **Documentación Oficial:** https://pestphp.com/docs

## 2. Patrones de Diseño y Estándares Profesionales
- **Sintaxis:** Usar sintaxis funcional de Pest (`test('description', function () {})`).
- **Estructura:** Tests de Feature en `tests/Feature`, Tests de Unidad en `tests/Unit`.
- **Factory:** Usar Model Factories para generar datos de prueba, nunca hardcodear IDs.

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Aislamiento:** Cada test debe ser independiente y poder correr en cualquier orden.
- **Base de Datos:** Usar `RefreshDatabase` o transacciones que se revierten al finalizar el test.
- **Cobertura:** Priorizar tests de flujos críticos (Login, Creación de pedidos, Cambios de status).

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Tests que dependen del orden de ejecución.
- ❌ Probar implementación interna en lugar de comportamiento público.
- ❌ Ignorar tests fallidos ("flaky tests").

## 5. Checklist de Calidad
- [ ] ¿El test describe claramente qué comportamiento valida?
- [ ] ¿Usa factories en lugar de datos estáticos?
- [ ] ¿Limpia su propio estado después de ejecutarse?
- [ ] ¿Valida tanto el camino feliz como los casos de error?