@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a class="link-secondary text-decoration-none" href="{{ route('products.index') }}">← Productos</a>
            <h1 class="h2 mt-2 mb-1">{{ $product->name }}</h1>
            <span class="text-secondary">SKU: {{ $product->sku }}</span>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('products.edit', $product) }}">Editar</a>
            <form method="POST" action="{{ route('products.destroy', $product) }}"
                  onsubmit="return confirm('¿Eliminar este producto?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">Eliminar</button>
            </form>
        </div>
    </div>

    @if ($product->stock <= $product->min_stock)
        <div class="alert alert-warning" role="alert">
            Stock bajo: hay {{ $product->stock }} unidades y el mínimo es {{ $product->min_stock }}.
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Información del producto</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Categoría</dt>
                <dd class="col-sm-8">{{ $product->category?->name ?? 'Sin categoría' }}</dd>
                <dt class="col-sm-4">Existencia actual</dt>
                <dd class="col-sm-8">{{ $product->stock }}</dd>
                <dt class="col-sm-4">Stock mínimo</dt>
                <dd class="col-sm-8">{{ $product->min_stock }}</dd>
                <dt class="col-sm-4">Precio de costo</dt>
                <dd class="col-sm-8">${{ number_format((float) $product->cost_price, 2, ',', '.') }}</dd>
                <dt class="col-sm-4">Precio de venta</dt>
                <dd class="col-sm-8">${{ number_format((float) $product->sale_price, 2, ',', '.') }}</dd>
                <dt class="col-sm-4">Fecha de vencimiento</dt>
                <dd class="col-sm-8">{{ $product->expiration_date?->format('d/m/Y') ?? 'Sin fecha' }}</dd>
                <dt class="col-sm-4">Descripción</dt>
                <dd class="col-sm-8">{{ $product->description ?: 'Sin descripción' }}</dd>
            </dl>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Movimientos recientes</h2>
        <a class="btn btn-sm btn-outline-primary" href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}">
            Registrar movimiento
        </a>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Motivo</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($product->stockMovements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($movement->type->value === 'entry')
                                <span class="badge text-bg-success">Entrada</span>
                            @else
                                <span class="badge text-bg-danger">Salida</span>
                            @endif
                        </td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->reason }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary"
                               href="{{ route('stock-movements.show', $movement) }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-secondary py-4" colspan="5">Este producto aún no tiene movimientos.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
