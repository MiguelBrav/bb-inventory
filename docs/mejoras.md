### 1. Crear los Form Requests, controladores y rutas
- Archivos: `CategoryRequest`, `UpdateCategoryRequest`, `CategoryController`, `StoreStockMovementRequest`, `UpdateStockMovementRequest`, `StockMovementController` y las rutas web necesarias.
- Mantener la validación en Form Requests. Implementar categorias con paginación de 15 movimientos con visualización, registro, actualización y cancelación.
- **Dependencias:** plan anterior

### 2. Crear las vistas Blade
- Archivos: layout base, vistas de productos (`index`, `create`, `edit`, `show`) y vistas de movimientos para visualización, registro, actualización y cancelación.
- Usar Bootstrap 5, mostrar el filtro/paginación de productos y la alerta visual de stock bajo.
- **Dependencias:** paso 1 para rutas y datos

### 3. Desde las vistas de show
- Poder eliminar productos y categorias, mediante un botón de eliminar, mismo que se utiliza en los páginas correspondientes.
- Asimismo los productos deben poderse crear sin categoria, si se elimina la categoria (soft-delete), se deberia poner null el id.
- Realiza las migraciones o ajustes necesarios.
