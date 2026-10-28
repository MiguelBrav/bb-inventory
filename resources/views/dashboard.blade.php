@extends('layouts.app')

@section('title', 'Panel')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Panel de inventario</h1>
            <p class="text-secondary mb-0">Resumen de productos y actividad reciente.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-primary" href="{{ route('products.create') }}">Nuevo producto</a>
            <a class="btn btn-primary" href="{{ route('stock-movements.create') }}">Registrar movimiento</a>
        </div>
    </div>

    <section class="row g-3 mb-4" aria-label="Métricas de inventario">
        <div class="col-12 col-sm-6 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="small text-secondary mb-2">Productos activos</p>
                        <svg class="text-primary" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m3 7 9-4 9 4-9 4-9-4Z"/><path d="M3 7v10l9 4 9-4V7M12 11v10"/>
                        </svg>
                    </div>
                    <p class="h3 mb-0">{{ number_format($activeProductCount) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="small text-secondary mb-2">Stock bajo</p>
                        <svg class="text-warning" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 3 2.8 20h18.4L12 3Z"/><path d="M12 9v5m0 3h.01"/>
                        </svg>
                    </div>
                    <p class="h3 text-warning-emphasis mb-0">{{ number_format($lowStockCount) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="small text-secondary mb-2">Agotados</p>
                        <svg class="text-danger" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/><path d="m9 9 6 6m0-6-6 6"/>
                        </svg>
                    </div>
                    <p class="h3 text-danger mb-0">{{ number_format($outOfStockCount) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="small text-secondary mb-2">Valor del inventario</p>
                        <svg class="text-success" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 2v20m5-15c-.8-1-2.1-1.5-4-1.5-2.5 0-4 1.2-4 3s1.5 3 4 3 4 1.2 4 3-1.5 3-4 3c-1.9 0-3.2-.5-4-1.5"/>
                        </svg>
                    </div>
                    <p class="h3 text-success mb-0">MXN ${{ $inventoryValue }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <p class="small text-secondary mb-2">Movimientos esta semana</p>
                        <svg class="text-info" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3 12a9 9 0 0 1 15.4-6.4L21 8m0-5v5h-5M21 12a9 9 0 0 1-15.4 6.4L3 16m0 5v-5h5"/>
                        </svg>
                    </div>
                    <p class="h3 mb-0">{{ number_format($weeklyMovementCount) }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4 mb-4">
        <section class="col-12 col-xl-7" aria-labelledby="stock-alerts-title">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-1" id="stock-alerts-title">Productos con stock bajo</h2>
                        <p class="small text-secondary mb-0">En o por debajo del mínimo configurado.</p>
                    </div>
                    <span class="badge text-bg-warning">{{ number_format($lowStockCount) }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($lowStockProducts->isEmpty())
                        <p class="text-secondary text-center py-5 mb-0">No hay productos con stock bajo.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4">SKU</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col" class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        <tr>
                                            <td class="ps-4 text-nowrap">{{ $product->sku }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->stock }} / {{ $product->min_stock }}</td>
                                            <td>
                                                <span class="badge {{ $product->stock === 0 ? 'text-bg-danger' : 'text-bg-warning' }}">
                                                    {{ $product->stock === 0 ? 'Agotado' : 'Stock bajo' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4 text-nowrap">
                                                <a class="btn btn-sm btn-outline-secondary"
                                                   href="{{ route('products.edit', $product) }}">Editar</a>
                                                <a class="btn btn-sm btn-outline-primary"
                                                   href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}">Entrada</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="col-12 col-xl-5" aria-labelledby="movement-chart-title">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="h5 mb-1" id="movement-chart-title">Entradas y salidas</h2>
                    <p class="small text-secondary mb-0">Unidades por día · últimos 30 días</p>
                </div>
                <div class="card-body">
                    <div style="height: 280px">
                        <canvas id="stock-movements-chart" aria-label="Gráfica diaria de entradas y salidas" role="img"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="card border-0 shadow-sm" aria-labelledby="recent-movements-title">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h2 class="h5 mb-1" id="recent-movements-title">Actividad reciente</h2>
            <p class="small text-secondary mb-0">Últimos movimientos registrados.</p>
        </div>
        <div class="card-body p-0">
            @if ($recentMovements->isEmpty())
                <p class="text-secondary text-center py-5 mb-0">Aún no hay movimientos registrados.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Producto</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Fecha</th>
                                <th scope="col" class="pe-4">Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentMovements as $movement)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold">{{ $movement->product->name }}</span>
                                        <span class="d-block small text-secondary">{{ $movement->product->sku }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $movement->type === \App\Enums\StockMovementType::Entry ? 'text-bg-success' : 'text-bg-secondary' }}">
                                            {{ $movement->type === \App\Enums\StockMovementType::Entry ? 'Entrada' : 'Salida' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($movement->quantity) }}</td>
                                    <td class="text-nowrap">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="pe-4">{{ $movement->creator?->name ?? 'No disponible' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.dashboardSeries = @json($series);
    </script>
    @vite('resources/js/dashboard.js')
@endpush
