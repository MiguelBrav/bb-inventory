@extends('layouts.app')

@section('title', 'Detalle del movimiento')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a class="link-secondary text-decoration-none" href="{{ route('stock-movements.index') }}">← Movimientos</a>
            <h1 class="h2 mt-2 mb-1">Detalle del movimiento</h1>
            <span class="text-secondary">Registrado {{ $stockMovement->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('stock-movements.edit', $stockMovement) }}">Editar</a>
            <form method="POST" action="{{ route('stock-movements.destroy', $stockMovement) }}"
                  onsubmit="return confirm('¿Cancelar este movimiento y revertir su efecto en el stock?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit">Cancelar movimiento</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">Producto</dt>
                <dd class="col-sm-8">
                    @if ($stockMovement->product)
                        @if ($stockMovement->product->trashed())
                            {{ $stockMovement->product->name }}
                            <span class="badge text-bg-secondary">Eliminado</span>
                        @else
                            <a href="{{ route('products.show', $stockMovement->product) }}">
                                {{ $stockMovement->product->name }}
                            </a>
                        @endif
                        <span class="text-secondary">({{ $stockMovement->product->sku }})</span>
                    @else
                        Producto no disponible
                    @endif
                </dd>
                <dt class="col-sm-4">Categoría</dt>
                <dd class="col-sm-8">{{ $stockMovement->product?->category?->name ?? 'Sin categoría' }}</dd>
                <dt class="col-sm-4">Tipo</dt>
                <dd class="col-sm-8">
                    @if ($stockMovement->type->value === 'entry')
                        <span class="badge text-bg-success">Entrada</span>
                    @else
                        <span class="badge text-bg-danger">Salida</span>
                    @endif
                </dd>
                <dt class="col-sm-4">Cantidad</dt>
                <dd class="col-sm-8">{{ $stockMovement->quantity }}</dd>
                <dt class="col-sm-4">Motivo</dt>
                <dd class="col-sm-8">{{ $stockMovement->reason }}</dd>
            </dl>
        </div>
    </div>
@endsection
