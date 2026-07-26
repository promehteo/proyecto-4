# Laravel (Versión ^12.0)

## 1. Ficha Técnica
- **Nombre:** Laravel Framework
- **Versión:** 12.x
- **Rol:** Framework Backend Principal.
- **Documentación Oficial:** https://laravel.com/docs/12.x

## 2. Patrones de Diseño y Estándares Profesionales
- **Arquitectura:** Controladores delgados -> FormRequests -> Repositorios/Services -> Modelos.
- **Estructura de Carpetas:**
  - `app/Http/Controllers`: Solo orquestación y respuesta HTTP.
  - `app/Http/Requests`: Validaciones.
  - `app/Policies`: Autorización.
  - `app/Repositories`: Acceso a datos (espejo de controllers según regla de negocio).
- **Colecciones:** Usar API Resources o Collections para transformar datos.

## 3. Buenas Prácticas de Rendimiento y Seguridad (Guardrails)
- **Regla Cero del Proyecto:** PROHIBIDA la eliminación real (`delete()`, `forceDelete()`). Usar `status` (1=activo, 2=inactivo).
- **Autorización:** Siempre usar `$this->authorize()` o Gates/Policies antes de cualquier acción de escritura.
- **Validación:** Toda validación debe estar en clases `FormRequest`, nunca en el controller.
- **Auditoría:** No usar `timestamps` (created_at/updated_at) en tablas de negocio. La auditoría va por bitácora externa.
- **Nombres de Tablas:** Singular (`producto`, `categoria`). PK: `id_<tabla>`.

## 4. Antipatrones y Errores Comunes a Evitar
- ❌ Consultas Eloquent directas en Controladores (`Product::all()`).
- ❌ Uso de Livewire (Prohibido por regla de negocio, usar Blade + Alpine).
- ❌ Hardcodear IDs o statuses mágicos (usar Enums o constantes).
- ❌ Modificar `.gitignore` (Regla Cero absoluta).

## 5. Checklist de Calidad
- [ ] ¿La validación está en un FormRequest?
- [ ] ¿La autorización está en una Policy?
- [ ] ¿Se respetó la prohibición de `delete()` físico?
- [ ] ¿El controlador solo llama al repositorio/service?
- [ ] ¿Se verificó que `.gitignore` no fue modificado?