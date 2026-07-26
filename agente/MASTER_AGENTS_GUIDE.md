# 🤖 Manual de Operaciones para Agentes de IA - La Casa El Rapidito

## 1. Propósito del Archivo
Este documento actúa como el "cerebro" de orquestación para cualquier Agente de IA trabajando en este repositorio. Define qué guías técnicas consultar, cómo priorizar reglas y asegura la coherencia arquitectónica del proyecto "La Casa El Rapidito".

**Objetivo Principal:** Garantizar que todo código generado cumpla con la **Regla Cero**, las **Prohibiciones Críticas** y los estándares de las tecnologías detectadas (Laravel 12, PHP 8.2, Blade+Alpine).

---

## 2. Mapeo de Rutas y Contexto (Routing Rules)

Antes de modificar cualquier archivo, el Agente DEBE identificar el tipo de archivo y cargar las siguientes guías de la carpeta `skills/`:

| Si editas/archivas en... | Tipo de Archivo | Guías Obligatorias a Leer | Guías Complementarias |
| :--- | :--- | :--- | :--- |
| `app/Http/Controllers/*.php` | Controlador | `skills/laravel.md`, `skills/php.md` | `skills/database.md` |
| `app/Http/Requests/*.php` | Validación | `skills/laravel.md`, `skills/php.md` | - |
| `app/Models/*.php` | Modelo Eloquent | `skills/laravel.md`, `skills/database.md` | `skills/php.md` |
| `app/Repositories/*.php` | Repositorio | `skills/laravel.md`, `skills/database.md` | `skills/php.md` |
| `resources/views/**/*.blade.php` | Vista Blade | `skills/laravel.md`, `skills/alpinejs.md` | `skills/tailwindcss.md` |
| `resources/js/**/*.js` | Script JS | `skills/alpinejs.md`, `skills/vite.md` | - |
| `resources/css/**/*.css` | Estilos | `skills/tailwindcss.md`, `skills/vite.md` | - |
| `database/migrations/*.php` | Migración | `skills/database.md`, `skills/laravel.md` | - |
| `tests/**/*.php` | Pruebas | `skills/testing.md`, `skills/laravel.md` | - |
| `routes/*.php` | Rutas | `skills/laravel.md` | - |
| `.gitignore` | Configuración Git | **NINGUNA (SOLO LECTURA)** | **REGLA CERO** |

---

## 3. Protocolo de Combinación de Skills

Cuando un archivo involucre múltiples tecnologías (ej. un `.blade.php` que usa Laravel, Alpine y Tailwind):

1. **Jerarquía de Reglas:**
   - **Nivel 1 (Absoluto):** REGLA CERO (.gitignore intocable) y Prohibiciones Críticas (No delete real, No Livewire). Estas anulan cualquier sugerencia de las guías individuales.
   - **Nivel 2 (Lógica de Negocio):** `skills/laravel.md` (Arquitectura, Repositorios, Status).
   - **Nivel 3 (Implementación):** `skills/alpinejs.md`, `skills/tailwindcss.md`, `skills/php.md`.

2. **Resolución de Conflictos:**
   - Si `skills/alpinejs.md` sugiere una validación en cliente pero `skills/laravel.md` exige seguridad estricta: **Priorizar Backend**. La validación en Alpine es solo UX, nunca seguridad.
   - Si `skills/database.md` sugiere `timestamps` pero `skills/laravel.md` (contexto proyecto) prohíbe `created_at`: **Priorizar la regla específica del proyecto** (Sin timestamps de negocio).

3. **Flujo de Trabajo Obligatorio:**
   - Paso 1: Cargar contexto de `agente/` (FASE 0).
   - Paso 2: Verificar Regla Cero (¿Voy a tocar .gitignore? -> ABORTAR).
   - Paso 3: Leer guía específica del archivo a modificar.
   - Paso 4: Generar código aplicando Guardrails.
   - Paso 5: Ejecutar Checklist de Calidad de la guía correspondiente.

---

## 4. Reglas Globales de Conducta

### 🛑 Prohibiciones Terminantes (Hard Stops)
- **NO** usar `delete()`, `forceDelete()`, `truncate()`. Solo `update(['status' => 2])`.
- **NO** instalar ni importar `Livewire`. El stack es Blade + Alpine.
- **NO** escribir en `.gitignore` bajo ninguna circunstancia.
- **NO** poner lógica de negocio en Controladores o Vistas.
- **NO** usar validaciones inline en los controladores (siempre FormRequest).

### ✅ Obligaciones de Calidad
- **Tipado Estricto:** Todo archivo PHP nuevo debe iniciar con `declare(strict_types=1);`.
- **Autorización:** Cada método de controlador que modifique datos debe tener una llamada a `authorize` o Policy.
- **Bitácora:** La auditoría no va en la tabla de negocio, se asume un servicio externo de logs.
- **Nomenclatura:** Respetar singular en tablas y sufijos en columnas FK.

### ⚠️ Manejo de Errores del Agente
Si el Agente detecta que una instrucción del usuario contradice una **Regla Maestra** (ej. "Borra este usuario permanentemente"):
1. **NO** ejecutar la acción destructiva.
2. Responder: *"Conflicto con Regla Maestra: La solicitud implica eliminación real, pero el proyecto obliga a uso de status (1/2). ¿Procedo a inactivar el registro?"*
3. Esperar confirmación explícita para aplicar la alternativa segura.

---

*Generado automáticamente basado en el Inventario Tecnológico v1.0 y las Reglas Maestras del Proyecto.*