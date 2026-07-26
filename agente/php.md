# PHP (Versión ^8.2)

## 1. Ficha Técnica
- **Nombre:** PHP
- **Versión:** 8.2+
- **Rol:** Lenguaje de programación principal (Runtime).
- **Documentación Oficial:** https://www.php.net/docs.php

## 2. Patrones de Diseño y Estándares Profesionales
- **Sintaxis Moderna:** Uso estricto de tipado (`declare(strict_types=1);`), propiedades tipadas, union types (`int|string`), y tipos intersección.
- **Estructura:** Clases en `app/`, Traits en `app/Traits`, Interfaces en `app/Interfaces` o junto a su implementación si es única.
- **Constructor Promocionado:** Preferir la promoción de propiedades en el constructor para inyección de dependencias.
- **Enums:** Utilizar `enum` nativo de PHP 8.2 para estados fijos (ej. `Status::Active`, `Status::Inactive`) en lugar de constantes mágicas.

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Seguridad:** Nunca confiar en inputs del usuario. Sanitizar antes de validar. Usar `htmlspecialchars()` al renderizar datos no confiables en vistas crudas.
- **Rendimiento:** Evitar N+1 queries usando Eager Loading (`with()`) en Eloquent.
- **Manejo de Errores:** Usar excepciones específicas, no capturar `Exception` genérica sin loguear o relanzar.
- **Inmutabilidad:** Preferir objetos de valor inmutables para DTOs.

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Uso de variables superglobales `$_GET`, `$_POST` directamente (usar `Request` de Laravel).
- ❌ Silenciar errores con `@`.
- ❌ Lógica de negocio en Controladores (debe estar en Services, Actions o Repositorios).
- ❌ Concatenación manual de SQL (riesgo de inyección).

## 5. Checklist de Calidad
- [ ] ¿Se declaró `strict_types=1`?
- [ ] ¿Todos los argumentos y retornos de funciones están tipados?
- [ ] ¿Se evitó la lógica de negocio en el controlador?
- [ ] ¿Se manejan las excepciones de forma adecuada?