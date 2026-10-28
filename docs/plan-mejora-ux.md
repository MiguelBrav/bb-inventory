# Plan de mejora UX: pantallas unificadas

**Estado: implementado.** Las vistas de detalle independientes se consolidaron en las pantallas de edición híbridas.

## Objetivo

En las listas de categorías, productos y movimientos de stock, eliminar la duplicidad entre las acciones **Ver** y **Editar**. Cada registro tendrá una sola acción **Editar**, que abrirá una pantalla híbrida: mostrará el contexto y los datos actuales del registro junto con el formulario para modificarlo.

La pantalla híbrida no implica permitir la edición de todos los datos. Los datos derivados o de auditoría se mantienen de solo lectura y las reglas actuales de inventario se conservan.

## Experiencia propuesta

- **Listas:** mantener búsqueda/filtros, columnas, paginación y acciones actuales de eliminación/cancelación. Sustituir los botones **Ver** y **Editar** por un único botón **Editar**. El nombre del registro no debe ofrecer un segundo acceso que parezca una acción diferente.
- **Edición:** combinar en la vista `edit` un resumen de solo lectura y el formulario existente. El resumen contextualiza el registro y evita perder la información útil que hoy solo aparece en `show`.
- **Creación:** mantener las pantallas `create` y los formularios existentes; al guardar, abrir la pantalla híbrida del registro recién creado.
- **Guardado:** tras actualizar, regresar a la pantalla híbrida del mismo registro y mostrar el mensaje de éxito.
- **Acciones destructivas:** conservar **Eliminar** para categorías/productos y **Cancelar movimiento** para movimientos como acciones independientes, con su confirmación. No confundirlas con **Editar**.
- **Navegación:** ofrecer un enlace de regreso a la lista correspondiente. Los vínculos a productos y movimientos que hoy apuntan a `show` deben dirigir a la pantalla híbrida disponible; los registros eliminados permanecen como texto sin enlace cuando no se puedan editar.

## Contenido de las pantallas híbridas

### Categorías

- Resumen visible: nombre, slug y cantidad de productos asignados.
- Formulario: reutilizar los campos actuales de edición de categoría.
- Mantener la eliminación y su comportamiento actual de desvincular los productos antes de aplicar el soft delete.

### Productos

- Resumen visible: SKU, categoría, existencia actual, stock mínimo, precios, vencimiento, descripción y alerta de stock bajo.
- Incluir en la misma pantalla el historial reciente de movimientos que hoy se muestra en el detalle del producto.
- Formulario: reutilizar los campos editables actuales. La existencia se muestra como dato de solo lectura; los cambios de stock siguen realizándose mediante movimientos.
- Mantener la creación de movimientos desde el contexto del producto y el enlace de regreso a productos.

### Movimientos de stock

- Resumen visible: fecha de registro, producto y categoría cuando estén disponibles, tipo, cantidad y motivo.
- Formulario: reutilizar los campos actuales de edición del movimiento.
- Mantener **Cancelar movimiento** y su reversión transaccional del stock. No convertir la cancelación en una edición ni alterar las reglas de validación.

## Alcance técnico

1. **Unificar vistas.** Integrar el resumen de cada `show.blade.php` correspondiente en su `edit.blade.php`, reutilizando los parciales de formulario y manteniendo las etiquetas y mensajes en español.
2. **Actualizar índices y vínculos.** Retirar los botones **Ver**, apuntar el botón **Editar** a la pantalla híbrida y actualizar vínculos internos que todavía usen rutas `*.show`.
3. **Retirar destinos de detalle independientes.** Quitar las acciones `show` de `CategoryController`, `ProductController` y `StockMovementController`, además de sus rutas de recurso; eliminar las vistas:
   - `resources/views/categories/show.blade.php`
   - `resources/views/products/show.blade.php`
   - `resources/views/stock_movements/show.blade.php`
4. **Ajustar redirecciones y controles de formulario.** Después de crear o actualizar un registro, redirigir a su ruta `*.edit` y conservar el mensaje flash. Los enlaces **Cancelar** de los formularios de edición vuelven al índice, no a una vista que se eliminará.
5. **Preservar el dominio.** No cambiar validaciones, permisos, soft deletes, relaciones, cálculo de stock ni el servicio transaccional de movimientos. No agregar módulos ni cambiar el alcance funcional.

## Archivos a revisar durante la implementación

- `routes/web.php`
- `app/Http/Controllers/CategoryController.php`
- `app/Http/Controllers/ProductController.php`
- `app/Http/Controllers/StockMovementController.php`
- `resources/views/categories/index.blade.php`, `edit.blade.php`, `show.blade.php`
- `resources/views/products/index.blade.php`, `edit.blade.php`, `show.blade.php`
- `resources/views/stock_movements/index.blade.php`, `edit.blade.php`, `show.blade.php`
- Parciales de formularios asociados y cualquier otra plantilla con enlaces a rutas `*.show`

## Criterios de aceptación

- En las tres listas existe una sola acción de navegación **Editar** por registro; no quedan botones **Ver** ni nombres enlazados a una pantalla de detalle independiente.
- Las pantallas `edit` muestran la información contextual antes disponible en `show`, junto con su formulario de edición.
- El historial reciente del producto sigue visible en la pantalla híbrida y los enlaces a movimientos no apuntan a rutas inexistentes.
- Las rutas de detalle independientes y sus vistas se retiran; no quedan referencias a rutas `categories.show`, `products.show` o `stock-movements.show`.
- Crear o actualizar una categoría, producto o movimiento termina en la pantalla híbrida correcta y conserva la notificación de éxito.
- Cancelar vuelve a la lista correspondiente sin descartar datos del servidor ni cambiar las reglas actuales.
- El stock solo cambia mediante las operaciones existentes de movimientos; actualizar o cancelar un movimiento conserva sus reglas transaccionales.
- Las operaciones de eliminar categoría/producto y cancelar movimiento continúan disponibles y mantienen sus confirmaciones.

## Verificación realizada

- `php artisan test`: 2 pruebas aprobadas.
- `php artisan route:list` para categorías, productos y movimientos: no se registran rutas `show`.
- `php artisan view:cache` y `php artisan view:clear`: compilación de vistas correcta.
- Pint en controladores y rutas modificados: sin problemas de formato.
