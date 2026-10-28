@extends('layouts.app')

@section('title', 'Editar movimiento')

@section('content')
    <div class="mb-4">
        <a class="link-secondary text-decoration-none"
           href="{{ route('stock-movements.show', $stockMovement) }}">← Detalle del movimiento</a>
        <h1 class="h2 mt-2 mb-0">Editar movimiento</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @include('stock_movements._form', [
                'stockMovement' => $stockMovement,
                'formAction' => route('stock-movements.update', $stockMovement),
                'formMethod' => 'PUT',
                'cancelRoute' => route('stock-movements.show', $stockMovement),
            ])
        </div>
    </div>
@endsection
