# Inventario Tecnológico - La Casa El Rapidito

> Documento generado automáticamente mediante auditoría de código y archivos de configuración.
> Última actualización: 2026

---

## 1. Núcleo del Sistema e Infraestructura

| Tecnología / Herramienta | Tipo / Rol | Versión Detectada | URL Documentación Oficial |
| :--- | :--- | :--- | :--- |
| **PHP** | Lenguaje / Runtime | `^8.2` | https://www.php.net/docs.php |
| **Laravel** | Framework Backend | `^12.0` | https://laravel.com/docs/12.x |
| **MySQL / MariaDB** | Base de Datos Relacional | *Detectada (Sin versión especificada)* | https://dev.mysql.com/doc/ |
| **SQLite** | Base de Datos (Dev/Testing) | *Detectada (Sin versión especificada)* | https://www.sqlite.org/docs.html |
| **Redis** | Caché / Gestión de Colas | *Detectada (Sin versión especificada)* | https://redis.io/documentation |
| **Vite** | Build Tool / Bundler | `^7.0.7` | https://vite.dev/docs/ |
| **Node.js** | Entorno de Ejecución JS | *Detectado (Requerido por Vite)* | https://nodejs.org/docs |

---

## 2. Frameworks y Librerías de Producción

### Backend (PHP)

| Tecnología / Paquete | Categoría | Versión Detectada | URL Documentación Oficial |
| :--- | :--- | :--- | :--- |
| **Laravel Framework** | Framework MVC | `^12.0` | https://laravel.com/docs/12.x |
| **Laravel Tinker** | REPL / Consola Interactiva | `^2.10.1` | https://laravel.com/docs/12.x/artisan#tinker |

### Frontend (JavaScript & CSS)

| Tecnología / Paquete | Categoría | Versión Detectada | URL Documentación Oficial |
| :--- | :--- | :--- | :--- |
| **Alpine.js** | Framework JS Reactivo | `^3.4.2` | https://alpinejs.dev/essentials/installation |
| **Tailwind CSS** | Framework CSS Utility-First | `^3.1.0` / `^4.0.0` | https://tailwindcss.com/docs/installation |
| **@tailwindcss/forms** | Plugin Tailwind CSS | `^0.5.2` | https://github.com/tailwindlabs/tailwindcss-forms |
| **Axios** | Cliente HTTP Promesas | `^1.11.0` | https://axios-http.com/docs/intro |
| **laravel-vite-plugin** | Integración Laravel + Vite | `^2.0.0` | https://github.com/laravel/vite-plugin |

---

## 3. Herramientas de Desarrollo (DevDependencies)

### Testing y Calidad de Código

| Herramienta | Utilidad | Versión Detectada | URL Documentación Oficial |
| :--- | :--- | :--- | :--- |
| **Pest PHP** | Testing Framework Moderno | `^3.8` | https://pestphp.com/docs/installation |
| **pest-plugin-laravel** | Integración Pest + Laravel | `^3.2` | https://pestphp.com/docs/plugin-laravel |
| **Mockery** | Mocking Objects para Tests | `^1.6` | https://docs.mockery.io/ |
| **Laravel Pint** | Formateador de Código (PHP CS Fixer) | `^1.24` | https://laravel.com/docs/12.x/pint |

### Internacionalización y Utilidades

| Herramienta | Utilidad | Versión Detectada | URL Documentación Oficial |
| :--- | :--- | :--- | :--- |
| **Laravel Lang** | Paquetes de Idioma (i18n) | `^6.8` | https://laravel-lang.com/ |
| **Faker** | Generación de Datos Falsos | `^1.23` | https://fakerphp.github.io/faker/ |

### Entorno y Tooling

| Herramienta | Utilidad | Versión Detectada | URL Documentación Oficial |
| :--- | :--- | :--- | :--- |
| **Laravel Sail** | Entorno Docker para Dev | `^1.41` | https://laravel.com/docs/12.x/sail |
| **Laravel Breeze** | Starter Kit Autenticación | `^2.4` | https://laravel.com/docs/12.x/starter-kits#breeze |
| **Laravel Pail** | Logs en Tiempo Real (CLI) | `^1.2.2` | https://laravel.com/docs/12.x/logging#real-time-logs |
| **Collision** | Reporter de Errores en CLI | `^8.6` | https://github.com/nunomaduro/collision |
| **Concurrently** | Ejecución Paralela de Comandos | `^9.0.1` | https://github.com/open-cli-tools/concurrently |
| **PostCSS** | Procesador de CSS | `^8.4.31` | https://postcss.org/docs/ |
| **Autoprefixer** | Plugin PostCSS (Prefijos) | `^10.4.2` | https://github.com/postcss/autoprefixer |

---

## Notas de Auditoría

1.  **Versión de Laravel:** Se detectó Laravel 12 (`^12.0`) como la versión principal del framework.
2.  **Stack Frontend:** El proyecto utiliza una arquitectura ligera basada en **Blade + Alpine.js**, sin frameworks pesados de SPA (como React o Vue), alineado con las reglas de negocio.
3.  **Base de Datos:** Aunque el `.env.example` sugiere SQLite para desarrollo, la configuración de producción apunta a MySQL/MariaDB según los estándares del proyecto.
4.  **Herramientas de Calidad:** Se incluye **Pest** para testing y **Pint** para formateo, indicando un flujo de trabajo moderno en el ecosistema Laravel.