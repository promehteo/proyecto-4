# Bases de Datos (MySQL / SQLite)

## 1. Ficha Técnica
- **Nombre:** MySQL / MariaDB (Prod), SQLite (Dev/Test)
- **Rol:** Persistencia de datos.
- **Documentación Oficial:** https://dev.mysql.com/doc/ | https://www.sqlite.org/docs.html

## 2. Patrones de Diseño y Estándares Profesionales
- **Migraciones:** Cada cambio de esquema debe tener su migración. Nunca editar migraciones antiguas ya desplegadas.
- **Naming:** Tablas en singular (regla de negocio específica del proyecto). Columnas con sufijo de tabla para FKs (`id_categoria`).
- **Índices:** Indexar siempre columnas usadas en `WHERE`, `JOIN` y `ORDER BY`.

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Soft Delete Custom:** Implementar "borrado lógico" mediante columna `status` (1=Activo, 2=Inactivo). Scopes globales para filtrar `status=1`.
- **Transacciones:** Usar `DB::transaction()` para operaciones que afectan múltiples tablas.
- **Datos Sensibles:** Nunca guardar contraseñas en texto plano (usar `bcrypt` o `argon2`).

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Eliminar registros físicamente (`DELETE FROM`).
- ❌ Usar `created_at`/`updated_at` en tablas de negocio (usar bitácora externa).
- ❌ Consultas `SELECT *` innecesarias.

## 5. Checklist de Calidad
- [ ] ¿La migración es reversible (`down()` method)?
- [ ] ¿Se respeta la convención de nombres (singular, sufijos)?
- [ ] ¿Se implementó el soft delete vía `status`?
- [ ] ¿Existen índices adecuados para las consultas previstas?