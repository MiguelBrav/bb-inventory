@extends('layouts.app')

@section('title', 'Editar movimiento')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none"
           href="{{ route('stock-movements.index') }}">← Movimientos</a>
        <h1 class="h2 mt-2 mb-1">Movimiento de stock</h1>
        <p class="text-secondary mb-0">
            Registrado {{ $stockMovement->created_at->format('d/m/Y H:i') }} · Consulta y edita sus datos.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <section class="card border-0 shadow-sm h-100" aria-labelledby="movement-summary-heading">
                <div class="card-body">
                    <h2 class="h5 mb-3" id="movement-summary-heading">Información del movimiento</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Producto</dt>
                        <dd class="col-sm-7">
                            @if ($stockMovement->product)
                                @if ($stockMovement->product->trashed())
                                    {{ $stockMovement->product->name }}
                                    <span class="badge text-bg-secondary">Eliminado</span>
                                @else
                                    <a href="{{ route('products.edit', $stockMovement->product) }}">
                                        {{ $stockMovement->product->name }}
                                    </a>
                                @endif
                                <span class="text-secondary">({{ $stockMovement->product->sku }})</span>
                            @else
                                <span class="text-secondary">Producto no disponible</span>
                            @endif
                        </dd>
                        <dt class="col-sm-5">Categoría</dt>
                        <dd class="col-sm-7">{{ $stockMovement->product?->category?->name ?? 'Sin categoría' }}</dd>
                        <dt class="col-sm-5">Tipo</dt>
                        <dd class="col-sm-7">
                            @if ($stockMovement->type->value === 'entry')
                                <span class="badge text-bg-success">Entrada</span>
                            @else
                                <span class="badge text-bg-danger">Salida</span>
                            @endif
                        </dd>
                        <dt class="col-sm-5">Cantidad</dt>
                        <dd class="col-sm-7">{{ $stockMovement->quantity }}</dd>
                        <dt class="col-sm-5">Motivo</dt>
                        <dd class="col-sm-7">{{ $stockMovement->reason }}</dd>
                    </dl>
                    <form class="mt-4" method="POST"
                          action="{{ route('stock-movements.destroy', $stockMovement) }}"
                          onsubmit="return confirm('¿Cancelar este movimiento y revertir su efecto en el stock?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit">Cancelar movimiento</button>
                    </form>
                </div>
            </section>
        </div>
        <div class="col-lg-7">
            <section class="card border-0 shadow-sm" aria-labelledby="movement-edit-heading">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3" id="movement-edit-heading">Editar movimiento</h2>
                    @include('stock_movements._form', [
                        'stockMovement' => $stockMovement,
                        'formAction' => route('stock-movements.update', $stockMovement),
                        'formMethod' => 'PUT',
                        'cancelRoute' => route('stock-movements.index'),
                    ])
                </div>
            </section>
        </div>
    </div>
@endsection
