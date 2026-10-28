@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none" href="{{ route('products.index') }}">← Productos</a>
        <h1 class="h2 mt-2 mb-1">{{ $product->name }}</h1>
        <p class="text-secondary mb-0">SKU: {{ $product->sku }} · Consulta y edita la información del producto.</p>
    </div>

    @if ($product->stock <= $product->min_stock)
        <div class="alert alert-warning" role="alert">
            Stock bajo: hay {{ $product->stock }} unidades y el mínimo es {{ $product->min_stock }}.
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-lg-5">
            <section class="card border-0 shadow-sm h-100" aria-labelledby="product-summary-heading">
                <div class="card-body">
                    <h2 class="h5 mb-3" id="product-summary-heading">Información del producto</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Categoría</dt>
                        <dd class="col-sm-7">{{ $product->category?->name ?? 'Sin categoría' }}</dd>
                        <dt class="col-sm-5">Existencia actual</dt>
                        <dd class="col-sm-7">{{ $product->stock }}</dd>
                        <dt class="col-sm-5">Stock mínimo</dt>
                        <dd class="col-sm-7">{{ $product->min_stock }}</dd>
                        <dt class="col-sm-5">Precio de costo</dt>
                        <dd class="col-sm-7">${{ number_format((float) $product->cost_price, 2, ',', '.') }}</dd>
                        <dt class="col-sm-5">Precio de venta</dt>
                        <dd class="col-sm-7">${{ number_format((float) $product->sale_price, 2, ',', '.') }}</dd>
                        <dt class="col-sm-5">Fecha de vencimiento</dt>
                        <dd class="col-sm-7">{{ $product->expiration_date?->format('d/m/Y') ?? 'Sin fecha' }}</dd>
                        <dt class="col-sm-5">Descripción</dt>
                        <dd class="col-sm-7">{{ $product->description ?: 'Sin descripción' }}</dd>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-lg-7">
            <section class="card border-0 shadow-sm" aria-labelledby="product-edit-heading">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3" id="product-edit-heading">Editar producto</h2>
                    <form method="POST" action="{{ route('products.update', $product) }}">
                        @csrf
                        @method('PUT')
                        @include('products._form')
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">Guardar cambios</button>
                            <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <section aria-labelledby="product-movements-heading">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0" id="product-movements-heading">Movimientos recientes</h2>
            <a class="btn btn-sm btn-outline-primary"
               href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}">
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
                            <td class="text-nowrap">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
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
                                <a class="btn btn-sm btn-outline-primary"
                                   href="{{ route('stock-movements.edit', $movement) }}">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-secondary py-4" colspan="5">
                                Este producto aún no tiene movimientos.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
