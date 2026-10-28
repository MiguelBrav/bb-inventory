Necesito construir el dashboard principal para un sistema de gestión de inventarios hecho con Laravel (usando Breeze con Bootstrap 5) y Chart.js.

Quiero que la pantalla principal (/) sea superlimpia, funcional y con un diseño moderno. La idea es estructurarla en 4 partes clave:

## Métricas principales (Tarjetas KPI arriba):
Un bloque de tarjetas visuales que muestre de un vistazo:

Total de productos activos.
Productos con stock bajo (según un umbral o campo min_stock).
Valor total del inventario ($ suma de costo × stock).
Movimientos de inventario registrados en la semana.
(Opcional) Productos agotados.

## Alertas y accesos directos:
Botones de acción rápida en la parte superior para "Nuevo producto" y "Registrar movimiento".

Una tabla compacta destacada (de 5 a 8 filas) con los productos con stock bajo, que muestre SKU, nombre, stock actual, un badge de advertencia y accesos directos para editar o registrar entrada de stock.

## Gráfica visual (Chart.js):
Un gráfico de líneas o barras (usando Chart.js) que compare las Entradas vs. Salidas por día durante los últimos 7 o 30 días. La idea es pasar la serie de datos directamente desde el controlador con @json($series).

## Feed de actividad reciente:
Una tabla o lista pequeña con los últimos 5 a 10 movimientos registrados (producto, tipo de movimiento, cantidad, fecha y usuario).

Requerimientos:

Crea un DashboardController@index centralizado para que toda la lógica de consultas quede en el controlador y la vista se mantenga limpia.

Agrega un scopeLowStock() en el modelo Product para reutilizar la condición del stock bajo.

Optimiza las consultas de Eloquent (agrupaciones para la gráfica, cálculo del valor total del inventario, etc.).

Asegúrate de que todas las métricas respeten los Soft Deletes (excluyendo registros eliminados o agregando whereNull('deleted_at') si usas consultas con DB::table).

Utiliza los componentes nativos de Bootstrap 5 (cards, badges, icons, utilidades de color).
