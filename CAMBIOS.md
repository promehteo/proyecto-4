# Cambios realizados en el layout y sidebar

## Archivos modificados/creados

1. **resources/views/layouts/app.blade.php** - Layout principal reestructurado
2. **resources/views/components/sidebar.blade.php** - Nuevo componente sidebar (creado)
3. **resources/views/categorias/index.blade.php** - Dark mode en panel/tabla
4. **resources/views/layouts/navigation.blade.php** - Eliminado (obsoleto)

## Elemento header flotante eliminado

El bloque oscuro residual era el `<header>` con `position: fixed/absolute` que estaba fuera del contenedor de contenido en el layout anterior. Se eliminó al mover el header DENTRO del contenedor de contenido con `sticky top-0`, ahora vive como primera fila del área de contenido a la derecha de la sidebar.

## Clase que controla el margen izquierdo

El margen izquierdo del contenido lo controla `x-bind:class="$store.sidebar.collapsed ? 'lg:ml-20' : 'lg:ml-64'"` en el contenedor de contenido, que lee el estado colapsado desde el Alpine store `$store.sidebar.collapsed` persistido en localStorage.

## Criterios de aceptación

- [OK] A 1280px: borde derecho del aside y borde izquierdo del contenido coinciden (sin franja oscura)
- [OK] No existe elemento fixed/absolute sobre la sidebar (salvo overlay móvil < lg)
- [OK] Header superior dentro del área de contenido, a la derecha de la sidebar
- [OK] Modo oscuro: panel/tabla de /categorias se ve oscuro (slate-900, texto slate-100)
- [OK] Colapso sidebar: ml cambia de 64 a 20 con transition-all duration-200
- [OK] Móvil: sidebar off-canvas con overlay, cierre por Escape o clic en overlay
- [OK] Enlaces, ítem activo y pie de usuario funcionan con @can

## REGLA CERO

.gitignore sin cambios verificado.
