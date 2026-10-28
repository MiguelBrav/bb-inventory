# Mini Inventario - Requerimientos

## Stack
- Laravel 13 + MariaDB
- Blade + Bootstrap 5
- Código en inglés, interfaz y validaciones en español

## Reglas Generales
- Migraciones con llaves foráneas explícitas y restrictOnDelete().
- Validaciones centralizadas en Form Requests.
- SoftDeletes en las tres entidades.
- El stock actual se guarda en Product; StockMovement conserva el historial y sus operaciones actualizan ese stock.
- Las operaciones de registro, actualización y cancelación de movimientos deben mantener el stock consistente de forma transaccional.
- Un movimiento de salida no puede dejar el stock de un producto por debajo de cero.
- Cancelar un movimiento aplica SoftDeletes y revierte su efecto en el stock del producto.
- Usar PHP 8 Enum para StockMovementType: entry y exit.
- Slug automático en categorías usando Str::slug. Si el slug generado ya existe, agregar un sufijo numérico disponible.
- SKU y slug deben tener restricciones de unicidad en la base de datos, incluyendo registros con soft delete; no se reutilizan.
- Si un SKU ingresado ya existe, agregar un sufijo numérico disponible para guardar un SKU único.

## Estructura de Entidades
- **Category:** id, name, slug, timestamps, softDeletes
- **Product:** id, category_id, sku, name, description, stock, min_stock, cost_price, sale_price, expiration_date, timestamps, softDeletes
- **StockMovement:** id, product_id, type, quantity, reason, timestamps, softDeletes

## Entregables
1. **Migraciones:** Ordenadas, una por entidad (categories, products, stock_movements).
2. **Modelos:** Configurados con relaciones, $fillable y SoftDeletes.
3. **Enum:** App\Enums\StockMovementType (Entry, Exit).
4. **Controlador de productos:** ProductController con Route::resource, paginación de 15 y filtro por categoría.
5. **Form Requests de productos:** StoreProductRequest y UpdateProductRequest con reglas básicas (SKU requerido, precios válidos, etc.). Los SKU duplicados deben resolverse agregando un sufijo, no rechazarse.
6. **Vistas Blade de productos:** index, create, edit y show con Bootstrap 5, layout base y alerta visual de stock bajo.
7. **Movimientos de stock:** StockMovementController y rutas para visualizar, registrar, actualizar y cancelar movimientos; StoreStockMovementRequest y UpdateStockMovementRequest; vistas index, create, edit y show con acción de cancelación.
8. **Seeders:** CategorySeeder (3 categorías) y ProductSeeder (5 productos de prueba).

## Fuera de scope (no generar)
- Autenticación, usuarios, roles
- Endpoints API JSON
- Tests automatizados
- Docker / CI

## Criterio de aceptación
- `php artisan migrate:fresh --seed` corre sin errores.
- `/products` muestra los 5 productos del seeder con paginación.
- SKU duplicado en `store` se guarda con un sufijo numérico disponible y permanece único en la base de datos.
- Un slug de categoría duplicado se guarda con un sufijo numérico disponible y permanece único en la base de datos.
- Registrar y actualizar movimientos ajusta correctamente el stock del producto; cancelar un movimiento lo soft-deletea y revierte su efecto.
- Una salida que dejaría el stock por debajo de cero se rechaza con un error en español.
