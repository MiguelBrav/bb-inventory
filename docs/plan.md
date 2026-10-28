# Plan de implementación - Mini Inventario

## Alcance y dependencias

### 1. Crear las migraciones
- Archivos: migraciones nuevas para `categories`, `products` y `stock_movements`.
- Definir las columnas indicadas en `mvp.md`, llaves foráneas explícitas con `restrictOnDelete()`, `softDeletes()` en las tres tablas y restricciones únicas para SKU y slug que incluyan los registros eliminados.
- **Dependencias:** ninguna. Este paso crea el esquema que consumirán los modelos y seeders.

### 2. Crear el enum y los modelos
- Archivos: `app/Enums/StockMovementType.php` y modelos `Category`, `Product` y `StockMovement`.
- Configurar `$fillable`, casts, `SoftDeletes` y relaciones.
- **Dependencias:** paso 1 para alinear los modelos al esquema de las tablas.

### 3. Implementar generación de SKU/slug y lógica de stock
- Archivos: lógica asociada a categorías, productos y movimientos; agregar una clase de acción/servicio solo si hace falta para mantener la lógica transaccional fuera de los controladores.
- Agregar sufijos numéricos disponibles cuando haya colisión de slug o SKU, incluyendo colisiones con registros eliminados.
- Actualizar el stock al registrar o modificar movimientos; al cancelar, aplicar soft delete y revertir el efecto. Rechazar salidas que dejarían el stock negativo.
- **Dependencias:** paso 2, por los modelos, relaciones y enum.

### 4. Crear los Form Requests, controladores y rutas
- Archivos: `StoreProductRequest`, `UpdateProductRequest`, `ProductController`, `StoreStockMovementRequest`, `UpdateStockMovementRequest`, `StockMovementController` y las rutas web necesarias.
- Mantener la validación en Form Requests. Implementar productos con paginación de 15 y filtro por categoría; movimientos con visualización, registro, actualización y cancelación.
- **Dependencias:** pasos 2 y 3.

### 5. Crear los seeders
- Archivos: `CategorySeeder`, `ProductSeeder` y ajustes puntuales al seeder principal, si son necesarios.
- Insertar 5 categorías y 20 productos de prueba.
- **Dependencias:** pasos 1 y 2; `ProductSeeder` requiere categorías existentes.

### 6. Crear las vistas Blade
- Archivos: layout base, vistas de productos (`index`, `create`, `edit`, `show`) y vistas de movimientos para visualización, registro, actualización y cancelación.
- Usar Bootstrap 5, mostrar el filtro/paginación de productos y la alerta visual de stock bajo.
- **Dependencias:** paso 4 para rutas y datos; paso 5 para validar el contenido inicial.

### 7. Verificar los criterios de aceptación
- Ejecutar `php artisan migrate:fresh --seed` en una base de datos de desarrollo desechable.
- Verificar la lista de productos, unicidad con sufijos, los cambios de stock al crear/actualizar/cancelar movimientos y el rechazo de salidas que produzcan stock negativo.
- **Dependencias:** pasos 1 a 6. No se añadirán tests automatizados, de acuerdo con el alcance del MVP.

## Inconsistencias documentales

- `AGENTS.md` indica Tailwind CSS y `mvp.md` especifica Bootstrap 5. La decisión confirmada para este MVP es Bootstrap 5; `AGENTS.md` queda fuera del alcance de este plan.
- `AGENTS.md` pide que las restricciones únicas tengan en cuenta soft deletes, mientras que `mvp.md` aclara que SKU y slug no se reutilizan y deben ser únicos incluso entre registros eliminados. La implementación seguirá la regla explícita del MVP.
