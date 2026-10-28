@extends('layouts.app')

@section('title', 'Movimientos de stock')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 mb-1">Movimientos de stock</h1>
            <p class="text-secondary mb-0">Consulta el historial de entradas y salidas.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('stock-movements.create') }}">Registrar movimiento</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Motivo</th>
                    <th scope="col"><span class="visually-hidden">Acciones</span></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($stockMovements as $stockMovement)
                    <tr>
                        <td class="text-nowrap">{{ $stockMovement->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($stockMovement->product)
                                @if ($stockMovement->product->trashed())
                                    <span>{{ $stockMovement->product->name }}</span>
                                    <span class="badge text-bg-secondary">Eliminado</span>
                                @else
                                    <a class="text-decoration-none"
                                       href="{{ route('products.edit', $stockMovement->product) }}">
                                        {{ $stockMovement->product->name }}
                                    </a>
                                @endif
                                <div class="small text-secondary">{{ $stockMovement->product->sku }}</div>
                            @else
                                <span class="text-secondary">Producto no disponible</span>
                            @endif
                        </td>
                        <td>
                            @if ($stockMovement->type->value === 'entry')
                                <span class="badge text-bg-success">Entrada</span>
                            @else
                                <span class="badge text-bg-danger">Salida</span>
                            @endif
                        </td>
                        <td>{{ $stockMovement->quantity }}</td>
                        <td>{{ $stockMovement->reason }}                        </td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('stock-movements.edit', $stockMovement) }}">Editar</a>
                            <form class="d-inline" method="POST"
                                  action="{{ route('stock-movements.destroy', $stockMovement) }}"
                                  onsubmit="return confirm('¿Cancelar este movimiento? El stock se actualizará para revertirlo.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-secondary py-4" colspan="6">No hay movimientos registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($stockMovements->hasPages())
            <div class="card-footer bg-white">
                {{ $stockMovements->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
